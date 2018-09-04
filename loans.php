<?php
$page_name = 'Loans';
include('header.php');
include('datatables-styles.php');
?>

</head>
<body>

<?php include('navbar.php') ?>

<form style="margin:0 20% 0 20%;">

    <h2 style="text-align:center; margin-top:10%;">Add/View Loans for Employee</h2>

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
    

    <div class="loan-area" style="display: none;">

        <fieldset class="border p-2">

            <legend class="w-auto">Selected Employee Loans</legend>

            <!-- <div class="row form-group">
                
                <div class="col-md-3">
                    <label for="">Total Amount:</label>
                    <input class="form-control" type="text" name="total_amt" id="total_amt" required/>
                </div>

                <div class="col-md-3">
                    <label for="">Total Installments:</label>
                    <input class="form-control" type="text" name="total_installments" id="total_installments" required/>
                </div>

                <div class="col-md-6">
                    <label for="">Installment Amount (Per month):</label>
                    <input class="form-control" type="text" id="installment_amt" readonly/>
                </div>
                
            </div>

            <div class="row form-group">

                <div class="col-sm-6">
                    <label for="">Loan Start Date:</label>
                    <input class="form-control" type="date" name="start_date" id="start_date" required/>
                </div>

                <div class="col-sm-6"> 
                    <label for="">Loan End Date:</label>
                    <input class="form-control" type="date" name="end_date" id="end_date" required/>
                </div>

            </div> -->

            <div id="table-wrapper" class="table-responsive" style="display:none">
                <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Loan Id</th>
                            <th>Total Amount</th>
                            <th>Total Installments</th>
                            <th>Installment Amount</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </fieldset>

    </div>
    
    <div class="alert alert-primary" id="msg" style="display:none"></div>

    <input style="display: none;" type="submit" class="btn btn-success submit-btn mt-2" value="Update Record">

</form>

<?php include('datatables-scripts.php'); ?>

<script>

    var selectedEmployeeId;
    var editor;
    var table;

    $(document).ready(function () {

        editor = new $.fn.dataTable.Editor({

            idSrc:  'Id',

            table: "#example",

            fields: [{
                label: "Total Amount:",
                name: "TotalAmount"
            }, {
                label: "Total Installments:",
                name: "TotalInstallments"
            }, {
                label: "Start Date:",
                name: "StartDate",
                type:  'datetime',
                def:   function () { return new Date(); }
            },{
                label: "End Date:",
                name: "EndDate",
                type:  'datetime'
            }],

            formOptions: {
                inline: {
                    onBlur: 'submit'
                }
            },

            ajax: function (method, url, d, successCallback, errorCallback) {

                console.log('coming in ajax function');

                var output = { data: [] };

                if (d.action === 'create') {
                    console.log('Create ajax call');
                }

                if (d.action === 'replace') {
                    console.log('Replace ajax call');
                }

                if (d.action === 'remove') {

                    var employeeRowsToDelete = [];

                    for(var i = 0; i < Object.keys(d.data).length; i++){
                        employeeRowsToDelete.push(Object.keys(d.data)[i]);
                    };
                    
                    $.ajax({
                        type: 'POST',
                        url: 'AJAX/delete_employees.php',
                        data: {'recordsToDelete' : employeeRowsToDelete},

                        success: function (data) {

                            if (data != 0) {
                                output.data.push(JSON.parse(data));
                                successCallback(output);
                            }
                            else {
                                alert('Failed to delete rows');
                            }

                        },
                        error: function (returnData) {
                            $("#msg").html("failed to connect to server");
                        }
                    }); 
                }

                if(d.action == 'edit'){

                    var eId = Object.keys(d.data)[0];
                    var propertyToChange = Object.keys(d.data[eId])[0];
                    var newVal = d.data[eId][propertyToChange];
                    
                    $.ajax({
                        type: 'POST',
                        url: 'AJAX/edit_employee_record.php',
                        data: { 
                            'Id': eId, 
                            'propertyToChange': propertyToChange,
                            'newVal': d.data[eId][propertyToChange]
                        },

                        success: function (returnData) {

                            if (returnData) {
                                output.data.push(JSON.parse(returnData));
                                successCallback(output);
                            }
                            else {
                               
                            }

                        },
                        error: function (returnData) {
                            $("#msg").html("failed to connect to server");
                        }
                    }); 

                }

            }

        });

        editor.on('preSubmit', function (e, o, action ) {

            if ( action !== 'remove' ) {

                var dateRegex = new RegExp("^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$");
                var dateError = false;

                var total_amt = this.field('TotalAmount');
                var total_installments = this.field('TotalInstallments');
                var start_date = this.field('StartDate');
                var end_date = this.field('EndDate');
              
                if (!total_amt.val()){
                    total_amt.error('Total Amount required');
                }

                if (!total_installments.val()){
                    total_installments.error('Total Installments required');
                }
                
                if (!start_date.val() || !(dateRegex.test(start_date.val()))){
                    start_date.error('Start date required with the format YYYY-MM-DD, example: 2018-01-01');
                    dateError = true;
                }

                if (!end_date.val() || !(dateRegex.test(end_date.val()))){
                    end_date.error('End date required with the format YYYY-MM-DD, example: 2018-01-01');
                    dateError = true;
                }

                if(!dateError){
                    var startDateUnix = (parseInt((new Date(start_date.val()).getTime() / 1000).toFixed(0)));
                    var startDateUnix = (parseInt((new Date(start_date.val()).getTime() / 1000).toFixed(0)));
                    end_date.error('e1');
                }

                if (this.inError()) {
                    return false;
                }
            }
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

                // $.ajax({
                //     type: 'get',
                //     url: `AJAX/get_loan.php?employee_id=${selectedEmployeeId}`,

                //     success: function (data) {

                //         if (data) { //loan data found

                //             var loanData = JSON.parse(data);
                //             console.log(loanData);
                            
                //             $('#total_amt').val(loanData.TotalAmount);
                //             $('#total_installments').val(loanData.TotalInstallments);
                //             $('#installment_amt').val(loanData.InstallmentAmount);
                //             $('#start_date').val(loanData.StartDate);
                //             $('#end_date').val(loanData.EndDate);

                //         }
                //         else {  //loan record doesn't exist
                //             $('.submit-btn').val('Add Record');
                //             $('.loan-area input').val('');
                //         }

                //         $('.loan-area').fadeIn("slow");
                //         $('.submit-btn').fadeIn("slow");
                //         $('#total_amt').focus();
                //     },
                //     error: function (data) {
                //         $("#msg").html("failed to connect to server");
                //     }
                // }); 

                if($.fn.DataTable.isDataTable('#example')) 
                    table.destroy();

                intializeTable();
                $('#table-wrapper').show();

                $('.loan-area').fadeIn("slow");

            },

        }).data('ui-autocomplete')._renderItem = function (ul, item) {
             return $('<li>')
                .append('Name: ' + item.FullName + ', CNIC: ' + item.CNIC + ', Employee Code: ' + item.Code)
                .appendTo(ul);
        };

        //====================================== Autocomplete End ====================================================

        // $('form').on('submit', function(e) {
        //     e.preventDefault();

        //     $.ajax({
        //         type: 'post',
        //         url: 'AJAX/add_loan.php',
        //         data: $('form').serialize() + `&employee_id=${selectedEmployeeId}`,

        //         success: function (data) {

        //             if (data == 1) {
        //                 $("#msg").html('Successfully inserted loan data');
        //                 $("#msg").fadeTo(1000, 500).slideUp(500, function(){
        //                     $("#msg").slideUp(500);
        //                 });
        //                 $('.selected-employee-area').fadeOut("slow");
        //                 $('.loan-area').fadeOut("slow");
        //                 $('.submit-btn').fadeOut("slow");
        //             }
        //             else {  //error while inserting in db
        //                 $("#msg").html(data);
        //                 $("#msg").fadeIn("slow");
        //             }
                    
        //         },
        //         error: function (data) {
        //             $("#msg").html("failed to connect to server");
        //         }
        //     }); 
        // });

        function intializeTable(){

            table = $('#example').DataTable({
                dom: 'Bfrtip',
                ajax: {
                    "url": "AJAX/get_loan.php",
                    "type": "POST",
                    "data" : {"employee_id": `${selectedEmployeeId}`},
                    
                    "error": function (e) {
                        console.log(e);
                    },

                    "dataSrc": function (d) {
                        return d;
                    }
                },

                responsive:true,

                columns: [
                    { 
                        data: "Id",
                    },
                    { 
                        data: "TotalAmount",
                        class: "editable"
                    },
                    { 
                        data: "TotalInstallments",
                        class: "editable"
                    },
                    { 
                        data: "InstallmentAmount",
                    },
                    { 
                        data: "StartDate",
                        class: "editable"
                    },
                    { 
                        data: "EndDate",
                        class: "editable"
                    }
                ],

                order: [0, 'asc'],

                buttons: [
                    { extend: "create", editor: editor },
                    { extend: "remove", editor: editor }
                ],

                keys: {
                    editor: editor,
                    columns: '.editable',
                    editOnFocus: true
                }
            });

            table.on('key-focus', function (e, datatable, cell) {   //tab key listener
                cell.edit();
            });

        }

        $('#DTE_Field_TotalAmount').keyup(function(){
            console.log('workds');
            if($('#DTE_Field_TotalAmount').val() != '' && $('#DTE_Field_TotalInstallments').val() != '')
                $('#DTE_Field_InstallmentAmount').val(($('#DTE_Field_TotalAmount').val() / $('#DTE_Field_TotalInstallments').val()).toFixed(2));
        });

        $('#DTE_Field_TotalInstallments').keyup(function(){
            if($('#DTE_Field_TotalAmount').val() != '' && $('#DTE_Field_TotalInstallments').val() != '')
                $('#DTE_Field_InstallmentAmount').val(($('#DTE_Field_TotalAmount').val() / $('#DTE_Field_TotalInstallments').val()).toFixed(2));
        });


    });

   

</script>

</body>

</html>