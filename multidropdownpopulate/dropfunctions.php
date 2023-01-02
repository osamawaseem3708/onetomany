<?php
require_once ('../connection.php');
if (isset($_GET['function']) && $_GET['function'] == 'getOfferDetailBasedOnID') {
    getOfferDetailBasedOnID ($con, $_POST['id']);
}
function getOfferDetailBasedOnID($con, $id)
{
    $response_array = ['responseCode' => 500, 'responseMessage' => 'Data No Found', 'options' => null];
    $res = mysqli_query ($con, "SELECT * FROM `tblofferdetail` od join tblmenu m on m.menuid=od.menuid where offhid = $id");
    $options = '<option value=""> --- Choose --- </option>';
    while ($row = mysqli_fetch_assoc ($res)) {
        $options .= '<option value="' . $row['menuid'] . '">' . $row['mname'] . '</option>';
    }
    if (!empty($options)) {
        $response_array = ['responseCode' => 200, 'responseMessage' => 'Data Found', 'options' => $options];
    }
    echo json_encode ($response_array);
}

function getAllOffers($con)
{
    $res = mysqli_query ($con, "SELECT * FROM `tblofferhead`");
    $options = '<option value=""> --- Choose --- </option>';
    while ($row = mysqli_fetch_assoc ($res)) {
        $options .= '<option value="' . $row['offhid'] . '">' . $row['offname'] . '</option>';
    }
    echo $options;
}

?>
