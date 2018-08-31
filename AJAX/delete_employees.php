<?php

include('../connection.php');

$record_to_delete = $_POST['recordsToDelete'];

$deleted_records = array();

foreach($record_to_delete as $record_id){

    $record = mysqli_fetch_assoc(mysqli_query($con, "Select * from employees where Id = {$record_id}"));
    array_push($deleted_records, $record);

    $res = mysqli_query($con, "Delete from employees where Id = {$record_id}");
    if(!$res)
    echo 0;
}

echo json_encode($deleted_records);

?>