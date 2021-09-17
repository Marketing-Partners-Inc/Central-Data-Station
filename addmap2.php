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

<form action="">
    <div class="row"><div class="col-12">
        <div class="form-group">
            <label for="id_of_field">Forms</label>
            <select name="" id="id_of_field">
                <?php for($count = 0; $count< count($reponse_arr['records']); $count++){ ?>
                 <option value="<?php echo $reponse_arr['records'][$count]['form_id']; ?>"><?php echo $reponse_arr['records'][$count]['form_name']; ?></option>
               <?php  }?>
                <option value=""></option>
            </select>

        </div>
    </div></div>
</form>


