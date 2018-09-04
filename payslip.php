<?php 

if (isset($_POST['paydata'])){
$pay_data = json_decode($_POST['paydata'], true); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payslip | <?=$pay_data['Employee']['FullName']?></title>
    <style>
    body{
        font-family: Segoe UI,Arial,sans-serif; 
        padding: 0 5% 5% 5%;
    }
    table {
        table-layout: fixed;
        border-collapse: collapse;
    }
    
    thead{
        text-align:left;
        margin-top:20px;
    }
    
    .border{
        border-top:3px solid black; 
        border-bottom:3px solid black;
    }

    img{
        max-width: 9%;
        max-height:20%;
        width:auto;
        height:auto;
    }

    ul li {
        display: inline;
    }

    @media print{
        .options{
            display:none;
        }
        body{
            padding-top: 5%;
        }
    }

    </style>
    
</head>
<body>
    
    <div style="float:right;" class="options">
        <ul>
            <li><button onclick="window.print()">Print</button></li>
            <li><button id="generate-pdf">Save as PDF</button></li>
        </ul>
    </div>

    <form action="payslips_pdf.php" method="POST" target="_blank">
        <input type="text" name="selected-records" id="selected-records" hidden>
        <input type="text" name="DownloadPdf" hidden>
    </form>

    <div style="clear:both"></div>
    
    <img src="assets/jazz-logo.jpg" style="position:absolute; z-index:-1;"/>
    <h2 style="text-align:center;">Jazz</h2>
    <h5 style="text-align:center;">7 Park Rd, F-8 Markaz, Islamabad, Islamabad Capital Territory</h5>
    <br>
    <h4 style="text-align:center;">Payslip for: <?=$pay_data['MonthYear']?></h4>

    <table style="width:100%">

        <tr>
            <td>Employee Code</td>
            <td>:<?=$pay_data['Employee']['Code']?></td>
            <td>Full Name</td>
            <td>:<?=$pay_data['Employee']['FullName']?></td>
        </tr>
        <tr>
            <td>Mobile Number</td>
            <td>:<?=$pay_data['Employee']['MobileNumber']?></td>
            <td>Designation</td>
            <td>:<?=$pay_data['Employee']['Designation']?></td>
        </tr>
        <tr>
            <td>CNIC</td>
            <td>:<?=$pay_data['Employee']['CNIC']?></td>
            <td>Department</td>
            <td>:<?=$pay_data['Employee']['Department']?></td>
        </tr>
        <tr>
            <td>City</td>
            <td>:<?=$pay_data['Employee']['City']?></td>
            <td>Address</td>
            <td>:<?=$pay_data['Employee']['Address']?></td>
        </tr>
        <tr>
            <td>Bank Account</td>
            <td>:<?=$pay_data['Employee']['BankAccount']?></td>
        </tr>
        
    </table>

    <table style="width:50%; margin-top:20px; float:left ">

        <thead>
            <th>Earnings</th>
            <th>Amount</th>
        </thead>

        <?php

        foreach($pay_data['Compensations'] as $compensation_name => $compensation_amt){ ?>

            <tr>
                <td><?=$compensation_name?></td>
                <td><?=$compensation_amt?></td>
            </tr>

        <?php
        } 
        ?>
        

    </table>

    <table style="width:50%; margin-top:20px;">

        <thead>
            <th>Deductions</th>
            <th>Amount</th>
        </thead>

        <?php

        foreach($pay_data['Deductions'] as $deduction_name => $deduction_amt){ ?>

            <tr>
                <td><?=$deduction_name?></td>
                <td><?=$deduction_amt?></td>
            </tr>
        <?php
        } 
        ?>
        
        
    </table>

    <table style="width:100%">
        
            <tr class="border">
                <td>Total Earnings</td>
                <td><?=$pay_data['TotalCompensationsAmount']?></td>
                <td>TotalDeductions</td>
                <td><?=$pay_data['TotalDeductionsAmount']?></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>Net Pay</td>
                <td><?=$pay_data['NetPay']?></td>
            </tr>
        
    </table>

    <script src="assets/jquery-3.3.1.js"></script>
    <script>

        $('document').ready(function(){

            $('#generate-pdf').on('click', function(e){
                e.preventDefault();

                var payData = [<?php echo json_encode($pay_data)?>];

                $("#selected-records").val(JSON.stringify(payData));

                $('form').submit();

            });

        });

    </script>
</body>

</html>

<?php } 

else{
    echo "No data";
}

?>