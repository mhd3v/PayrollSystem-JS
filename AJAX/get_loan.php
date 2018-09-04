<?php

include('../connection.php');

$employee_id = $_POST['employee_id'];

$query = "SELECT * FROM `employee_loans` WHERE `EmployeeId` = {$employee_id}";

$res = mysqli_query($con, $query);

$loans = array();

if(mysqli_num_rows($res) != 0){

    while($row = mysqli_fetch_assoc($res))
        array_push($loans, $row);

    echo json_encode($loans);
}
else
    echo '{"sEcho": 1,"iTotalRecords": "0","iTotalDisplayRecords": "0","aaData": []}';  //this response tells datatables that no records found
?>