<?php

include('../connection.php');

$Id = $_POST['Id'];
$propertyToChange = $_POST['propertyToChange'];
$newVal = $_POST['newVal'];

$query = "UPDATE `employees` SET `{$propertyToChange}` = NULLIF('{$newVal}','') WHERE Id = {$Id}";

$res = mysqli_query($con, $query);

if($res){
    $res = mysqli_query($con, "Select * from `employees` where Id = {$Id}");
    $row = mysqli_fetch_assoc($res);
    echo json_encode($row);
}

?>