<?php

include('../connection.php');

$records_to_delete = $_POST['loansToDelete'];

$deleted_records = array();

foreach($records_to_delete as $loan_id){

    $record = mysqli_fetch_assoc(mysqli_query($con, "Select * from employee_loans where Id = {$loan_id}"));

    $res = mysqli_query($con, "Delete from employee_loans where Id = {$loan_id}");

    if(!$res)
        echo 0;
    else
        array_push($deleted_records, $record);

}

echo json_encode($deleted_records);

?>