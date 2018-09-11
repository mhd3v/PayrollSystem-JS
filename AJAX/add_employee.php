<?php

include('../connection.php');

$code = $_POST['code'];
$full_name = $_POST['full_name'];
$city = $_POST['city'];
$designation = $_POST['designation'];
$mob_number = $_POST['mob_number'];
$cnic = $_POST['cnic'];
$address = $_POST['address'];
$dep = $_POST['dep'];
$bank_acc = $_POST['bank_acc'];

$query = "INSERT INTO `employees` 
(`Code`, `FullName`, `MobileNumber`, `Designation`, `Department`, `CNIC`, `Address`, `City`, `BankAccount`)
VALUES ('${code}', '${full_name}', '${mob_number}', '${designation}', '{$dep}', '${cnic}', '${address}', '${city}', '${bank_acc}')";

$res = mysqli_query($con, $query);

if($res)
echo 1;
else
echo 'Failed to insert data in database';

?>