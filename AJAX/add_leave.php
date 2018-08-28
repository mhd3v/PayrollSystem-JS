<?php

include('../connection.php');

$employee_id = $_POST['employee_id'];

$total_annual = empty($_POST['total_annual']) ? 0 : $_POST['total_annual'];
$total_sick = empty($_POST['total_sick']) ? 0 : $_POST['total_sick'];
$total_casual = empty($_POST['total_casual']) ? 0 : $_POST['total_casual'];
$annual_availed = empty($_POST['annual_availed']) ? 0 : $_POST['annual_availed'];
$sick_availed = empty($_POST['sick_availed']) ? 0 : $_POST['sick_availed'];
$casual_availed = empty($_POST['casual_availed']) ? 0 : $_POST['casual_availed'];
$without_pay = empty($_POST['without_pay']) ? 0 : $_POST['without_pay'];
$month_year = $_POST['month_year'];

$query = "select * from `employee_leaves` where `EmployeeId` = ${employee_id}";

$res = mysqli_query($con, $query);

if(mysqli_num_rows($res) == 0) {    //no record for employee compensation

    $query = "INSERT INTO `employee_leaves` 
    (`EmployeeId`,`TotalAnnualLeaves`,`TotalSickLeaves`, `TotalCasualLeaves`,`LeavesWithoutPay`, `AnnualLeavesAvailed`,
    `SickLeavesAvailed`, `CasualLeavesAvailed`, `MonthYear`) 
    VALUES (${employee_id},${total_annual},${total_sick},${total_casual},${without_pay},${annual_availed},
    ${sick_availed},${casual_availed},'${month_year}')";

    $res = mysqli_query($con, $query);

    if($res)
    echo 1;
    else
    echo $query;

}

else {

    $query = "Update `employee_leaves` 
    SET 
    `TotalAnnualLeaves` = ${total_annual}, `TotalSickLeaves` = ${total_sick},  
    `TotalCasualLeaves` = ${total_casual},`LeavesWithoutPay` = ${without_pay},
    `AnnualLeavesAvailed` = ${annual_availed}, `SickLeavesAvailed`= ${sick_availed},
    `CasualLeavesAvailed` = ${casual_availed}, `MonthYear`= '${month_year}'
    WHERE 
    `EmployeeId` = ${employee_id}";

    $res = mysqli_query($con, $query);

    if($res)
    echo 1;
    else
    echo $query;

}

?>