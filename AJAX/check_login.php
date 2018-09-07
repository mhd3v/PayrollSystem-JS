<?php

include('../connection.php');

$user = $_POST['username'];
$pass = $_POST['password'];

$query = "select * from users where UserName = '{$user}' and Password = '{$pass}'";
$res = mysqli_query($con, $query);
if($res && mysqli_num_rows($res) == 1){

    $row = mysqli_fetch_assoc($res);
    
    session_start();
    $_SESSION['user'] = $row['UserName'];
    echo 1;
}
else
echo "No record found";

?>