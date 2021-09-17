<?php
    session_start();
    if(
        !isset($_GET['map_id'])  || empty($_GET['map_id'])
        ){
            header("location: login.php");
            exit();
        }

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit();
    } 
    include("includes/config.php");   

    $success_text = '';;
    if(
        !isset($_POST['TOPI'])  
    ){
        
        if(
            (isset($_POST['api_form_id']) && !empty($_POST['api_form_id'])) && 
            (isset($_POST['key_name_count']) && !empty($_POST['key_name_count']) && (int)$_POST['key_name_count'] > 1)
        ){
            
            
            $mapping_details = filter_var_array($_POST, FILTER_SANITIZE_STRING);
            $key_name_count = (int)$mapping_details['key_name_count'];
            $api_form_id = $mapping_details['api_form_id'];
            $map_id =  $api_form_id;
            $status = 1;
            $count = 0;
            $updated = date('Y-m-d H:i:s');
            $createdby = $_SESSION["id"];
            $db_query = "DELETE FROM map WHERE formid = '$map_id'";
            $result = mysqli_query($link, $db_query ) or die("mysql error");
            if($result)	{
                $db_query_1  = " INSERT INTO map ( localfield, externalfield, formid, status, updated, createdby ) ";
                $db_query_2  = " VALUES ";
                while($count < $key_name_count ){
                    $key_name = 'key_name_'.$count;
                    $table_key = str_replace('key_name_','',$mapping_details[$key_name]);
                    $table_value = $mapping_details[$mapping_details[$key_name]];
                    $db_query_2  .= " ( ";
                    $db_query_2 .= "'".$table_key."', '".$table_value."', ".$api_form_id.", ".$status.", '".$updated."', ".$createdby;
                    $db_query_2 .= " )";
                    if($count < $key_name_count-1){
                        $db_query_2 .= ", ";                    
                    }
                    $count++;
                }
                $db_query = $db_query_1 . $db_query_2;
                $result = false;
                $result = mysqli_query($link, $db_query ) or die("mysql error");	
                if($result){$success_text = "Input map updated successfully.";}                
            }

        }
    }

    $map_data = filter_var_array($_GET, FILTER_SANITIZE_STRING);
    $map_id = $map_data['map_id'];
    $db_query = "SELECT * FROM map WHERE formid = '$map_id'";
    $form_map_result = mysqli_query($link, $db_query) or die("mysql error");	
    if( $form_map_result->num_rows < 1 ){
        header("location: login.php");
        exit();
    }


?>
<?php include_once('includes/header.php'); ?>
<!DOCTYPE html>
<html>
  
<head>
	<?php //include("includes/head-tag-contents.php");?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<?php //include("includes/design-top.php");?>
<?php //("includes/navigation.php");?>
<?php
    $curl = curl_init();
    $api_url = 'https://bigcloud.work/api/category/read.php';
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://bigcloud.work/api/category/read.php',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $reponse_arr = json_decode($response, true);
?>

<div id="page-wrapper" class="mb-5">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Maps</h1>
        </div>
    </div>    
    <div class="row">
        <div class="col-12 mb-3" style="    padding-bottom: 20px;">
            <span class="text-success h4" style="color: green"><em><?= $success_text;?></em></span>
        </div>
	<div class="col-12">
        <form action="" method="POST" id="map-inputs-form" class="mb-5">
			<table class="table">
				<thead>
					<tr class="form-group">
						<th class="h3">
                        <?php for($count = 0; $count< count($reponse_arr['records']); $count++){ ?>
                            <?php if($reponse_arr['records'][$count]['form_id'] == $map_id) {?>
                                <em><?php echo $reponse_arr['records'][$count]['form_name'].' ( id = '.$reponse_arr['records'][$count]['form_id'].')';?></em>
                                <input type="hidden" id="api_form_id"  name="api_form_id" value="<?= $map_id; ?>">
                                <?php } ?>
                            <?php } ?>
                        </th>
					</tr>
				</thead>
                </table> 
                <table class="table" id="add-map-table">
                    <thead>
                        <tr>
                            <th>Map this Field <span class="pl-2">&#9660</span></th><th>To This External Field <span class="pl-2">&#9660</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $count = 0;  
                            $count_substract = 0;  
                            while($row = mysqli_fetch_array($form_map_result)){
                        ?>
                        <tr class="form-group">
                            <th style="max-width: 100px;">
                                <label for="key_name_<?php echo $row['localfield']; ?>"><?php echo $row['localfield']; ?></label>
                            </th>
                            <td colspan="2">
                                <select name="key_name_<?php echo $row['localfield']; ?>" id="key_name_<?php echo $row['Field']; ?>" class="form-control form_fields">
                                    <?php if(!empty($row['externalfield'])) {?><option value="<?= $row['externalfield']; ?>"><?= $row['externalfield']; ?></option> <?php } else{?><option value="">Select an Option</option> <?php }?>
                                </select>
                                <input type="hidden" name="key_name_<?= $count; ?>" id="key_name_<?= $count; ?>" value="key_name_<?php echo $row['localfield']; ?>">
                            </td>
                        </tr>
                        <?php $count++; } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="pt-3 pb-3" style="padding-top: 20px; max-width: 100px;">
                                <input type="text" name="" placeholder="Name Key for Input" id="new-input-name" class="form-control mb-0 is-invalid">
                            </td>
                            <td class="pt-3 pb-3" style="padding-top: 20px;">
                                <div class="form-group mb-0">
                                    <select name="" id="new-input-value" class="form-control form_fields">
                                        <option value="">Select an option</option>
                                    </select>
                                </div>
                            </td>
                            <td class="pt-3 pb-3" style="padding-top: 20px; width: 105px;">
                                <button type="button" class="btn btn-primary" id="add-new-input">Add Input</button> 
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-right"><button type="button" class="btn btn-primary" id="map-inputs-button">Map Inputs</button></td>
                        </tr>
                    </tfoot>
			</table>
            <input type="hidden" name="key_name_count" id="key_name_count" value="<?= $count; ?>">
            <?php   $new_submit=rand();   $_SESSION['new_submit']=$new_submit;  ?>
            <input type="hidden" value="<?php echo $new_submit; ?>" name="new_submit" />   
	   </form> 

   </div>
   </div>
</div>
<div class="mt-5 pt-5" style="height: 200px;"></div>
</body>

<script>
/**************Code 3************/
function fetch_form_fields_for_oldman(form_id){
            if(form_id){
                $.ajax({
                type: "POST",  
                dataType: 'json', 
                url: "includes/api.php",
                data: {form_id: form_id},
                success: function(response) {
                     var response_html = '';
                    if(response){
                        for(var i = 0; i < Object.keys(response.form_fields[0]).length ; i++){
                            response_html = response_html 
                                            + '<option value="'
                                            + Object.keys(response.form_fields[0])[i] 
                                            + '">'+ Object.keys(response.form_fields[0])[i]
                                            + '</option>';
                        }
                        $('.form_fields').append(response_html);
                    }
                }
            });  
    }
}
$(document).ready(function(){
    /**************Code 4************/
    var form_id = parseInt($('#api_form_id').val());
    fetch_form_fields_for_oldman(form_id);
    /**************Code 5************/
    $('#map-inputs-button').on('click', function(){
        $('#map-inputs-form').submit();  
    })
    /**************Code 6************/

    $('#new-input-name').on('keypress', function(e) {
        if (e.which == 32)
            return false;
    });

    $('#add-new-input').on('click', function(e){
        e.preventDefault();
        $('#new-input-name').removeClass('is-invalid');
        var input_name = $('#new-input-name').val();
        var input_value = $('#new-input-value').val();
        
        if(!input_name){
            $('#new-input-name').addClass('is-invalid');
            return false;
        }
        var input_counts = parseInt($('#key_name_count').val());
        var html = '<tr class="new-row"><th>';
        html  = html + input_name + '</th><td>';
        html  = html + input_value + '</td><td><a href="#"><i class="fa fa-trash text-danger"></i></a>';
        html  = html + '<input type="hidden" name="key_name_' + input_name + '" value="' + input_value + '">';
        html  = html + '<input type="hidden" name="key_name_' + input_counts + '" value="key_name_' + input_name +'">';
        html = html + '</td></tr>';
        $('#add-map-table tfoot').prepend(html);
        $('#key_name_count').val(input_counts + 1);
    })
    $('#add-map-table tfoot').on('click', 'tr.new-row .fa-trash', function(e){
        e.preventDefault();
        $(this).parent().parent().parent().remove()
        var input_counts = parseInt($('#key_name_count').val());
        $('#key_name_count').val(input_counts - 1);
    })
})
</script>
<script>


</script>
</html>
