<?php
$page_name = 'Add Employee';
include('header.php');
?>
</head>
<body>

<?php 

include('navbar.php');

if(session_status() == PHP_SESSION_NONE) 
session_start();
if(isset($_SESSION['user'])){ 
    
include('connection.php');
$res = mysqli_query($con, "Select Max(Id) from employees");
$LatestId = 1;
if($res && mysqli_num_rows($res) == 1){
    $LatestId = mysqli_fetch_assoc($res)['Max(Id)'];
}
?>

<form style="margin:0 20% 0 20%;">

    <h2 style="text-align:center; margin-top:10%;">Add new employee</h2>

    <fieldset class="border p-2 form-group">
        
        <legend class="w-auto">Office Details</legend>

        <div class="row">

            <div class="col-sm-4">
                <label for="">Code:</label>
                <input class="form-control" type="text" name="code" value="<?='EMP - '.$LatestId?>">
            </div>

            <div class="col-sm-4">
                <label for="">Department:</label>
                <select name="dep" class="form-control" required>
                    <option selected>Technology</option>
                    <option value="">HR</option>
                    <option value="">Engineering</option>
                    <option value="">R & D</option>
                </select>
            </div>

            <div class="col-sm-4">
                <label for="">Designation:</label>
                <input class="form-control" type="text" name="designation" autofocus>
            </div>

        </div>

    </fieldset>

    <fieldset class="border p-2 form-group">
        
        <legend class="w-auto">Personal Details</legend>

        <div class="row form-group">
        
            <div class="col-sm-7">
                <label for="">Full Name:</label>
                <input class="form-control" type="text" name="full_name" id="full-name" required>
                <small id="name-error" style="display:none" class="form-text text-danger">Name can only contain alphabets!</small>
            </div>

            <div class="col-sm-5 text-nowrap">
                <label for="">CNIC:</label>
                <input class="form-control" type="text" name="cnic" maxlength="13" required>
                <small id="cnic-error" style="display:none" class="form-text text-danger">Length should be 13 digits!</small>
            </div>

        </div>

        <div class="row form-group">

            <div class="col-sm-7">
                <label for="">Mobile Number:</label>
                <input class="form-control" type="text" name="mob_number" maxlength="12" required>
                <small id="phone-error" style="display:none" class="form-text text-danger">Length should be 11 digits!</small>
            </div>

            <div class="col-sm-5">
                <label for="">City:</label>
                <input class="form-control" type="text" name="city">
            </div>

        </div>

        <div class="form-group">
            <label for="">Address:</label>
            <input class="form-control" type="text" name="address">
        </div>

        <div class="form-group">
            <label for="">Bank Account Number (IBAN):</label>
            <input class="form-control" type="text" maxlength="14" name="bank_acc">
        </div>

    </fieldset>

    <div class="alert alert-primary" id="msg" style="display:none"></div>
    
    <input type="submit" class="btn btn-success">

</form>

<script>
    $('document').ready(function(){

        var error = false;

        var cnicRegex = new RegExp('[0-9]{13}'); 
        var phoneRegex = new RegExp('[0-9]{11}');
        var nameRegex = new RegExp('^[a-zA-Z ]{1,}$');
        var numRegex = new RegExp('^[0-9]{1,}$');

        $('form').on('submit', function(e) {
            
            e.preventDefault();

            if(!cnicRegex.test($('input[name=cnic]'))){
                $("#cnic-error").show();
                error = true;
            }
            else
                $("#cnic-error").hide();

            if(!phoneRegex.test($('input[name=mob_number]'))){
                $("#phone-error").show();
                error = true;
            }
            else 
                $("#phone-error").show();

            if(error)
                return;
                
            $.ajax({
                type: 'post',
                url: 'AJAX/add_employee.php',
                data: $('form').serialize(),

                success: function (data) {
                    
                    if (data == 1){
                        $("#msg").html('Successfully added employee to database');
                        $('form').trigger("reset");
                        $("#msg").fadeTo(1000, 500).slideUp(500, function(){
                            $("#msg").slideUp(500);
                        });
                    }
                    else {
                        $("#msg").html(data);
                        $("#msg").show();
                    }
                },
                error: function (data) {
                    $("#msg").html("failed to connect to server");
                }
            }); 

        });
        
        $('#full-name').on('keyup',function(){
            if($(this).val().length >= 5){
                if(!nameRegex.test($(this).val()))
                    $("#name-error").show();
                else
                    $("#name-error").hide(); 
            }   
        });

        $('input[name=cnic]').on('keyup',function(){
            if($(this).val().length >= 1){
                if(!numRegex.test($(this).val()))
                    $("#cnic-error").show();
                else
                    $("#cnic-error").hide();
            }
        });

        $('input[name=mob_number]').on('keyup',function(){
            if($(this).val().length >= 1){
                if(!numRegex.test($(this).val()))
                    $("#phone-error").show();
                else
                    $("#phone-error").hide(); 
            }
        });
        
    });
</script>

<?php } else {
    include('error.php');
}?>

</body>
</html>