<?php

include('../connection.php');

$id = $_POST['employee_id'];
$month_year = $_POST['month_year'];

$res = mysqli_query($con, "Select * from employees where Id = {$id}");

if($res){

    $employee_record = mysqli_fetch_assoc($res);
    
    

}



?>