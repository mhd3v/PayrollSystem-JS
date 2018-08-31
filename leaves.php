<?php

$page_name = 'Leaves';
include('header.php');

?>

<form style="margin:0 20% 0 20%;">

    <h2 style="text-align:center; margin-top:10%;">Add/View Leaves for Employee</h2>

    <div class="form-group">
        <label for="">Search Employee CNIC/Code:</label>
        <input class="form-control" type="text" id="employee_selection" autofocus>
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

            <div class="row form-group">

                <div class="col-md-4">
                    <label for="">Total Annual Leaves:</label>
                    <input class="form-control" type="number" name="total_annual" id="total_annual"/>
                </div>

                <div class="col-md-4">
                    <label for="">Total Sick Leaves:</label>
                    <input class="form-control" type="number" name="total_sick" id="total_sick"/>
                </div>

                <div class="col-md-4">
                    <label for="">Total Casual Leaves:</label>
                    <input class="form-control" type="number" name="total_casual" id="total_casual"/>
                </div>

            </div>

            <div class="row form-group">
                
                <div class="col-md-4">
                    <label for="">Annual leaves availed:</label>
                    <input class="form-control" type="number" name="annual_availed" id="annual_availed"/>
                </div>

                <div class="col-md-4">
                    <label for="">Sick leaves availed:</label>
                    <input class="form-control" type="number" name="sick_availed" id="sick_availed"/>
                </div>

                <div class="col-md-4">
                    <label for="">Casual leaves availed:</label>
                    <input class="form-control" type="number" name="casual_availed" id="casual_availed"/>
                </div>

            </div>

            <hr>

            <h4>Leaves without pay</h4>

            <div class="row form-group">

                <div class="col-md-6">
                    <label for="">Total:</label>
                    <input class="form-control" type="number" name="without_pay" id="without_pay"/>
                </div>

                <div class="col-md-6">

                    <label for="">Select Month and Year:</label>

                    <div class="input-group">
                        <input type="text" id="month_year" class="form-control" name="month_year" autocomplete="disabled" readonly>
                        <label class="input-group-addon btn calendar-icon" for="month_year">
                            <span class="fa fa-calendar open-datetimepicker"></span>
                        </label>
                    </div>

                </div>
         
            </div>

        </fieldset>

    </div>
    
    <div class="alert alert-primary" id="msg" style="display:none"></div>

    <input style="display:none" type="submit" class="btn btn-success submit-btn mt-2">

</form>

<script>

    var selectedEmployeeId;

    $('document').ready(function(){

        $('#month_year').datepicker({
            format: "mm-yyyy",
            viewMode: "months", 
            minViewMode: "months",
            autoclose: true,
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
                $("#msg").fadeOut("slow");

                $.ajax({
                    type: 'get',
                    url: `AJAX/get_leaves.php?employee_id=${selectedEmployeeId}`,

                    success: function (data) {

                        if (data) { //loan data found

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
                        $('.submit-btn').fadeIn("slow");
                        $('#total_annual').focus();
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

            if($("#without_pay").val() == "" || $("#month_year").val() == ""){
                return alert("Leaves without pay data must have both Total and Month Year!");
            }

            else{
    
                var monthYearRegex = new RegExp('((0[1-9]{1})|(1[012]{1}))\-[1-9]{1}[0-9]{3}'); //regex for month-year format

                if(!(monthYearRegex.test($("#month_year").val())))
                    return alert('Month year format not correct, use MM-YYYY, for example 01-2018');

                else{

                    $.ajax({
                        type: 'post',
                        url: 'AJAX/add_leave.php',
                        data: $('form').serialize() + `&employee_id=${selectedEmployeeId}`,

                        success: function (data) {

                            if (data == 1) {
                                $("#msg").html('Successfully inserted leave data');
                                $("#msg").fadeTo(1000, 500).slideUp(500, function(){
                                    $("#msg").slideUp(500);
                                });
                                $('.selected-employee-area').fadeOut("slow");
                                $('.leave-area').fadeOut("slow");
                                $('.submit-btn').fadeOut("slow");
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

                }

            }
            
            
        });

    });

   

</script>

</body>

</html>