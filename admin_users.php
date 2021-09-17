<?php
    session_start();
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit();
    } else{
        
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
        
       
<?php
// Attempt select query execution
$sql = "SELECT * FROM users";
if($result = mysqli_query($link, $sql)){
    if(mysqli_num_rows($result) > 0){
        echo "<table border='1' width='100%'>";
            echo "<tr>";
                echo "<th>id</th>";
                echo "<th>UserName</th>";
            echo "</tr>";
        while($row = mysqli_fetch_array($result)){
            echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['username'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        // Free result set
        mysqli_free_result($result);
    } else{
        echo "No records matching your query were found.";
    }
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}
 
// Close connection
mysqli_close($link);



?>



</br></br></br>

    <!-- /.row -->
    
    <!-- /.row -->
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