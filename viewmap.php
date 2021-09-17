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
    $success_text = '';
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
    $form_names_array = array();
    for($count = 0; $count<count($reponse_arr['records']);$count++){
        $form_id = $reponse_arr['records'][$count]['form_id'];
        $form_names_array[$form_id] = $reponse_arr['records'][$count]['form_name'];
    }
?>


<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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
            <table class="table">
                <thead>
                    <tr>
                        <th class="col-2">S. No</th>
                        <th class="col-7">Form Name</th>
                        <th class="col-3">Status</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="col-12">
            <div id="accordion">
                    <?php 
                        
                        $accordian_count = 1;
                        $db_query = "SELECT formid FROM map GROUP BY formid";
                        $result2 = mysqli_query($link, $db_query) or die("mysql error");	
                        while($row2 = mysqli_fetch_array($result2)){ 
                            $count = 1;
                            $form_id = $row2['formid']; ?>
                            <div class="card" >
                                <div class="card-header text-left" id="heading-<?= $accordian_count; ?>">
                                    <h5 class="mb-0">
                                        <div class="row">
                                            <div class="col-2">
                                                <span class="pl-2"><?= $accordian_count.'.'; ?></span>
                                            </div>
                                            <div class="col-10">
                                               <button class="pl-0 text-left btn btn-link w-100" data-toggle="collapse" data-target="#collapse-<?= $accordian_count; ?>" aria-expanded="true" aria-controls="collapse-<?= $accordian_count; ?>">
                                                    From >> <?= $form_names_array[$form_id]; ?><span class="fa fa-chevron-down float-right pl-3"></span>
                                                </button> 
                                            </div>
                                        </div>
                                        
                                    </h5>
                                </div>
                                <div id="collapse-<?= $accordian_count; ?>" class="collapse" aria-labelledby="heading-<?= $accordian_count; ?>" data-parent="#accordion">
                                    <div class="card-body">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>S.no </th>
                                                <th>Local Input Name </th>
                                                <th>API Input Name </th>
                                                <td>Status</td>
                                                <th>API Form</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    <tbody>
                                    <?php   
                                        
                                        $db_query = "SELECT localfield, externalfield, formid, status FROM map WHERE formid = '$form_id'";
                                        $result = mysqli_query($link, $db_query) or die("mysql error");	
                                        while($row = mysqli_fetch_array($result)){
                                    ?>
                                            <tr>
                                                <td><?= $count; ?></td>
                                                <td><?= $row['localfield']; ?></td>
                                                <td><?php if(empty($row['externalfield'])) echo '-'; else echo $row['externalfield']; ?></td>
                                                <td><?= $row['status']; ?></td>
                                                <td><?= $form_names_array[$row['formid']]; ?></td>
                                                <td>
                                                    <a href="update_map.php?map_id=<?= $row['formid']; ?>" data-attr="<?= $row['formid']; ?>" class="pr-3"><i class="fa fa-pencil text-primary"></i></a>
                                                    <a href="?map_id=<?= $row['formid']; ?>" data-attr="<?= $row['formid']; ?>"><i class="fa fa-trash text-danger" ></i></a>
                                                </td>
                                            </tr>
                                        <?php $count++; } ?>
                                    </tbody>
                                </table> 
                            </div>
                        </div>
                    </div>
                    <?php $accordian_count++; }?>
            </div>
        </div>
   </div>
</div>
</body>
</html>
