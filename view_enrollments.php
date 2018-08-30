<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Enrollments | Payroll System</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.17/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.6/css/select.dataTables.min.css">
    <link rel="stylesheet" href="https://editor.datatables.net/extensions/Editor/css/editor.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/keytable/2.4.1/css/keyTable.dataTables.min.css">
    
</head>

<body>

    <?php include('navbar.php') ?>
    
    <table id="example" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th></th>
                <th>Full Name</th>
                <th>Employee Code</th>
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

    <script src="assets/jquery-3.3.1.js"></script>
    <script src="assets/popper.min.js"></script>
    <script src="assets/bootstrap.min.js"></script>

    <script src="assets/jquery.dataTables.min.js"></script>
    <script src="assets/dataTables.buttons.min.js"></script>
    <script src="assets/dataTables.select.min.js"></script>
    <script src="assets/dataTables.keyTable.min.js"></script>
    <script src="assets/dataTables.editor.js"></script>

    <script>

    var editor; // use a global for the submit and return data rendering in the examples
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

                //console.log(d.data);

                var output = { data: [] };

                if (d.action === 'create') {
                    console.log('Create ajax call');
                }

                if (d.action === 'replace') {
                    console.log('Replace ajax call');
                }

                 if (d.action === 'remove') {
                    console.log('Remove ajax call');
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
                "error": function (e) {},

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
                