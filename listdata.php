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
    $input_names =  array();
    $input_values  = array();
        
    $mySQLQuery = "SELECT formid FROM map GROUP BY formid";
    $result = mysqli_query($link, $mySQLQuery);
    $form_ids = array();
    $j = 0;
    while($row = mysqli_fetch_array($result)){ 
        $form_id = $row['formid'];
        $form_ids[$j] =  $form_id;
        $mysql_query = "SELECT COUNT(DISTINCT input_key) AS key_count from fetched_data WHERE formid = '$form_id'";
        $result3 = mysqli_query($link, $mysql_query);
        while($row3 = mysqli_fetch_array($result3)){
            $key_count[$form_id] = $row3['key_count'];
        }
        $mySQLQuery2 = "SELECT input_key, input_value FROM fetched_data WHERE formid = '$form_id'";
        $result2 = mysqli_query($link, $mySQLQuery2);
        $count = 0;
        while($row2 = mysqli_fetch_array($result2)){ 
            if($count < $key_count[$form_id]){
               $input_names[$form_id][$count]  = $row2['input_key'];
            }
            $input_names[$form_id][$count]  = $row2['input_key'];;
            $input_values[$form_id][$count] = $row2['input_value'];;
            $count++;
        }
        $j++;
    }   
 ?> 
 <?php ?>
<?php for($count=0; $count<count($form_ids);$count++){ ?>
    <table class="table table-stripped mb-4">
        <thead>
            <tr>
                <th colspan="<?=$key_count[$form_ids[$count]]+1; ?>">From <?=$count+1; ?></th>
            </tr>
            <tr>
                <?php for($j = 0; $j<$key_count[$form_ids[$count]]; $j++ ) {?>
                    <th><?php echo $input_names[$form_ids[$count]][$j] ; ?></th>
                <?php } ?>
            </tr>
        </thead>
    </table>
<?php } ?>


<?php

$sql = "SELECT * FROM customer";
if($result = mysqli_query($link, $sql)){
    if(mysqli_num_rows($result) > 0){
        echo "<table border='1' width='100%'>";
            echo "<tr>";
                echo "<th>id</th>";
                echo "<th>first_name</th>";
                echo "<th>last_name</th>";
                echo "<th>address</th>";
                echo "<th>city</th>";
                echo "<th>state</th>";
                echo "<th>zip</th>";
                echo "<th>email</th>";
                echo "<th>phone</th>";
            echo "</tr>";
        while($row = mysqli_fetch_array($result)){
            echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['fname'] . "</td>";
                echo "<td>" . $row['lname'] . "</td>";
                echo "<td>" . $row['address'] . "</td>";
                echo "<td>" . $row['city'] . "</td>";
                echo "<td>" . $row['state'] . "</td>";
                echo "<td>" . $row['zip'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['phone'] . "</td>";
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