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
    $form_ids_array = array();
    $mapped_form_ids_array = array();
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
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>S.no </th>
                        <th>Form Name </th>
                        <th>Form ID</th>
                        <td>Status</td>
                        <th>Action </th>
                    </tr>
                </thead>
                <tbody>
                    <?php for($count = 0; $count< count($reponse_arr['records']); $count++){ ?>
                        <?php if(!empty($reponse_arr['records'][$count]['form_id'])) { $form_mapped = 0;?>
                        <?php if( in_array($reponse_arr['records'][$count]['form_id'], $form_ids_array)) { $form_mapped = 1; }?>
                        <tr>
                            <td><?= $count+1; ?></td>
                            <td><?= $reponse_arr['records'][$count]['form_name']; ?></td>
                            <td><?= $reponse_arr['records'][$count]['form_id']; ?></td>
                            <td>
                                <?php if($form_mapped) {echo 'Mapped';} ?>
                                <?php if(!$form_mapped) {echo 'Not Mapped';} ?>
                            </td>
                            <td>
                                <?php if($form_mapped) {echo '<button class="w-100 btn button bg-primary">Activate API</button>';} ?>
                                <?php if(!$form_mapped) {echo '<a href="addmap.php?map_id='.$reponse_arr['records'][$count]['form_id'].'" class="w-100 btn button bg-primary map-this-form"  data-attr="'.$reponse_arr['records'][$count]['form_id'].'">Map Form</a>';} ?>
                            </td>
<!--                             <td>
                                <a 
                                    href="update_map.php?map_id=<?= $row['formid']; ?>" data-attr="<?= $row['formid']; ?>" class="pr-3"><i class="fa fa-pencil text-primary"></i></a>
                                <a href="?map_id=<?= $row['formid']; ?>" data-attr="<?= $row['formid']; ?>"><i class="fa fa-trash text-danger" ></i></a>
                            </td> -->
                        </tr>
                        <?php  } ?>
                    <?php  } ?>
                </tbody>
            </table>
        </div>
   </div>
</div>
</body>
</html>
