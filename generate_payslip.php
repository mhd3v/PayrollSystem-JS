<?php
$page_name = 'Generate Payslip';
include('header.php');
?>

</head>
<body>

<?php   
include('navbar.php');

if(session_status() == PHP_SESSION_NONE) 
session_start();
if(isset($_SESSION['user'])){ ?>

<form style="margin:0 20% 0 20%;" id="GeneratePaySlipForm">

    <h2 style="text-align:center; margin-top:10%;">Generate Payslip for Employee</h2>

    <div class="form-group">
        <label for="">Search Employee CNIC/Code:</label>
        <input class="form-control" type="text" id="employee_selection" autofocus>
    </div>  

    <div class="form-group selected-employee-area" style="display: none;">

        <fieldset class="border p-2">
        
            <legend class="w-auto">Selected Employee Details</legend>
        
            <div class="row form-group">
                <div class="col-sm-4">
                    <label for="">First Name:</label>
                    <input class="form-control" type="text" id="first_name" disabled/>
                </div>

                <div class="col-sm-4">
                    <label for="">Code:</label>
                    <input class="form-control" type="text" id="emp_code" disabled/>
                </div>
                
                <div class="col-sm-4">
                    <label for="">CNIC:</label>
                    <input class="form-control" type="text" id="emp_cnic" disabled/>
                </div>    
            </div>
        
        </fieldset>

    </div>

    <div class="form-group month-area" style="display: none;">

        <label for="">Enter Month and Year:</label>

        <div class="input-group">
            <input type="text" id="month_year" class="form-control" name="month_year" autocomplete="disabled" required>
            <label class="input-group-addon btn calendar-icon" for="month_year">
                <span class="fa fa-calendar open-datetimepicker"></span>
            </label>
        </div>

    </div>
    
    <div class="alert alert-primary" id="msg" style="display:none"></div>

    <input style="display:none" type="submit" class="btn btn-success submit-btn mt-2">

</form>

<form action="payslip.php" method="post" id="payform" target="_blank">
<input id="paydata" name="paydata" hidden>
</form>


<script>

    var selectedEmployeeId;
    var currentMonthYear = (new Date().getMonth()+1) + '/' + (new Date().getFullYear());

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
                $('.month-area').fadeIn("slow");
                $('.submit-btn').fadeIn("slow");
                $("#msg").fadeOut("slow");

                $("#month_year").datepicker( "setDate" , currentMonthYear);
            },

        }).data('ui-autocomplete')._renderItem = function (ul, item) {
             return $("<li>")
                .append('Name: ' + item.FullName + ',CNIC: ' + item.CNIC + ', Employee Code: ' + item.Code)
                .appendTo(ul);
        };

        //====================================== Autocomplete End ====================================================

        $('#GeneratePaySlipForm').on('submit', function(e) {

            e.preventDefault();

            if($("#month_year").val() == ""){
                return alert('Fill in month-year field');
            }

            var monthYearRegex = new RegExp('((0[1-9]{1})|(1[012]{1}))\-[1-9]{1}[0-9]{3}'); //regex for month-year format

            if(!(monthYearRegex.test($("#month_year").val())))
                return alert('Month year format not correct, use MM-YYYY, for example 01-2018');

            $.ajax({
                type: 'post',
                url: 'AJAX/calculate_pay_single.php',
                data: $('form').serialize() + `&employee_id=${selectedEmployeeId}`,

                success: function (data) {

                    data = JSON.parse(data);
                    
                    if(data.Status == 1){
                        $("#paydata").val(JSON.stringify(data));
                        $("#payform").submit();
                    }

                    else {
                        alert(data.Message);
                    }
                },
                error: function (data) {
                    $("#msg").html("Failed to connect to server");
                }
            }); 
        });

    });

</script>

<?php } else {
    include('error.php');
}?>

</body>
</html>