<?php
require ('connection.php');
function getProductDropDownOptions($con)
{
    $result = mysqli_query ($con, 'select * from tblproduct where stockinhand > 0');
    $select = ' <option value="">-- Choose --</option>';
    while ($row = mysqli_fetch_assoc ($result)) {
        $select .= '<option value="' . $row['prodid'] . '">' . $row['prodname'] . '</option>';
    }
    echo $select;
}

function getUomDropDownOptions($con)
{
    $result = mysqli_query ($con, "SELECT * FROM `tbluom`");
    $select = ' <option value="">-- Choose --</option>';
    while ($row = mysqli_fetch_assoc ($result)) {
        $select .= '<option value="' . $row['longname'] . '">' . $row['longname'] . '</option>';
    }
    echo $select;
}

function getAllUoms($con, $flag = 'E')
{
    $uom_name_array = [];
    $result = mysqli_query ($con, "SELECT * FROM `tbluom`");
    while ($single_uom = mysqli_fetch_assoc ($result)) {
        $uom_name_array[$single_uom['longname']] = $single_uom['divideby'];
    }
    if ($flag == 'E') {
        echo json_encode ($uom_name_array);
    } else {
        return $uom_name_array;
    }
}

function getPreproductionDetailAgainstId($con, $prodid)
{
    $detail_stockin_hand = $detail_price = $detail_prodid = $detail_qty = $detail_uom = [];
    $all_uom_array = getProductUomArray ($con);
    $result = mysqli_query ($con, "SELECT *, pd.prodid as detail_prodid ,pd.stockinhand as detail_stockinhand , pd.uom as detail_uom FROM `tblpreproductionhead` ph join tblpreproductiondetail pd on pd.prehid = ph.prehid where ph.prehid=$prodid");
    if (mysqli_num_rows ($result) > 0) {
        while ($row = mysqli_fetch_assoc ($result)) {
            $detail_prodid[] = $row['detail_prodid'];
            $detail_price[] = $row['cost'];
            $detail_stockin_hand[] = $row['detail_stockinhand'];
            $detail_uom[] = array_search ($row['detail_uom'], ($all_uom_array));
            $detail_qty[] = $row['weight'];
        }
        $response_array = ['detail_prodid' => $detail_prodid, 'detail_price' => $detail_price, 'detail_stockinhand' => $detail_stockin_hand, 'detail_qty' => $detail_qty, 'detail_uom' => $detail_uom];
        return json_encode ($response_array);
    }

}

function getProductUomArray($con)
{
    $result = mysqli_query ($con, "SELECT * FROM `tbluom` ");
    while ($row = mysqli_fetch_assoc ($result)) {
        $uom_array[$row['longname']] = $row['longname'];
    }
    return $uom_array;
}

function calculateStockUomWise($con, $head_uom, $qty)
{
    $uom_array = getAllUoms ($con, 'R');
    foreach ($uom_array as $key => $value) {
        if ($key == $head_uom) {
            $qty = $qty / $value;
        }
    }
    return $qty;
}

function getPreproductionHeadItem($con, $prehid)
{
    $res = mysqli_query ($con, "select * from tblpreproductionhead where prehid=$prehid");
    return mysqli_fetch_assoc ($res);
}

function getPreproductionDetailItems($con, $prehid)
{
    return mysqli_query ($con, "SELECT * FROM `tblpreproductiondetail` where prehid= '$prehid'");

}

function insertPreProductionDetailAndUpdateStockAlso($con, $detail_prodid, $detail_price, $detail_stockinhand, $detail_qty, $detail_amount, $detail_uom, $pre_production_id)
{

    $status = 'Active';
    $lastmoddate = date ('Y-m-d H:i:s');
    $lastmoduser = 1;
    if (!empty($detail_prodid) && !empty($detail_price) && !empty($detail_stockinhand) && !empty($detail_qty) && !empty($detail_amount) && !empty($detail_uom) && !empty($pre_production_id)) {
        for ($i = 0; $i < count ($detail_prodid); $i++) {
            // Inserting in detail
            $prodid = $detail_prodid[$i];
            $weight = $detail_qty[$i];
            $cost = $detail_price[$i];
            $stockinhand = $detail_stockinhand[$i];
            $uom = $detail_uom[$i];
//            need to be add
            $detail_res = mysqli_query ($con, "INSERT INTO `tblpreproductiondetail`(`prehid`, `prodid`, `weight`, `cost`, `stockinhand`, `status`, `uom`, `lastmoddate`, `lastmoduser`) VALUES ('$pre_production_id','$prodid','$weight','$cost','$stockinhand','$status','$uom','$lastmoddate','$lastmoduser')");
//                Updating Quantity of detail stock here
//        calculating stock according to detail uom
            $weight = calculateStockUomWise ($con, $uom, $weight);
//        UPDATING PRE PRODUCTION ITEM IN STOCK
            mysqli_query ($con, "update tblproduct set stockinhand = stockinhand - $weight where prodid=$prodid");

        }
        if (isset($detail_res) && isset($res)) {
            echo "INSERTED";
            exit;
        }
    }
}

function reversingPreProductionItemInStockAgainstPreproduction($con, $prehid)
{
    $previous_detail_items = getPreproductionDetailItems ($con, $prehid);
    $lastmoddate = date ('Y-m-d H:i:s');
    $lastmoduser = 1;
    while ($row = mysqli_fetch_assoc ($previous_detail_items)) {
        $old_stock = calculateStockUomWise ($con, $row['uom'], $row['weight']);
        $prodid = $row['prodid'];
        mysqli_query ($con, "UPDATE `tblproduct` SET `stockinhand`=stockinhand+$old_stock,`lastmoddate`='$lastmoddate',`lastmoduser`='$lastmoduser' WHERE prodid='$prodid'");
    }
}

?>
