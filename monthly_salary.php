<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Monthly Salaries | Payroll System</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.17/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.6/css/select.dataTables.min.css">
    
    
</head>

<body>

    <?php include('navbar.php') ?>
    
    <table id="example" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th></th>
                <th>Full Name</th>
                <th>Bank Account</th>
                <th>Department</th>
                <th>Basic Salary</th>
                <th>Total Compensation</th>
                <th>Total Deductions</th>
                <th>Net Salary</th>
            </tr>
        </thead>
    </table>

    <form action="payslips_pdf.php" id="generate-pay-slips" method="POST">
        <input type="text" name="selected-records" id="selected-records" hidden>
    </form>

    <script src="assets/jquery-3.3.1.js"></script>
    <script src="assets/popper.min.js"></script>
    <script src="assets/bootstrap.min.js"></script>

    <script src="assets/jquery.dataTables.min.js"></script>
    
    <script src="assets/dataTables.select.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
    

    <script>

    var table;

    $(document).ready(function () {

        table = $('#example').DataTable({
            dom: 'Bfrtip',
            ajax: {
                "url": "AJAX/calculate_pay_all.php",
                "type": "POST",
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
                    data: "Employee.FullName",
                    defaultContent: '',
                },
                { 
                    data: "Employee.BankAccount",
                    defaultContent: 'Not set',
                },
                { 
                    data: "Employee.Department",
                    defaultContent: 'Not Set',
                },
                { 
                    data: "Compensations.BasicSalary", render: $.fn.dataTable.render.number(',', '.', 0, 'PKR '),
                    defaultContent: '',
                },
                { 
                    data: "TotalCompensationsAmount", render: $.fn.dataTable.render.number(',', '.', 0, 'PKR '),
                    defaultContent: '',
                },
                { 
                    data: "TotalDeductionsAmount",
                    defaultContent: '',
                },
                { 
                    data: "NetPay",
                    defaultContent: '', render: $.fn.dataTable.render.number(',', '.', 0, 'PKR ')
                }
                ],

            order: [2, 'asc'],

            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print',
                {
                    text: 'Generate Selected Payslip',
                    action: function ( e, dt, node, config ) {

                        var selectedRecords = [];

                        $.each($('.selected'), function(row) {
                            selectedRecords.push(table.row(row).data());
                        });

                        if(selectedRecords.length == 0)
                        return alert('Select some records first');


                        $("#selected-records").val(JSON.stringify(selectedRecords));

                        $("#generate-pay-slips").submit();

                    }
                }
            ],

            select: {
                style: 'os',
                selector: 'td:first-child'
            },

        });


    });

    </script>

</body>

</html>
                
