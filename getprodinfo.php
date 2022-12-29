<?php
require('connection.php');
$response_array = ['responseCode' => 500, 'reponseMessage' => 'Cannot Find Product', 'responseData' => null];
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $result = mysqli_query($con, "select * from tblproduct where prodid=$id");
    $result = mysqli_fetch_assoc($result);
    $prodprice = $result['prodcost'];
    $stockinhand = $result['stockinhand'];
    $uom = $result['uom'];
    $array = ['prodprice' => $prodprice, 'stockinhand' => $stockinhand, 'produom' => $uom];
    $response_array = ['responseCode' => 200, 'reponseMessage' => 'Product Found', 'responseData' => $array];
}
echo json_encode($response_array);

?>
