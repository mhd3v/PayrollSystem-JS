<?php

include('../connection.php');
include('calculate_pay.php');

$employees = mysqli_query($con, "Select Id from employees");
$pay_data = array();

if($employees){
    
    while($row = mysqli_fetch_assoc($employees)){

        $employee_id = $row['Id'];

        $employee_pay = calculate_pay($con, $employee_id, '1-2019');

        if($employee_pay['Status'] == 1){
            array_push($pay_data, $employee_pay);
        }

    }

    echo json_encode($pay_data);
}

?>