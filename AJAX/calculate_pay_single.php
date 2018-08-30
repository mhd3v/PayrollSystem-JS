<?php

include('../connection.php');
include('calculate_pay.php');

$id = $_POST['employee_id'];
$month_year = $_POST['month_year'];

echo json_encode(calculate_pay($con, $id, $month_year));

?>