<?php
$page_name = 'Welcome';
include('header.php');

include('navbar.php');

if(session_status() == PHP_SESSION_NONE) 
session_start();
if(!isset($_SESSION['user'])){
?>

<h1 style="text-align:center" class="m-5">Login to Payroll System</h1>

<div class="row justify-content-center no-gutters">

    <div class="col-8 col-md-5">

        <form style="background-color:#f2f2f2;border-radius:10px;">
        
            <div class="row justify-content-center p-5">

                <div class="form-group col-sm-10 col-md-7">
                    <label for="">Username:</label>
                    <input name="username" type="text" class="form-control" required autofocus>
                </div>

                <div class="w-100"></div>

                <div class="form-group col-sm-10 col-md-7">
                    <label for="">Password:</label>
                    <input name="password" type="password" class="form-control" required>
                </div>

                <div class="w-100"></div>

                <div class="text-center">
                    <input type="submit" value="Login" class="btn btn-primary">
                </div>

                <div class="w-100"></div>

                <div style="display:none" id="msg" class="alert alert-danger mt-3"></div>

            </div>
            
        </form>

    </div>
    
</div>

<script>
    $('document').ready(function(e){
        $('form').on('submit', function(e){

            e.preventDefault();
            
            $.ajax({
              type: 'post',
              url: 'ajax/check_login.php',
              data: $('form').serialize(),
              success: function (data) {
                
                if(data == 1)
                  $(location).attr('href', 'index.php')
                
                else{
                  $("#msg").html(data);
                  $("#msg").show();
                }
              },
              error:function (data) {
                $("#msg").html("failed to connect to server");
              }
            });
        })
    });
</script>

</body>
</html>

<?php } 

else{ ?>


<div class="text-center" style="margin-top:15%;">
<h3>Welcome to Payroll Management System!</h3>
<h5 class="mt-3">You are logged in as <?=$_SESSION['user']?></h5>
</div>


<?php }?>