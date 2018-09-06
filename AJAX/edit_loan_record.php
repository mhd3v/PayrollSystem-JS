<?php

include('../connection.php');

$Id = $_POST['Id'];
$eId = $_POST['employeeId'];
$propertyToChange = $_POST['propertyToChange'];
$newVal = $_POST['newVal'];
$error = false;

if($propertyToChange == 'StartDate' || $propertyToChange == 'EndDate'){

    $query = "Select * from employee_loans where Id = ${Id}";
    $res = mysqli_query($con, $query);

    if($res && mysqli_num_rows($res) != 0){

        $errorField = array('error'=> 'Same range exists for the user', 'fieldErrors' => array(array('name' => 'StartDate', 'status' => 'Same date range exists for the user')
        , array('name' => 'EndDate', 'status' => 'Same date range exists for the user')));

        if($propertyToChange == 'StartDate'){

            $end_date = mysqli_fetch_assoc($res)['EndDate'];

            $res = mysqli_query($con, "Select * from employee_loans where Id != ${Id} AND EmployeeId = ${eId} AND 
            StartDate = '${newVal}' AND EndDate= '${end_date}'");

            if($res && mysqli_num_rows($res) != 0){
                $error = true;
                echo json_encode($errorField);
            }
        }

        else if($propertyToChange == 'EndDate'){

            $start_date = mysqli_fetch_assoc($res)['StartDate'];

            $res = mysqli_query($con, "Select * from employee_loans where Id != ${Id} AND EmployeeId = ${eId} AND 
            EndDate = '${newVal}' AND StartDate= '${start_date}'");

            if($res && mysqli_num_rows($res) != 0){
                $error = true;
                echo json_encode($errorField);
            }
        }
       
    }
}
    
else if(!$error){

    $query = "Select * from employee_loans WHERE Id = {$Id}";
    $res = mysqli_query($con, $query);

    if($res){

        if($propertyToChange == 'TotalAmount'){
            $installmentAmt = $newVal / mysqli_fetch_assoc($res)['TotalInstallments'];
        }
        
        else if($propertyToChange == 'TotalInstallments'){
            $installmentAmt = mysqli_fetch_assoc($res)['TotalAmount'] / $newVal ;
        }

        else
            $installmentAmt = mysqli_fetch_assoc($res)['InstallmentAmount'];

        $query = "UPDATE employee_loans SET `{$propertyToChange}` = '{$newVal}', InstallmentAmount = ${installmentAmt} WHERE Id = {$Id}";

        $res = mysqli_query($con, $query);

        if($res){
            $res = mysqli_query($con, "Select * from `employee_loans` where Id = {$Id}");
            $row = mysqli_fetch_assoc($res);
            echo json_encode($row);
        }
        else{
            echo 'query error';
        }
    }


}


?>