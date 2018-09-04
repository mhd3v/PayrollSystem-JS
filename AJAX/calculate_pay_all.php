<?php

include('../connection.php');
include('calculate_pay.php');

$employees = mysqli_query($con, "Select Id from employees");
$pay_data = array();

if($employees){
    
    while($row = mysqli_fetch_assoc($employees)){

        $employee_id = $row['Id'];

        $employee_pay = calculate_pay($con, $employee_id, $_POST['month_year']);

        if($employee_pay['Status'] == 1){
            array_push($pay_data, $employee_pay);
        }

    }

    if(sizeof($pay_data) != 0)
        echo json_encode($pay_data);
    else
        echo '{"sEcho": 1,"iTotalRecords": "0","iTotalDisplayRecords": "0","aaData": []}';  //this response tells datatables that no records found
}

else
    echo '{"sEcho": 1,"iTotalRecords": "0","iTotalDisplayRecords": "0","aaData": []}';  //this response tells datatables that no records found

?>