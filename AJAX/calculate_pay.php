<?php

function calculate_pay($con, $id, $month_year_string){

    $res = mysqli_query($con, "Select * from employees where Id = {$id}");

    if($res){

        $month_year = explode("-", $month_year_string);

        $month = $month_year[0];
        $year = $month_year[1];

        $pay_data = array();

        $employee_record = mysqli_fetch_assoc($res);

        $employee_compensations = mysqli_fetch_assoc(mysqli_query($con, "Select BasicSalary,HouseRent,FuelAllowance,
        UtilityAllowance,MobileAllowance,OtherAllowance 
        from employee_compensation where EmployeeId = {$id}"));

        if($employee_compensations['BasicSalary'] && $employee_compensations['BasicSalary'] != 0){    //cant calculate pay without basic salary

            $netpay = 0; 
            $total_compensations_amount = 0;
            $total_deductions_amount = 0;

            $compensations = array();
            $deductions = array();

            foreach($employee_compensations as $compensation_name => $compensation_amt){
                if($compensation_amt != 0){
                    $total_compensations_amount += $compensation_amt;
                    $compensations[$compensation_name] = (int)$compensation_amt;
                }
            }

            $employee_leaves = mysqli_fetch_assoc(mysqli_query($con, "Select * from employee_leaves where EmployeeId = {$id}"));
        
            $employee_loans = mysqli_fetch_assoc(mysqli_query($con, "Select * from employee_loans where EmployeeId = {$id}"));


            if($employee_leaves){  //check if leave record exists

                if($employee_leaves['MonthYear'] == $month_year_string){

                    $basic_sal = $employee_compensations['BasicSalary'];

                    $days_month = cal_days_in_month(CAL_GREGORIAN,$month,$year);

                    $sal_per_day = $basic_sal / $days_month;
                    
                    $leaves_without_pay = $employee_leaves['LeavesWithoutPay'];

                    $leaves_without_pay_deduction = round($sal_per_day * $leaves_without_pay);

                    $total_deductions_amount += $leaves_without_pay_deduction;
                    
                    $deductions['LeavesWithoutPay'] = $leaves_without_pay_deduction;
                    
                }

            }

            if($employee_loans){    //check if loan record exists

                $current_month_year_unix = strtotime("$year-$month-1");
                $loan_start_unix =  strtotime($employee_loans['StartDate']);
                $loan_end_unix = strtotime($employee_loans['EndDate']);

                if(($loan_start_unix <= $current_month_year_unix) && ($loan_end_unix >= $current_month_year_unix)){
                    $installment = (int) $employee_loans['InstallmentAmount'];
                    $total_deductions_amount += $installment;
                    $deductions['LoanInstallment'] = $installment;
                }

            }

            $netpay = $total_compensations_amount - $total_deductions_amount;

            $pay_data['NetPay'] = $netpay;
            $pay_data['TotalCompensationsAmount'] = $total_compensations_amount;
            $pay_data['TotalDeductionsAmount'] =  $total_deductions_amount;
            $pay_data['Compensations'] = $compensations;
            $pay_data['Deductions'] = $deductions;
            $pay_data['Employee'] = $employee_record;
            $pay_data['MonthYear'] = $month.'-'.$year;

            $pay_data['Status'] = 1;
            
        }

        else {
            $pay_data['Status'] = 0;
            $pay_data['Message'] = 'Basic Salary not set';
        }

        return $pay_data;

    }

}



?>