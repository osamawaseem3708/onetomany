<?php
require('connection.php');
require('functions.php');
if (isset($_POST['submit']) && !empty($_POST)) {

//    GETTING HERE HEAD FORM VALUES FROM POST
    $status = "Active";
    $lastmoddate = date('Y-m-d H:i:s');
    $lastmoduser = 1;
    $branch_id = 1;
    $finished_prodname = $_POST['finished_prodname'];
    $finished_qty = $_POST['finishedqty'];
    $pre_production_date = $_POST['predate'];
    $employee_id = $_POST['employeeid'];
    $stockinhand_head = $_POST['stockinhand_head'];
    $uom_head = $_POST['uom_head'];
    $remarks = $_POST['remarks'];
//    GETTING HERE DETAIL OF PREPRODUCTION VALUES FROM POST
    $detail_prodid = $_POST['detail_prodid'];
    $detail_price = $_POST['detail_price'];
    $detail_stockinhand = $_POST['detail_stockinhand'];
    $detail_qty = $_POST['detail_qty'];
    $detail_amount = $_POST['detail_amount'];
    $detail_uom = $_POST['detail_uom'];
    if ($_POST['flag'] != 'U') {
//    NOW INSERTING IN HEAD TABLE ON CURRENT CONNECTION AND AFTER INSERTION GETTING BACK PRIMARY KEY THAT WOULD BE USED IN DETAIL TABLE AS FOREIGN KEY
        $res = mysqli_query($con, "INSERT INTO `tblpreproductionhead`(`predate`, `employeeid`, `finishedqty`, `uom`, `prodid`, `stockinhand`, `remarks`, `status`, `lastmoddate`, `lastmoduser`, `branchid`) VALUES ('$pre_production_date','$employee_id','$finished_qty','$uom_head','$finished_prodname','$stockinhand_head','$remarks','$status','$lastmoduser','$lastmoduser','$branch_id')");
        $last_pre_production_id = mysqli_insert_id($con);
//        calculating stock according to uom
        $finished_qty = calculateStockUomWise($con, $uom_head, $finished_qty);
//        UPDATING PRE PRODUCTION ITEM IN STOCK
        mysqli_query($con, "update tblproduct set stockinhand = stockinhand + $finished_qty where prodid=$finished_prodname");
        insertPreProductionDetailAndUpdateStockAlso($con, $detail_prodid, $detail_price, $detail_stockinhand, $detail_qty, $detail_amount, $detail_uom, $last_pre_production_id);

    } else {
//        HERE CODE OF UPDATE SHOULD BE WRITTEN
        $prehid = $_POST['prehid'];
        $head_stock = getPreproductionHeadItem($con, $prehid);
        $previous_finished_qty = calculateStockUomWise($con, $head_stock['uom'], $head_stock['finishedqty']);
        $res = mysqli_query($con, "UPDATE `tblpreproductionhead` SET `predate`='$pre_production_date',`employeeid`='$employee_id',`finishedqty`='$finished_qty',`uom`='$uom_head',`prodid`='$finished_prodname',`stockinhand`='$stockinhand_head',`remarks`='$remarks',`status`='$status',`lastmoddate`='$lastmoddate',`lastmoduser`='$lastmoduser',`branchid`='$branch_id' WHERE prehid = '$prehid'");
        $finished_qty = calculateStockUomWise($con, $uom_head, $finished_qty);
        mysqli_query($con, "UPDATE tblproduct set stockinhand=stockinhand-$previous_finished_qty+$finished_qty, lastmoddate='$lastmoddate',lastmoduser='$lastmoduser' where prodid='$finished_prodname'");
//        NOW REVERSING DETAIL FOR PREPRODUCTION ITEMS
        reversingPreProductionItemInStockAgainstPreproduction($con, $prehid);
//        NOW ADDING UPDATED STOCK
        insertPreProductionDetailAndUpdateStockAlso($con, $detail_prodid, $detail_price, $detail_stockinhand, $detail_qty, $detail_amount, $detail_uom, $prehid);

    }
    echo "<script>alert('Action Performed Successfully')</script>";
    echo "<script>window.location.href='manage-preproduction.php'</script>";
}
?>
