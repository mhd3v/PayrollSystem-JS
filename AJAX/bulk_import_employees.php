<?php

include('../connection.php');

$return_data;

if (isset($_FILES['record-file'])) {
    
    $fileName = $_FILES['record-file']['tmp_name'];
    
    if ($_FILES['record-file']['size'] > 0) {
        
        $file = fopen($fileName, 'r'); //r flag for read only

        $data = array();

        while (($row = fgetcsv($file, ",")) !== FALSE) {
            array_push($data,$row);
        }

        $query_starting = "Insert into employees (";

        foreach($data[0] as $col_name){
            $col_name = str_replace(" ", "", $col_name);
            $query_starting .= "${col_name}, ";
        }

        $query_starting = substr($query_starting, 0, -2). ") Values ("; //remove last comma and blank space
        $successful_insertions = 0;
        $failed_queries = array();

        for($i = 1; $i < sizeof($data); $i++){

            $complete_query = $query_starting;
            
            foreach($data[$i] as $col_data)
                $complete_query .= "'".$col_data."', ";

            $complete_query = substr($complete_query, 0, -2). ")"; //remove last comma and blank space
           
            $res = mysqli_query($con, $complete_query);

            if($res)
                $successful_insertions ++;
            else
                array_push($failed_queries, $complete_query);

        }

        if(sizeof($failed_queries) != 0){
            $return_data['Status'] = 0;
            $return_data['FailedQueries'] = $failed_queries;
            $return_data['Insertions'] = $successful_insertions;

            if($successful_insertions == 0)
                $return_data['Error'] = 'All insertions failed';
            else
                $return_data['Error'] = 'Some insertions failed';
        }
        else {
            $return_data['Status'] = 1;
            $return_data['Insertions'] = $successful_insertions;
            $return_data['Message'] = "$successful_insertions records inserted!";
        }
        
    }

    else{
        $return_data['Status'] = 0;
        $return_data['Error'] = 'File empty';
    }
}

else {
    $return_data['Status'] = 0;
    $return_data['Error'] = 'File not set';
}

echo json_encode($return_data);

   
?>