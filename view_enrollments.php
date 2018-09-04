<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Enrollments | Payroll System</title>
    <?php include('datatables-styles.php') ?>

</head>

<body>

    <?php include('navbar.php') ?>

    <div id="table-wrapper" class="table-responsive mt-3">
        <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th></th>
                    <th>Full Name</th>
                    <th>Code</th>
                    <th>Mobile Number</th>
                    <th>Designation</th>
                    <th>Department</th>
                    <th>CNIC</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>BankAccount</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <?php include('datatables-scripts.php'); ?>

    <script>

    var editor;
    var table;

    $(document).ready(function () {

        editor = new $.fn.dataTable.Editor({

            idSrc:  'Id',

            table: "#example",

            fields: [{
                label: "Full Name:",
                name: "FullName"
            }, {
                label: "Employee Code:",
                name: "Code"
            }, {
                label: "MobileNumber:",
                name: "MobileNumber"
            }, {
                label: "Designation:",
                name: "Designation"
            },{
                label: "Department:",
                name: "Department"
            }, {
                label: "CNIC:",
                name: "CNIC"
            }, {
                label: "Address:",
                name: "Address",
            },{
                label: "City:",
                name: "City",
            }, {
                label: "Bank Account:",
                name: "BankAccount",
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

        table = $('#example').DataTable({
            
            dom: "Bfrtip",

            ajax: {
                "url": "AJAX/get_enrollments.php",
                "type": "GET",

                "dataSrc": function (d) {
                    return d;
                }
            },

            responsive: true,

            columns: [
                {
                    data: null,
                    defaultContent: '',
                    className: 'select-checkbox',
                    orderable: false
                },
                { 
                    data: "FullName",
                    defaultContent: '<i>Not set</i>'
                },
                { 
                    data: "Code",
                    defaultContent: '<i>Not set</i>'
                },
                { 
                    data: "MobileNumber",
                    defaultContent: '<i>Not set</i>',
                },
                {
                    data: "Designation",
                    defaultContent: '<i>Not set</i>'
                },
                {
                    data: "Department",
                    defaultContent: '<i>Not set</i>'
                },
                { 
                    data: "CNIC",
                    defaultContent: '<i>Not set</i>'
                },
                { 
                    data: "Address",
                    defaultContent: '<i>Not set</i>'
                },
                {
                    data: "City",
                    defaultContent: '<i>Not set</i>'
                },
                {
                    data: 'BankAccount',
                    defaultContent: '<i>Not set</i>'      
                }],

            order: [1, 'asc'],

            select: {
                style: 'os',
                selector: 'td:first-child'
            },

            buttons: [
                'selectAll','selectNone',
                {
                    extend: 'csv',
                    text: 'CSV',
                    exportOptions: {
                        columns: ':not(:first-child)'
                    }
                }, 'excel', 'pdf',
                { extend: "remove", editor: editor }
            ],

            keys: {
                editor: editor,
                columns: ':not(:first-child)',
                editOnFocus: true
            },
        });
  
        table.on('key-focus', function (e, datatable, cell) {   //tab key listener
            cell.edit();
        });

    });

    </script>

</body>

</html>
                