<?php

include('connection.php');

include('header.php');

?>

<form class="m-5 p-5">

    <h2 style="text-align:center">Add/View Leaves for Employee</h2>

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
    

    <div class="leave-area" style="display: none;">

        <fieldset class="border p-2">

            <legend class="w-auto">Selected Employee Leaves</legend>

            <div class="form-group">
                <label for="">Total Annual Leaves:</label>
                <input class="form-control" type="number" name="total_annual" id="total_annual"/>

                <label for="">Total Sick Leaves:</label>
                <input class="form-control" type="number" name="total_sick" id="total_sick"/>

                <label for="">Total Casual Leaves:</label>
                <input class="form-control" type="number" name="total_casual" id="total_casual"/>

                <label for="">Annual leaves availed:</label>
                <input class="form-control" type="number" name="annual_availed" id="annual_availed"/>

                <label for="">Sick leaves availed:</label>
                <input class="form-control" type="number" name="sick_availed" id="sick_availed"/>

                <label for="">Casual leaves availed:</label>
                <input class="form-control" type="number" name="casual_availed" id="casual_availed"/>

                <label for="">Leaves without pay:</label>
                <input class="form-control" type="number" name="without_pay" id="without_pay"/>

                <label for="">Month/Year for leaves without pay:</label>
                <input class="form-control" type="date" name="month_year" id="month_year" required/>

                
            </div>

        </fieldset>

    </div>
    

    <div class="alert alert-primary" id="msg" style="display:none"></div>

    <input type="submit" class="btn btn-success mt-2">

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
                    url: `AJAX/get_leaves.php?employee_id=${selectedEmployeeId}`,

                    success: function (data) {

                        if (data) {

                            var leaveData = JSON.parse(data);

                            console.log(leaveData);
                            
                            $('#total_annual').val(leaveData.TotalAnnualLeaves);
                            $('#total_sick').val(leaveData.TotalSickLeaves);
                            $('#total_casual').val(leaveData.TotalCasualLeaves);
                            $('#annual_availed').val(leaveData.AnnualLeavesAvailed);
                            $('#casual_availed').val(leaveData.CasualLeavesAvailed);
                            $('#sick_availed').val(leaveData.SickLeavesAvailed);
                            $('#without_pay').val(leaveData.LeavesWithoutPay);
                            $('#month_year').val(leaveData.MonthYear);

                        }
                        else {  //loan record doesn't exist
                            $('.leave-area input').val('');
                        }

                        $('.leave-area').fadeIn("slow");
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
                url: 'AJAX/add_leave.php',
                data: $('form').serialize() + `&employee_id=${selectedEmployeeId}`,

                success: function (data) {

                    if (data == 1) {
                        $("#msg").html('Successfully inserted leave data');
                        $("#msg").fadeIn("slow");
                        $('.selected-employee-area').fadeOut("slow");
                        $('.leave-area').fadeOut("slow");

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