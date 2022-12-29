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
    $uom_name_array = $uom_divide_by_array = [];
    $result = mysqli_query($con, "SELECT * FROM `tbluom`");
    while ($single_uom = mysqli_fetch_assoc($result)) {
        $uom_name_array[$single_uom['longname']] = $single_uom['divideby'];
    }
    echo json_encode($uom_name_array);
}

?>

