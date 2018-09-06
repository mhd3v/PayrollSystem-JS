<?php

include('../connection.php');

$employee_id = $_POST['employee_id'];

$total_amt = $_POST['total_amt'];
$total_installments = $_POST['total_installments'];
$installment_amt = $total_amt/$total_installments;
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];

$query = "select * from `employees` where `Id` = ${employee_id}";

$res = mysqli_query($con, $query);

if($res && (mysqli_num_rows($res) == 1)) { 

    if(mysqli_num_rows(mysqli_query($con, "Select * from employee_loans where EmployeeId = ${employee_id} AND 
    StartDate = '${start_date}' AND EndDate= '${end_date}'")) == 0){

        $query = "INSERT INTO `employee_loans` 
        (`EmployeeId`,`TotalAmount`,`TotalInstallments`, `InstallmentAmount`,`StartDate`, `EndDate`) VALUES 
        (${employee_id},${total_amt},${total_installments},${installment_amt},'${start_date}','${end_date}')";

        $res = mysqli_query($con, $query);

        if($res){
        echo json_encode(mysqli_fetch_assoc(mysqli_query($con, 'Select * from employee_loans ORDER BY Id DESC LIMIT 1;')));
        }

    }

    else {
        $errorField = array('error'=> 'Same data between the set range exists for the user');
        //'fieldErrors' => array(array('name' => 'StartDate', 'status' => 'Samedate set'), array('name' => 'EndDate', 'status' => 'Samedate set'))
        echo json_encode($errorField);
    }

}
?>