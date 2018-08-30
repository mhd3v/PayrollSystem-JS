<?php

$records = $_POST['selected-records'];

if(isset($records)){

    require_once __DIR__ . '/vendor/autoload.php';
    $mpdf = new \Mpdf\Mpdf();
   
    $records = json_decode($records, true); 
        
    ob_start();?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Payslip | Mahad </title>

        <style>

        body{
            font-family: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; 
            border: 3px solid black;
            padding:10px;
        }

        table {
            table-layout: fixed;
            border-collapse: collapse;
        }

        </style>
    </head>

    <body>

        <?php foreach($records as $record){ ?>

        <img src="assets/jazz-logo.jpg" style="position:absolute; z-index:-1;" width="10%" height="10%"/>
        <h2 style="text-align:center;">Jazz</h2>
        <h5 style="text-align:center;">7 Park Rd, F-8 Markaz, Islamabad, Islamabad Capital Territory</h5>
        <br>

        <h4 style="text-align:center;">Payslip for: <?=$record['MonthYear']?></h4>

        <table style="width:100%">

            <tr>
                <td>Employee Code</td>
                <td>:<?=$record['Employee']['Code']?></td>
                <td>Full Name</td>
                <td>:<?=$record['Employee']['FullName']?></td>
            </tr>
            <tr>
                <td>Mobile Number</td>
                <td>:<?=$record['Employee']['MobileNumber']?></td>
                <td>Designation</td>
                <td>:<?=$record['Employee']['Designation']?></td>
            </tr>
            <tr>
                <td>CNIC</td>
                <td>:<?=$record['Employee']['CNIC']?></td>
                <td>Department</td>
                <td>:<?=$record['Employee']['Department']?></td>
            </tr>
            <tr>
                <td>City</td>
                <td>:<?=$record['Employee']['City']?></td>
                <td>Address</td>
                <td>:<?=$record['Employee']['Address']?></td>
            </tr>
            <tr>
                <td>Bank Account</td>
                <td>:<?=$record['Employee']['BankAccount']?></td>
            </tr>

            <tr>
                <td style="padding-top: 20px;"><b>Earnings</b></td>
                <td style="padding-top: 20px;"><b>Amount</b></td>
                <td style="padding-top: 20px;"><b>Deductions</b></td>
                <td style="padding-top: 20px;"><b>Amount</b></td>
            </tr>

            <?php 

            $count = 0;
            $deductions = $record['Deductions'];
            
            foreach($record['Compensations'] as $compensation_name => $compensation_amt){ ?>

            <tr>
                <td><?=$compensation_name?></td>
                <td><?=$compensation_amt?></td>
                
                <?php 
                if(isset(array_values($deductions)[$count])){?> 
                    <td><?=array_keys($deductions)[$count]?></td>
                    <td><?=array_values($deductions)[$count]?></td>
                <?php $count++;}?>
            </tr>

            <?php }?>
    

            <tr class="test">
                <td style="border-top:3px solid black; border-bottom:3px solid black;">Total Earnings</td>
                <td style="border-top:3px solid black; border-bottom:3px solid black;"><?=$record['TotalCompensationsAmount']?></td>
                <td style="border-top:3px solid black; border-bottom:3px solid black;">TotalDeductions</td>
                <td style="border-top:3px solid black; border-bottom:3px solid black;"><?=$record['TotalDeductionsAmount']?></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>Net Pay</td>
                <td><?=$record['NetPay']?></td>
            </tr>
            
        </table>
        
        
        <pagebreak />

    <?php } ?>
    </body>
    </html>

<?php

$HTMLoutput = ob_get_contents();
ob_end_clean();

$mpdf->WriteHTML($HTMLoutput);

$mpdf->Output();

}



else{

}?>

