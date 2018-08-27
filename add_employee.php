<?php
include('connection.php');
include('header.php');
?>

<form class="m-5 p-5">

    <h2 style="text-align:center">Add new employee</h2>

    <div class="form-group">
        <label for="">Code:</label>
        <input class="form-control" type="text" name="code">
    </div>

    <div class="form-group">
        <label for="">Full Name:</label>
        <input class="form-control" type="text" name="full_name">
    </div>

    <div class="form-group">
        <label for="">City:</label>
        <input class="form-control" type="text" name="city">
    </div>

    <div class="form-group">
        <label for="">Designation:</label>
        <input class="form-control" type="text" name="designation">
    </div>

    <div class="form-group">
        <label for="">Mobile Number:</label>
        <input class="form-control" type="number" name="mob_number">
    </div>

    <div class="form-group">
        <label for="">CNIC:</label>
        <input class="form-control" type="number" name="cnic">
    </div>

    <div class="form-group">
        <label for="">Address:</label>
        <input class="form-control" type="text" name="address">
    </div>

    <div class="form-group">
        <label for="">Department:</label>
        <input class="form-control" type="text" name="dep">
    </div>

    <div class="form-group">
        <label for="">Bank Acoount Number:</label>
        <input class="form-control" type="text" name="bank_acc">
    </div>

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
                        $("#msg").show();
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