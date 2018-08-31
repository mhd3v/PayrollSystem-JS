<?php
$page_name = 'Add Employee';
include('header.php');
?>

<form style="margin:0 20% 0 20%;">

    <h2 style="text-align:center; margin-top:10%;">Add new employee</h2>

    <fieldset class="border p-2 form-group">
        
        <legend class="w-auto">Office Details</legend>

        <div class="row">

            <div class="col-sm-4">
                <label for="">Code:</label>
                <input class="form-control" type="text" name="code" autofocus>
            </div>

            <div class="col-sm-4">
                <label for="">Department:</label>
                <input class="form-control" type="text" name="dep">
            </div>

            <div class="col-sm-4">
                <label for="">Designation:</label>
                <input class="form-control" type="text" name="designation">
            </div>

        </div>

    </fieldset>

    <fieldset class="border p-2 form-group">
        
        <legend class="w-auto">Personal Details</legend>

        <div class="row form-group">
        
            <div class="col-sm-7">
                <label for="">Full Name:</label>
                <input class="form-control" type="text" name="full_name">
            </div>

            <div class="col-sm-5 text-nowrap">
                <label for="">CNIC:</label>
                <input class="form-control" type="number" name="cnic">
            </div>

        </div>

        <div class="row form-group">

            <div class="col-sm-7">
                <label for="">Mobile Number:</label>
                <input class="form-control" type="number" name="mob_number">
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
            <label for="">Bank Account Number:</label>
            <input class="form-control" type="text" name="bank_acc">
        </div>

    </fieldset>

    <div class="alert alert-primary" id="msg" style="display:none"></div>
    
    <input type="submit" class="btn btn-success">

</form>

<script>
    $('document').ready(function(){

        $('form').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                type: 'post',
                url: 'AJAX/add_employee.php',
                data: $('form').serialize(),

                success: function (data) {
                    
                    if (data == 1){
                        $("#msg").html('Successfully added employee to database');
                        $('form').trigger("reset");
                        $("#msg").fadeTo(2000, 500).slideUp(500, function(){
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

    });
</script>

</body>
</html>