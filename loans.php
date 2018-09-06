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

            <div id="table-wrapper" class="table-responsive" style="display:none">
                <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th></th>
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

            idSrc: 'Id',

            table: "#example",

            fields: [{
                label: "Total Amount:",
                name: "TotalAmount",
                attr: {
                    type: "number"
                }
            }, {
                label: "Total Installments:",
                name: "TotalInstallments",
                attr: {
                    type: "number"
                }
            }, {
                label: "Start Date:",
                name: "StartDate",
                type:  'datetime',
                def:   function () { return new Date(); }
            },{
                label: "End Date:",
                name: "EndDate",
                type:  'datetime',
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

                    $.ajax({
                        type: 'POST',
                        url: 'AJAX/add_loan.php',
                        data: { 
                            'employee_id': selectedEmployeeId, 
                            'total_amt': d.data[0].TotalAmount,
                            'total_installments': d.data[0].TotalInstallments,
                            'start_date': d.data[0].StartDate,
                            'end_date': d.data[0].EndDate,
                        },

                        success: function (returnData) {

                            returnData = JSON.parse(returnData);

                            if (!returnData.error) {
                                console.log(returnData);
                                output.data.push(returnData);
                                successCallback(output);
                            }
                            else {
                                successCallback(returnData);
                            }

                        },
                        error: function (xhr, error, thrown) {
                            errorCallback( xhr, error, thrown );
                        }
                    }); 

                }

                if (d.action === 'replace') {
                    console.log('Replace ajax call');
                }

                if (d.action === 'remove') {

                    var selectedLoanRecords = [];

                    for(var i = 0; i < Object.keys(d.data).length; i++){
                        selectedLoanRecords.push(Object.keys(d.data)[i]);
                    };
                    
                    $.ajax({
                        type: 'POST',
                        url: 'AJAX/delete_loans.php',
                        data: {'loansToDelete' : selectedLoanRecords},

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

                    var loanId = Object.keys(d.data)[0];
                    var propertyToChange = Object.keys(d.data[loanId])[0];
                    var newVal = d.data[loanId][propertyToChange];
                    
                    $.ajax({
                        type: 'POST',
                        url: 'AJAX/edit_loan_record.php',
                        data: { 
                            'Id': loanId, 
                            'employeeId': selectedEmployeeId,
                            'propertyToChange': propertyToChange,
                            'newVal': newVal
                        },

                        success: function (returnData) {

                            returnData = JSON.parse(returnData);

                            if (!returnData.error) {
                                console.log(returnData);
                                output.data.push(returnData);
                                successCallback(output);
                            }
                            else {
                                successCallback(returnData);
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
                    var endDateUnix = (parseInt((new Date(end_date.val()).getTime() / 1000).toFixed(0)));
                    
                    if(startDateUnix > endDateUnix){
                        start_date.error('Start date needs to be before the ending date');
                    }

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

                columns: [
                    {
                        data: null,
                        defaultContent: '',
                        className: 'select-checkbox',
                        orderable: false
                    },
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

                order: [1, 'asc'],

                buttons: [
                    { extend: "create", editor: editor },
                    { extend: "remove", editor: editor }
                ],

                keys: {
                    editor: editor,
                    columns: '.editable',
                    editOnFocus: true
                },

                select: {
                    style: 'os',
                    selector: 'td:first-child'
                }
            });

            table.on('key-focus', function (e, datatable, cell) {   //tab key listener
                cell.edit();
            });

        }

    });

</script>

</body>

</html>