<?php

include('../connection.php');

$query = "SELECT * FROM `employees`";

$res = mysqli_query($con, $query);

$allEmployees = array();

if(mysqli_num_rows($res) != 0){
    while($row = mysqli_fetch_assoc($res))
        array_push($allEmployees, $row);
    echo json_encode($allEmployees);
}

else
    echo null;
?>