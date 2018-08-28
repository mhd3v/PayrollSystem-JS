<?php

include('../connection.php');

$employee_id = $_GET['employee_id'];

$query = "SELECT * FROM `employee_leaves` WHERE `EmployeeId` = {$employee_id}";

$res = mysqli_query($con, $query);

if(mysqli_num_rows($res) == 1){
    $row = mysqli_fetch_assoc($res);
    echo json_encode($row);
}
else
    echo null;
?>