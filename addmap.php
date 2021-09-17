<?php
    session_start();
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit();
    } 
    
?>

<?php 
    include("includes/config.php");
    include_once('includes/header.php');
?>
<?php 
    $map_data = filter_var_array($_GET, FILTER_SANITIZE_STRING);
    $map_id = (isset($map_data['map_id']) ? $map_data['map_id'] : '');
    $success_text = '';;
    if(
        !isset($_POST['TOPI'])  &&
        isset($_POST['new_submit']) && isset($_SESSION['new_submit']) && $_POST['new_submit']==$_SESSION['new_submit']
    ){
        if(
            (isset($_POST['api_form_id']) && !empty($_POST['api_form_id'])) && 
            (isset($_POST['key_name_count']) && !empty($_POST['key_name_count']) && (int)$_POST['key_name_count'] > 1)
        ){
            $mapping_details = filter_var_array($_POST, FILTER_SANITIZE_STRING);
            $key_name_count = (int)$mapping_details['key_name_count'];
            $api_form_id = $mapping_details['api_form_id'];
            $status = 1;
            $count = 0;
            $updated = date('Y-m-d H:i:s');
            $createdby = $_SESSION["id"];
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
            if($result){$success_text = "Inputs were mapped successfully.";}
        }
    }
?>

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
    $form_ids_array = array();
    $db_query = "SELECT formid FROM map GROUP BY formid";
    $result = mysqli_query($link, $db_query) or die("mysql error");	
    while($row = mysqli_fetch_array($result)){
        $form_ids_array[] = $row['formid'];
    }

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
						<th style="border-bottom: 0">
							<label for="api_form_id">Select a Form</label>
                            <select name="api_form_id" id="api_form_id" class="form-control">
								<?php for($count = 0; $count< count($reponse_arr['records']); $count++){ ?>
									<?php if(!empty($reponse_arr['records'][$count]['form_id'])) { $option_disabled = '';?>
                                        <?php if( in_array($reponse_arr['records'][$count]['form_id'], $form_ids_array)) { $option_disabled = 'disabled'; }?>
										<option value="<?php echo $reponse_arr['records'][$count]['form_id']; ?>" <?php echo $option_disabled;?> <?php if($map_id == $reponse_arr['records'][$count]['form_id']) echo 'selected'; ?>><?php echo $reponse_arr['records'][$count]['form_name']; ?></option>
									<?php  } ?>
								<?php  } ?>
							</select>	                            
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
                            $result = mysqli_query($link, "SHOW COLUMNS FROM alldata FROM web1_cloud") or die("mysql error");	
                            while($column = mysqli_fetch_array($result)) {
                                if($column['Field'] != 'id' && $column['Field'] != 'formid'){
                        ?>
                        <tr class="form-group">
                            <th style="max-width: 100px;">
                                <label for="key_name_<?php echo $column['Field']; ?>"><?php echo $column['Field']; ?></label>
                            </th>
                            <td colspan="2">
                                <select name="key_name_<?php echo $column['Field']; ?>" id="key_name_<?php echo $column['Field']; ?>" class="form-control form_fields">
                                    <option value="">Select an option</option>
                                </select>
                                <input type="hidden" name="key_name_<?= $count; ?>" id="key_name_<?= $count; ?>" value="key_name_<?php echo $column['Field']; ?>">
                            </td>
                        </tr>
                        <?php $count++; }else{$count_substract++;} } ?>
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
/**************Code 1************/
$(document).ready(function(){
    
    var old_url = $('.third').text(); var new_url;
    initialize_url();
    $('input[type="checkbox"]').on('change', function(){
        var this_check = this;
            var counter = 1;
            $('input[type="checkbox"]').each(function(){
                if((this.checked)) {
                    if(counter == 1){
                         new_url = old_url + '?';
                    }
                     
                    new_url = new_url + $(this).parent().find('input[type="text"]').attr("name") + '='+ $(this).parent().find('input[type="text"]').val() + '&';
                    counter++;
                }
            })
            if(counter==1){new_url = old_url + '?';}
            $('.third').html(new_url);
    })
})
function initialize_url(){
        var old_url = $('.third').text(); var new_url; var counter = 1;
            $('input[type="checkbox"]').each(function(){
                if((this.checked)) {
                    if(counter == 1){
                         new_url = old_url + '?';
                    }
                     
                    new_url = new_url + $(this).parent().find('input[type="text"]').attr("name") + '='+ $(this).parent().find('input[type="text"]').val() + '&';
                    counter++;
                }
            })

            $('.third').html(new_url);
}
/**************Code 2************/
    var api_url = 'https://bigcloud.work/api/category/read.php';
    fetch(api_url)
        .then(
            res => res.json()
        ).then(
            (response) => {
                var response_html = '';
            for(var i = 0; i < response.records.length ; i++){
                response_html = response_html + '<option value="'+ response.records[i].form_id + '">'+ response.records[i].form_name + '</option>'
            }
            $('#id_of_field').html(response_html);
    }
    ).catch(err => console.error(err));
</script>
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
                     var response_html = '<option value="">Select an option</option>';
                    if(response){
                        for(var i = 0; i < Object.keys(response.form_fields[0]).length ; i++){
                            response_html = response_html 
                                            + '<option value="'
                                            + Object.keys(response.form_fields[0])[i] 
                                            + '">'+ Object.keys(response.form_fields[0])[i]
                                            + '</option>';
                        }
                        $('.form_fields').html(response_html);
                    }
                }
            });  
    }
}
$(document).ready(function(){
    /**************Code 4************/
    var form_id = parseInt($('#api_form_id').val());
    fetch_form_fields_for_oldman(form_id);
    $('#api_form_id').on('change', function(){
        $('#form_fields').html('');
        var form_id = parseInt($('#api_form_id').val());
        fetch_form_fields_for_oldman(form_id);
    })
    /**************Code 5************/
    $('#map-inputs-button').on('click', function(e){
        e.preventDefault();
        if($('#api_form_id').val == '' || $('#api_form_id').val() == 0) return false;
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
        //alert('old man is angry');
        if(!input_name || !input_value){
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
