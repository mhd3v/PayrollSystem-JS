<?php

include('connection.php');

include('header.php');

?>

<form class="m-5 p-5">

    <h2 style="text-align:center">Add/View Loans for Employee</h2>

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
    

    <div class="loan-area" style="display: none;">

        <fieldset class="border p-2">

            <legend class="w-auto">Selected Employee Loans</legend>

            <div class="form-group">
                <label for="">Total Amount:</label>
                <input class="form-control" type="text" name="total_amt" id="total_amt" required/>

                <label for="">Total Installments:</label>
                <input class="form-control" type="text" name="total_installments" id="total_installments" required/>

                <label for="">Installment Amount (Per month):</label>

                <input class="form-control" type="text" id="installment_amt" readonly/>

                <label for="">Loan Start Date:</label>
                <input class="form-control" type="date" name="start_date" id="start_date" required/>

                <label for="">Loan End Date:</label>
                <input class="form-control" type="date" name="end_date" id="end_date" required/>
                
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
                    url: `AJAX/get_loan.php?employee_id=${selectedEmployeeId}`,

                    success: function (data) {

                        if (data) {

                            var loanData = JSON.parse(data);

                            console.log(loanData);
                            
                            $('#total_amt').val(loanData.TotalAmount);
                            $('#total_installments').val(loanData.TotalInstallments);
                            $('#installment_amt').val(loanData.InstallmentAmount);
                            $('#start_date').val(loanData.StartDate);
                            $('#end_date').val(loanData.EndDate);

                        }
                        else {  //loan record doesn't exist
                            $('.loan-area input').val('');
                        }

                        $('.loan-area').fadeIn("slow");
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
                url: 'AJAX/add_loan.php',
                data: $('form').serialize() + `&employee_id=${selectedEmployeeId}`,

                success: function (data) {

                    if (data == 1) {
                        $("#msg").html('Successfully inserted loan data');
                        $("#msg").fadeIn("slow");
                        $('.selected-employee-area').fadeOut("slow");
                        $('.loan-area').fadeOut("slow");

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

    $('#total_amt').keyup(function(){
        if($('#total_amt').val() != '' && $('#total_installments').val() != '')
            $('#installment_amt').val(($('#total_amt').val() / $('#total_installments').val()).toFixed(2));
    });

    $('#total_installments').keyup(function(){
        if($('#total_amt').val() != '' && $('#total_installments').val() != '')
            $('#installment_amt').val(($('#total_amt').val() / $('#total_installments').val()).toFixed(2));
    });


    });

   

</script>

</body>

</html>