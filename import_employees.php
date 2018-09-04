<?php
$page_name = 'Bulk Import Employees';
include('header.php');
?>

<form style="margin:0 20% 0 20%;">

    <h2 style="text-align:center; margin-top:10%;">Add new employee</h2>

    <fieldset class="border p-2 form-group">
        
        <legend class="w-auto">Import through CSV</legend>

        <div class="form-group">
            <label>File input</label>
            <input type="file" class="form-control-file" id="record-file" name="record-file" accept=".csv">
            <input type="submit" name="test" class="btn btn-success my-2">
            <small class="form-text text-muted">Chose the employee records file (CSV format).</small>
        </div>

    </fieldset>

    <div class="alert alert-primary" id="msg" style="display:none"></div>

</form>

<script>

    $('document').ready(function(){

        $('form').on('submit', function(e) {
            e.preventDefault();

            var file_data = $('#record-file').prop('files')[0];
            var form_data = new FormData();
            form_data.append('record-file', file_data);

            $.ajax({
                url: 'AJAX/bulk_import_employees.php',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                         
                type: 'post',

                success: function(data){
                    var data = JSON.parse(data);

                    if(data.Status == 1){
                        $('#msg').html(data.Message);
                        console.log(data);
                    }
                    else{
                        $('#msg').html(data.Error);
                        console.log(data);
                    }
                       
                },

                error: function (data) {
                    $("#msg").html("failed to connect to server");
                }

            });

            $("#msg").fadeTo(1500, 500).slideUp(500, function(){
                $("#msg").slideUp(500);
            });
        });

    });
</script>

</body>
</html>