<?php

include('../connection.php');

$q = trim($_GET['term']);

$query = "SELECT * FROM `employees` WHERE ((`FullName` LIKE '%${q}%') OR (`Code` LIKE '%${q}%') OR (`CNIC` LIKE '%${q}%'))";

$res = mysqli_query($con, $query);

$filtered_results = array();

if(mysqli_num_rows($res) > 0){
    while($row = mysqli_fetch_assoc($res))
    array_push($filtered_results, $row);
}

echo json_encode($filtered_results);


?>