<?php
require('connection.php');

function getUomDropDownOptions($con)
{
    $result=mysqli_query($con,"SELECT * FROM `tbluom`");
    $select = ' <option value="">-- Choose --</option>';
    while ($row = mysqli_fetch_assoc($result)) {
        $select .= '<option value="' . $row['longname'] . '">' .  $row['longname'] . '</option>';
    }
    echo $select;
}
?>

