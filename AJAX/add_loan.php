<?php

include('../connection.php');

$employee_id = $_POST['employee_id'];

$total_amt = $_POST['total_amt'];
$total_installments = $_POST['total_installments'];
$installment_amt = $total_amt/$total_installments;
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];

$query = "select * from `employee_loans` where `EmployeeId` = ${employee_id}";

$res = mysqli_query($con, $query);

if(mysqli_num_rows($res) == 0) {    //no record for employee compensation

    $query = "INSERT INTO `employee_loans` 
    (`EmployeeId`,`TotalAmount`,`TotalInstallments`, `InstallmentAmount`,`StartDate`, `EndDate`) 
    VALUES (${employee_id},${total_amt},${total_installments},${installment_amt},'${start_date}','${end_date}')";

    $res = mysqli_query($con, $query);

    if($res)
    echo 1;
    else
    echo $query;

}

else {

    $query = "Update `employee_loans` 
    SET 
    `TotalAmount` = ${total_amt}, `TotalInstallments` = ${total_installments},  
    `InstallmentAmount` = ${installment_amt},`StartDate` = '${start_date}',
    `EndDate` = '${end_date}'
    WHERE 
    `EmployeeId` = ${employee_id}";

    $res = mysqli_query($con, $query);

    if($res)
    echo 1;
    else
    echo $query;

}

?>