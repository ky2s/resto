<?php
$key = '0d09591122ffdc20c2f1356289fe9414';
if($_GET['key'] !== 'null') {
	$key = $_GET['key'];
}
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://pro.rajaongkir.com/api/cost",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "origin=".$_GET['origin']."&originType=subdistrict&destination=".$_GET['destination']."&destinationType=subdistrict&weight=".$_GET['weight']."&courier=".$_GET['courier'],
  CURLOPT_HTTPHEADER => array(
    "content-type: application/x-www-form-urlencoded",
    "key: ".$key.""
  ),
));
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if ($err) {
  echo "Error";
} else {
  echo $response;
}
?>