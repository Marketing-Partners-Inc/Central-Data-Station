<?php
    session_start();
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit();
    } 
    
?>
<?php include("includes/config.php");
include_once('includes/header.php');

?>
<!DOCTYPE html>
<html>
  
<head>
	<?php //include("includes/head-tag-contents.php");?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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
    //echo '<pre>'; print_r($reponse_arr );echo '</pre>';
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Maps</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    
    
    <div class="row"><div class="col-12">
        <form action="">
        <div class="form-group">
            <label for="name_form">Forms</label>
            <select name="" id="name_form" class="form-control">
                <?php for($count = 0; $count< count($reponse_arr['records']); $count++){ ?>
                    <option value="<?php echo $reponse_arr['records'][$count]['form_id']; ?>"><?php echo $reponse_arr['records'][$count]['form_name']; ?></option>
               <?php  } ?>
                <option value=""></option>
            </select>
        </div>
<?php        
    $result = mysqli_query($link, "SELECT * FROM customer")
or die("mysql error");

$i = 0;
	echo '<html><body><table width="100%"><tr>';
	while ($i < mysqli_num_fields($result))
	{
		$meta = mysqli_fetch_field($result);
		echo '<tr><td>' . $meta->name . '</td><td>';?> 
		
		 <div class="form-group mb-5">
            <label for="form_fields">Form Fields</label>
            <select name="form_fields" id="form_fields" class="form-control">
                <option value="">Select an option</option>
            </select>
        </div>
		<?php echo '</td></tr>';
		$i = $i + 1;
	}
	echo '</tr>';
	
	echo '</table>';

        
        
     ?>   
       
   </form> </div></div>

    <!-- /.row -->
    <div class="row">
        
        <div class="container" id="main-content">
            <div class="panel panel-primary">
                    
<input type="button" value="Create map" >


<form action="">
    <div class="row"><div class="col-12">
        <div class="form-group">
            <label for="id_of_field">Forms</label>
            <select name="" id="id_of_field">
                <option value=""></option>
            </select>

        </div>
    </div></div>
</form>
<script>

</script>
	  <?php
    if (isset($_POST['submit'])){
    $fname= $_POST['fname'];
    $lname= $_POST['lname'];
    $address= $_POST['address'];
    $city= $_POST['city'];
    $state= $_POST['state'];
    $zip= $_POST['zip'];
    $email= $_POST['email'];
    $phone= $_POST ['phone'];
    $sql = "INSERT INTO customer (fname, lname, address, city, state, zip, email, phone) VALUES ('$fname', '$lname', '$address', '$city', '$state', '$zip', '$email', '$phone')";
if(mysqli_query($link, $sql)){
    echo "Records inserted successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}
    }
    ?>
    

	
</div>


</div>
<!-- /#page-wrapper -->

</body>
<script>
    var api_url = 'https://bigcloud.work/api/category/read.php';
    fetch(api_url)
        .then(
            res => res.json())
        .then(
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
                        $('#form_fields').html(response_html);
                    }
                }
            });  
    }
}
    $(document).ready(function(){
        var form_id = parseInt($('#name_form').val());
        fetch_form_fields_for_oldman(form_id);
        $('#name_form').on('change', function(){
            $('#form_fields').html('');
            var form_id = parseInt($('#name_form').val());
            fetch_form_fields_for_oldman(form_id);
        })
    })
</script>
</html>