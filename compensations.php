<?php

include('connection.php');

include('header.php');

?>

<form class="m-5 p-5">

    <h2 style="text-align:center">Add/View Compensations for Employee</h2>

    <div class="form-group">
        <label for="">Enter Employee CNIC/Code:</label>
        <input class="form-control" type="text" id="employee_selection">
    </div>  

    <div class="selected-employee-area" style="display: none;">

        <fieldset class="border p-2">
        
            <legend class="w-auto">Selected Employee Details</legend>
        
            <div class="form-group">
                <label for="">First Name:</label>
                <input class="form-control" type="text" id="first_name" disabled/>
                <label for="">Employee Code:</label>
                <input class="form-control" type="text" id="emp_code" disabled/>
                <label for="">CNIC:</label>
                <input class="form-control" type="text" id="emp_cnic" disabled/>
            </div>
        
        </fieldset>

    </div>

    

    <div class="compensation-area" style="display: none;">

        <fieldset class="border p-2">

            <legend class="w-auto">Selected Employee Compensations</legend>

            <div class="form-group">
                <label for="">Basic Salary:</label>
                <input class="form-control" type="text" name="basic_sal" id="basic_sal" />

                <label for="">House Rent:</label>
                <input class="form-control" type="text" name="house_rent" id="house_rent" />

                <label for="">Fuel Allowance:</label>
                <input class="form-control" type="text" name="fuel_allowance" id="fuel_allowance" />

                <label for="">Utility Allowance:</label>
                <input class="form-control" type="text" name="utility_allowance" id="utility_allowance" />

                <label for="">Mobile Allowance:</label>
                <input class="form-control" type="text" name="mobile_allowance" id="mobile_allowance" />

                <label for="">Other Allowance:</label>
                <input class="form-control" type="text" name="other_allowance" id="other_allowance" />
                
            </div>

        </fieldset>
    </div>
    

    <div class="alert alert-primary" id="msg" style="display:none"></div>

    <input type="submit" class="btn btn-success">

</form>

<script>

    var selectedEmployeeId;

    $('document').ready(function(){

        $("#employee_selection").autocomplete({
            source: "AJAX/get_employees.php",
            minLength: 1,

            select: function (event, ui) {
                
                selectedEmployeeId = ui.item.Id;
                $('#first_name').val(ui.item.FullName);
                $('#emp_code').val(ui.item.Code);
                $('#emp_cnic').val(ui.item.CNIC);

                $('.selected-employee-area').fadeIn("slow");
                $("#msg").fadeOut("slow");

                $.ajax({
                    type: 'get',
                    url: `AJAX/get_compensation.php?employee_id=${selectedEmployeeId}`,

                    success: function (data) {

                        if (data) {

                            var compensationData = JSON.parse(data);

                            $('#basic_sal').val(compensationData.BasicSalary);
                            $('#house_rent').val(compensationData.HouseRent);
                            $('#fuel_allowance').val(compensationData.FuelAllowance);
                            $('#utility_allowance').val(compensationData.UtilityAllowance);
                            $('#mobile_allowance').val(compensationData.MobileAllowance);
                            $('#other_allowance').val(compensationData.OtherAllowance);

                        }
                        else {  //compensation record doesn't exist
                            $('.compensation-area input').val('');
                        }

                        $('.compensation-area').fadeIn("slow");
                    },
                    error: function (data) {
                        $("#msg").html("failed to connect to server");
                    }
                }); 

            },

        }).data('ui-autocomplete')._renderItem = function (ul, item) {
             return $("<li>")
                .append('Name: ' + item.FullName + ', CNIC: ' + item.CNIC + ', Employee Code: ' + item.Code)
                .appendTo(ul);
        };

        $('form').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                type: 'post',
                url: 'AJAX/add_compensations.php',
                data: $('form').serialize() + `&employee_id=${selectedEmployeeId}`,

                success: function (data) {

                    if (data == 1) {
                        $("#msg").html('Successfully inserted compensation data');
                        $("#msg").fadeIn("slow");
                        $('.selected-employee-area').fadeOut("slow");
                        $('.compensation-area').fadeOut("slow");

                    }
                    else {
                        $("#msg").html(data);
                        $("#msg").fadeIn("slow");
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