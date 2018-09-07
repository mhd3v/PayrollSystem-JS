<?php

if(isset($_POST['selected-records'])){

    $records = $_POST['selected-records'];
    
    if(isset($_POST['DownloadPdf']))    //auto download
        generatePdfPaySlip($records, true);
    else
        generatePdfPaySlip($records, false);
}

else{
    echo 'No data';
}

function generatePdfPaySlip($records, $download) {

    require_once __DIR__ . '/vendor/autoload.php';
    $mpdf = new \Mpdf\Mpdf();

    $records = json_decode($records, true); 
    $num_record = sizeof($records);
    $main_counter = 1;
        
    ob_start(); ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Payslip</title>

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
        
        <div style="width:1000px;">

        <table style="width:100%;overflow:wrap"  width="100%" autosize="1">

            <tr>
                <td style="width:100pt">Employee Code</td>
                <td style="width:100pt">:<?=$record['Employee']['Code']?></td>
                <td style="width:100pt">Full Name</td>
                <td style="width:100pt">:<?=$record['Employee']['FullName']?></td>
            </tr>
            <tr>
                <td style="width:100pt">Mobile Number</td>
                <td style="width:100pt">:<?=$record['Employee']['MobileNumber']?></td>
                <td style="width:100pt">Designation</td>
                <td style="width:100pt">:<?=$record['Employee']['Designation']?></td>
            </tr>
            <tr>
                <td style="width:100pt">CNIC</td>
                <td style="width:100pt">:<?=$record['Employee']['CNIC']?></td>
                <td style="width:100pt">Department</td>
                <td style="width:100pt">:<?=$record['Employee']['Department']?></td>
            </tr>
            <tr>
                <td style="width:100pt">City</td>
                <td style="width:100pt">:<?=$record['Employee']['City']?></td>
                <td style="width:100pt">Address</td>
                <td style="width:100pt">:<?=$record['Employee']['Address']?></td>
            </tr>
            <tr>
                <td style="width:100pt">Bank Account</td>
                <td style="width:100pt">:<?=$record['Employee']['BankAccount']?></td>
            </tr>

            <tr>
                <td style="padding-top: 20px;"><b>Earnings</b></td>
                <td style="padding-top: 20px;"><b>Amount</b></td>
                <td style="padding-top: 20px;"><b>Deductions</b></td>
                <td style="padding-top: 20px;"><b>Amount</b></td>
            </tr>

            <?php 

            //$count = 0;
            $deductions = $record['Deductions'];
            $compensations = $record['Compensations'];

            $deductionsAndCompensations = sizeof($deductions) + sizeof($compensations);

            for ($count = 0; $count < $deductionsAndCompensations; $count++){ ?>

                <tr>
                    <?php 
                    if(isset(array_values($compensations)[$count])){?> 
                        <td style="width:100pt"><?=array_keys($compensations)[$count]?></td>
                        <td style="width:100pt"><?=array_values($compensations)[$count]?></td>
                    <?php }

                    //else insert empty tds:
                    else{ ?>  
                        <td style="width:100pt"></td>
                        <td style="width:100pt"></td>
                    <?php }

                    if(isset(array_values($deductions)[$count])){?> 
                        <td style="width:100pt"><?=array_keys($deductions)[$count]?></td>
                        <td style="width:100pt"><?=array_values($deductions)[$count]?></td>
                    <?php }

                    //else insert empty tds:
                    else{ ?>
                        <td style="width:100pt"></td>
                        <td style="width:100pt"></td>
                    <?php } ?>

                </tr>

            <?php } ?>

            <tr class="test">
                <td style="border-top:3px solid black; border-bottom:3px solid black;width:100pt">Total Earnings</td>
                <td style="border-top:3px solid black; border-bottom:3px solid black;width:100pt"><?=$record['TotalCompensationsAmount']?></td>
                <td style="border-top:3px solid black; border-bottom:3px solid black;width:100pt">TotalDeductions</td>
                <td style="border-top:3px solid black; border-bottom:3px solid black;width:100pt"><?=$record['TotalDeductionsAmount']?></td>
            </tr>
            <tr>
                <td style="width:100pt"></td>
                <td style="width:100pt"></td>
                <td style="width:100pt">Net Pay</td>
                <td style="width:100pt"><?=$record['NetPay']?></td>
            </tr>
            
        </table>
        </div>
        
        <?php if($main_counter != $num_record){ ?>
        <pagebreak />
        <?php } 
        $main_counter++;
        ?>

    <?php } ?>
    </body>
    </html>

<?php

$HTMLoutput = ob_get_contents();
ob_end_clean();

$mpdf->WriteHTML($HTMLoutput);

if($download)
$mpdf->Output('Payslip.pdf', 'D');

else
$mpdf->Output();

}

?>

