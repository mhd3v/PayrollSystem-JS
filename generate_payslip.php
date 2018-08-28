<?php
$page_name = 'Generate Payslip';
include('header.php');
?>

<form style="margin:0 20% 0 20%;">

    <h2 style="text-align:center; margin-top:10%;">Generate Payslip for Employee</h2>

    <div class="form-group">
        <label for="">Search Employee CNIC/Code:</label>
        <input class="form-control" type="text" id="employee_selection" autofocus>
    </div>  

    <div class="form-group selected-employee-area" style="display: none;">

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

    <div class="form-group month-area" style="display: none;">
        <label for="">Enter Month and Year:</label>
        <input id="datepicker" disabled required/>
        <small class="form-text text-muted">Select any day of the desired month using the icon</small>
    </div>
    
    <div class="alert alert-primary" id="msg" style="display:none"></div>

    <input style="display:none" type="submit" class="btn btn-success submit-btn mt-2">

</form>

<script>

    var selectedEmployeeId;
    var currentMonthYear = (new Date().getMonth()+1) + '-' + (new Date().getFullYear());
    console.log(currentMonthYear)

    $('document').ready(function(){

        $('#datepicker').datepicker({
            uiLibrary: 'bootstrap4',
            format: "mm-yyyy",
            startView: "months", 
            minViewMode: "months",
            value: currentMonthYear
        });

        $("#employee_selection").autocomplete({
            source: "AJAX/get_employees.php",
            minLength: 1,

            select: function (event, ui) {
                
                selectedEmployeeId = ui.item.Id;
                $('#first_name').val(ui.item.FullName);
                $('#emp_code').val(ui.item.Code);
                $('#emp_cnic').val(ui.item.CNIC);

                $('.selected-employee-area').fadeIn("slow");
                $('.month-area').fadeIn("slow");
                $('.submit-btn').fadeIn("slow");
                $("#msg").fadeOut("slow");

            },

        }).data('ui-autocomplete')._renderItem = function (ul, item) {
             return $("<li>")
                .append('Name: ' + item.FullName + ', CNIC: ' + item.CNIC + ', Employee Code: ' + item.Code)
                .appendTo(ul);
        };

        $('form').on('submit', function(e) {

            e.preventDefault();
            var monthYear = $('#datepicker').val();

            $.ajax({
                type: 'post',
                url: 'AJAX/calculate_pay.php',
                data: $('form').serialize() + `&employee_id=${selectedEmployeeId}&month_year=${monthYear}`,

                success: function (data) {

                    if (data) {
                        // $("#msg").html('Successfully inserted leave data');
                        // $("#msg").fadeIn("slow");
                        // $('.selected-employee-area').fadeOut("slow");
                        // $('.leave-area').fadeOut("slow");
                        // $('.submit-btn').fadeOut("slow");

                        console.log(data);
                    }
                    else {
                        // $("#msg").html(data);
                        // $("#msg").fadeIn("slow");
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