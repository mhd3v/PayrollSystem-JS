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

        <div class="input-group">
            <input type="text" id="month_year" class="form-control" name="month_year" autocomplete="disabled" readonly required>
            <label class="input-group-addon btn" for="month_year">
                <span class="fa fa-calendar open-datetimepicker"></span>
            </label>
        </div>

    </div>
    
    <div class="alert alert-primary" id="msg" style="display:none"></div>

    <input style="display:none" type="submit" class="btn btn-success submit-btn mt-2">

</form>

<script>

    var selectedEmployeeId;
    var currentMonthYear = (new Date().getMonth()+1) + '-' + (new Date().getFullYear());

    $('document').ready(function(){

        $('#month_year').datepicker({
            format: "mm-yyyy",
            viewMode: "months", 
            minViewMode: "months",
            autoclose: true,
            defaultDate: currentMonthYear
        });

        $("open-datetimepicker").click(function(e){
            $('#month_year').click();
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

            $.ajax({
                type: 'post',
                url: 'AJAX/calculate_pay.php',
                data: $('form').serialize() + `&employee_id=${selectedEmployeeId}`,

                success: function (data) {

                    data = JSON.parse(data);

                    if (data.Status == 1) {
                        console.log(data);
                    }

                    else {
                        alert(data.Message);
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