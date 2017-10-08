<?php

$latitude = $_POST["latitude"];
$longitude = $_POST["longitude"];
$radius = $_POST["radius"];
$tags = $_POST["tags"];
$cultural = $_POST['cultural'];
/*$latitude = 39.1141327;
$longitude = -84.51;
$radius = 100000;
$tags = 'wildlife-zoos';
$cultural = 'harambe';*/



$mydata = array ( 
'center'=> $longitude.','.$latitude, 
'radius'=>$radius,
'tags'=>$tags,
'cultural_query'=>$cultural
);
$data_json = json_encode($mydata);


$SECRET_KEY = 'e1ed6f5012e8ce78068b2e462b4940c4ea8d5208f3c834bdbe21590c86406bab';
$s = hash_hmac('sha256','/api/v2/search?limit=50-'.$data_json, $SECRET_KEY, false);

//send it off

$curl = curl_init();

$headers = array();
$headers[] = 'Accept: application/json';
$headers[] = 'Content-Type: application/json';
$headers[] = "RT-ORG-APP-CLIENT-ID: fa9d12f72f914b9bbeadfb07b7d15b6f";
$headers[] = "RT-ORG-APP-HMAC: ". $s;

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.roadtrippers.com/api/v2/search?limit=50",
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => $headers,
  CURLOPT_POSTFIELDS => $data_json,
 
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}