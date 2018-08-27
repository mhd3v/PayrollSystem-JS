<?php

include('../connection.php');

$employee_id = $_POST['employee_id'];

$basic_sal = empty($_POST['basic_sal']) ? 0 : $_POST['basic_sal'];   //if empty then $basic_sal = 0, else posted val
$house_rent = empty($_POST['house_rent']) ? 0 : $_POST['house_rent'];
$fuel_allowance = empty($_POST['fuel_allowance']) ? 0 : $_POST['fuel_allowance'];
$utility_allowance = empty($_POST['utility_allowance']) ? 0 : $_POST['utility_allowance'];
$mobile_allowance = empty($_POST['mobile_allowance']) ? 0 : $_POST['mobile_allowance'];
$other_allowance = empty($_POST['other_allowance']) ? 0 : $_POST['other_allowance'];

$query = "select * from `employee_compensation` where `EmployeeId` = ${employee_id}";

$res = mysqli_query($con, $query);

if(mysqli_num_rows($res) == 0) {    //no record for employee compensation

    $query = "INSERT INTO `employee_compensation` 
    (`EmployeeId`,`BasicSalary`,`HouseRent`, `FuelAllowance`,`UtilityAllowance`, `MobileAllowance`, `OtherAllowance`) 
    VALUES (${employee_id},${basic_sal},${house_rent},${fuel_allowance},${utility_allowance},${mobile_allowance},
    ${other_allowance})";

    $res = mysqli_query($con, $query);

    if($res)
    echo 1;
    else
    echo 'Error inserting into database';

}

else {

    $query = "Update `employee_compensation` 
    SET 
    `BasicSalary` = ${basic_sal}, `HouseRent` = ${house_rent},  
    `FuelAllowance` = ${fuel_allowance},`UtilityAllowance` = ${utility_allowance},
    `MobileAllowance` = ${mobile_allowance}, `OtherAllowance`= ${other_allowance}
    WHERE 
    `EmployeeId` = ${employee_id}";

    $res = mysqli_query($con, $query);

    if($res)
    echo 1;
    else
    echo 'Error inserting into database';

}

?>