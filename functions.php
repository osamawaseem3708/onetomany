<?php
require('connection.php');

function getProductDropDownOptions($con)
{
    $result = mysqli_query($con, 'select * from tblproduct where stockinhand > 0');
    $select = ' <option value="">-- Choose --</option>';
    while ($row = mysqli_fetch_assoc($result)) {
        $select .= '<option value="' . $row['prodid'] . '">' . $row['prodname'] . '</option>';
    }
    echo $select;
}

function getUomDropDownOptions($con)
{
    $result = mysqli_query($con, "SELECT * FROM `tbluom`");
    $select = ' <option value="">-- Choose --</option>';
    while ($row = mysqli_fetch_assoc($result)) {
        $select .= '<option value="' . $row['longname'] . '">' . $row['longname'] . '</option>';
    }
    echo $select;
}

function getAllUoms($con)
{
    $uom_name_array = [];
    $result = mysqli_query($con, "SELECT * FROM `tbluom`");
    while ($single_uom = mysqli_fetch_assoc($result)) {
        $uom_name_array[$single_uom['longname']] = $single_uom['divideby'];
    }
    echo json_encode($uom_name_array);
}

function getPreproductionDetailAgainstId($con, $prodid)
{
    $detail_stockin_hand = $detail_price = $detail_prodid = $detail_qty = $detail_uom = [];
    $all_uom_array = getProductUomArray($con);
    $result = mysqli_query($con, "SELECT *, pd.prodid as detail_prodid ,pd.stockinhand as detail_stockinhand , pd.uom as detail_uom FROM `tblpreproductionhead` ph join tblpreproductiondetail pd on pd.prehid = ph.prehid where ph.prehid=$prodid");
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $detail_prodid[] = $row['detail_prodid'];
            $detail_price[] = $row['cost'];
            $detail_stockin_hand[] = $row['detail_stockinhand'];
            $detail_uom[] = array_search($row['detail_uom'], ($all_uom_array));
            $detail_qty[] = $row['weight'];
        }
        $response_array = ['detail_prodid' => $detail_prodid, 'detail_price' => $detail_price, 'detail_stockinhand' => $detail_stockin_hand, 'detail_qty' => $detail_qty, 'detail_uom' => $detail_uom];
        return json_encode($response_array);
    }

}

function getProductUomArray($con)
{
    $result = mysqli_query($con, "SELECT * FROM `tbluom` ");
    while ($row = mysqli_fetch_assoc($result)) {
        $uom_array[$row['longname']] = $row['shortname'];
    }
    return $uom_array;
}


?>

