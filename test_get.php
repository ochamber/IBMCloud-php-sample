<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

$data_string = '
{
}
';

//$endpoint = "https://192.168.1.79/fineract-provider/api/v1/";
$endpoint = "https://158.177.112.252/fineract-provider/api/v1/";
$username= "mifos";
$password= "password";
$tenant = "default";

$method = "GET";
$api_target = "clients";
//$URL= $endpoint.$api_target."?tenantIdentifier=".$tenant;
$URL= $endpoint.$api_target;
//echo $URL . "\n" ;
//echo "<br>" ;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$URL);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Fineract-Platform-TenantId: default',
    'Content-Length: ' . strlen($data_string))
);
curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
//echo $status_code;
//print curl_error($ch);
$result=curl_exec ($ch);
//echo $result;
curl_close ($ch);

$result = json_decode(json_encode(json_decode($result,true)), true);
echo "<pre>".print_r($result,true)."</pre>";

?>
