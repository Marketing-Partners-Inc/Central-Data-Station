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


<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Dashboard</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        
        <div class="container" id="main-content">
            <div class="panel panel-primary">
                    
	<p>Some content goes here! Let's go with the classic "lorem ipsum."</p>
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
<form method="post" action ="">
    <div ><input name="formid" type="text" value="formid"></input><input type="checkbox"></br></div>
    <div ><input name="fname" type="text" value="john"></input><input type="checkbox"></br></div>
    <div class="l-name"><input  name="lname" type="text" value="doe"></input><input type="checkbox"></br></div>
    <div class="address"><input  name="address" type="text" value="address"></input><input type="checkbox"></br></div>
   <div class="city"><input  name="city" type="text" value="city"></input><input type="checkbox"></br></div>
   <div class="state"><input  name="state" type="text" value="state"></input><input type="checkbox"></br></div>
   <div class="zip"><input  name="zip" type="text" value="zip"></input><input type="checkbox"></br></div>
   <div class="email"><input  name="email" type="text" value="john@email.com"></input><input type="checkbox"></br></div>
    <div class="phone"><input  name="phone" type="text" value="+1-325645"></input><input type="checkbox"></br>
    </div>
    
    <button type="submit" name="submit">Add Data</button>
</form>
	<div class="third">https://1web.cloud/</div>
	
</div>
</br></br></br>

</div>
<!-- /#page-wrapper -->

</body>
<script>
$(document).ready(function(){
    var old_url = $('.third').text(); var new_url;
    initialize_url();
    $('input[type="checkbox"]').on('change', function(){
        var this_check = this;
        //if((this_check.checked)) {
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
        //}else{
            
        //}
    })
})
function initialize_url(){
        //if((this_check.checked)) {
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
        //}else{
            
        //}
}
</script>

</html>