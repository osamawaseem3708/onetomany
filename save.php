<?php
require('connection.php');
if (isset($_POST['submit']) && !empty($_POST)) {
    echo "<pre>";
    print_r($_POST);

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

//    NOW INSERTING IN HEAD TABLE ON CURRENT CONNECTION AND AFTER INSERTION GETTING BACK PRIMARY KEY THAT WOULD BE USED IN DETAIL TABLE AS FOREIGN KEY

    $res = mysqli_query($con, "INSERT INTO `tblpreproductionhead`(`predate`, `employeeid`, `finishedqty`, `uom`, `prodid`, `stockinhand`, `remarks`, `status`, `lastmoddate`, `lastmoduser`, `branchid`) VALUES ('$pre_production_date','$employee_id','$finished_qty','$uom_head','$finished_prodname','$stockinhand_head','$remarks','$status','$lastmoduser','$lastmoduser','$branch_id')");

    $last_pre_production_id = mysqli_insert_id($con);

    if(!empty($detail_prodid) && !empty($detail_price) && !empty($detail_stockinhand) && !empty($detail_qty) && !empty($detail_amount)){
        for($i=0; $i < count($detail_prodid); $i++){
            // Inserting in detail
            $prodid=$detail_prodid[$i];
            $weight=$detail_qty[$i];
            $cost=$detail_price[$i];
            $stockinhand=$detail_stockinhand[$i];

//            need to be add
           $uom = 'kg';
         $detail_res=  mysqli_query($con,"INSERT INTO `tblpreproductiondetail`(`prehid`, `prodid`, `weight`, `cost`, `stockinhand`, `status`, `uom`, `lastmoddate`, `lastmoduser`) VALUES ('$last_pre_production_id','$prodid','$weight','$cost','$stockinhand','$status','$uom','$lastmoddate','$lastmoduser')");

        }
        if(isset($detail_res) && isset($res)){
            echo "INSERTED";exit;
        }
    }
}

?>

