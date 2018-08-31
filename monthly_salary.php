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

    <link href="assets/bootstrap-datepicker.standalone.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    
    <style>
    .calendar-icon{
        background-color:#E9ECEF;
        border: 1px solid #ced4da;
        border-radius: 0 .25rem .25rem 0;
    }
    </style>

</head>

<body>

    <?php include('navbar.php') ?>

    <form style="margin:0 20% 2% 20%;" id="month-year-form">
        <h2 style="text-align:center; margin-top:10%;">View Salary Data for month</h2>

        <div class="input-group">
            <input style="height:35px;" type="text" id="month_year" class="form-control" name="month_year" autocomplete="disabled" readonly required>
            <label style="height:35px;" class="input-group-addon btn calendar-icon" for="month_year">
                <span class="fa fa-calendar open-datetimepicker"></span>
            </label>
        </div>

        <div class="text-center">
            <button class="btn btn-primary">Get Salary Data</button>
        </div>
        
    </form>
    
    <div id="table-wrapper" style="display:none">
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
    </div>

    <form action="payslips_pdf.php" id="generate-pay-slips" method="POST" target="_blank">
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

    <script src="assets/bootstrap-datepicker.js" type="text/javascript"></script>

    <script>

    var table;

    $(document).ready(function () {

        $('#test').hide();

        var currentMonthYear = (new Date().getMonth()+1) + '-' + (new Date().getFullYear());

        $('#month_year').datepicker({
            format: "mm-yyyy",
            viewMode: "months", 
            minViewMode: "months",
            autoclose: true,
            defaultDate: currentMonthYear
        });

        $("open-datetimepicker").click(function(e){
            $('#month_year').click();
        });

        $("#month-year-form").on('submit', function(e){

            e.preventDefault();

            if($('#month_year').val() != ""){
            
                if($.fn.DataTable.isDataTable('#example')) 
                table.destroy();

                intializeTable();
                $('#table-wrapper').show();

            }

        });

        function intializeTable(){

            table = $('#example').DataTable({
                dom: 'Bfrtip',
                ajax: {
                    "url": `AJAX/calculate_pay_all.php`,
                    "type": "POST",
                    "data": {"month_year": $("#month_year").val()},
                    
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
                        data: "TotalDeductionsAmount", render: $.fn.dataTable.render.number(',', '.', 0, 'PKR '),
                        defaultContent: '',
                    },
                    { 
                        data: "NetPay",
                        defaultContent: '', render: $.fn.dataTable.render.number(',', '.', 0, 'PKR ')
                    }
                    ],

                order: [1, 'asc'],

                buttons: [
                    'selectAll','selectNone','csv', 'excel', 'pdf', 'print', 
                    {
                        text: 'Generate Selected Payslip(s)',
                        action: function ( e, dt, node, config ) {

                            var selectedRecords = [];

                            $.each($('.selected'), function(row) {
                                selectedRecords.push(table.row(row).data());
                            });

                            if(selectedRecords.length == 0)
                            return alert('Select some records first');

                            console.log(selectedRecords)

                            $("#selected-records").val(JSON.stringify(selectedRecords));

                            $("#generate-pay-slips").submit();

                        },
                        className: 'generatePaySlip',
                        enabled: false
                    }
                ],

                select: {
                    style: 'os',
                    selector: 'td:first-child'
                },

            });

            table.on('select deselect', function () {
                var selectedRows = table.rows( { selected: true } ).count();
                table.button('.generatePaySlip').enable(selectedRows > 0);  //enable button if selectedrows > 0
            });
        }
        
    });

    </script>

</body>

</html>
                
