<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Monthly Salaries | Payroll System</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.17/css/jquery.dataTables.min.css">
    
</head>

<body>

    <?php include('navbar.php') ?>
    
    <table id="example" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
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

    <script src="assets/jquery-3.3.1.js"></script>
    <script src="assets/popper.min.js"></script>
    <script src="assets/bootstrap.min.js"></script>

    <script src="assets/jquery.dataTables.min.js"></script>

    <script>

    var editor; // use a global for the submit and return data rendering in the examples
    var table;

    $(document).ready(function () {

        table = $('#example').DataTable({

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

            order: [0, 'asc'],

        });


    });

    </script>

</body>

</html>
                
