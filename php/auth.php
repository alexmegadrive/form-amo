<?php
require_once 'config.php';
$link = 'https://' . $subdomain . '.amocrm.ru/oauth2/access_token'; //Формируем URL для запроса
print_r($client_id);
print_r($redirect_uri);
$data = [
    
 'client_id'     => '3e5b255c-f8b1-4f60-af25-6cef21ca40e2',
 'client_secret' => 'FBVsCyKzto0xe9WAKApgzdM3kMUzewunJgsgJi7CIzQV08jTL1AvejmfATX1kur7',
 'code'          => 'def502006d0ce2bc56868de91cbac07c7d225d13fa62731570db0345c30f7b4fa40136a361bc9109aba769ec569fbb39e222e39d5209be818c1207077f5f35af52ff42bb9a4d61e6a0c39950836b29b45aca8ce8ec138f27a75eba5560bf5a426110851f3e9e5d6426b14936c2fc3f68dbb56f839fd0bd2da80f3ab61bf7679ac67d3515976ce7429433e3a20c3591d767eab010ad151b7fde274d009585bd2dcf08493f49ee9c36c6f8e9c5feaee5768fd5cba9ede58f0d7b5d7ef0df2d8c4ce609173b1832b21478fd098d05b34c5012130e2616db93c5cbb044ba6133f73cf30c01fd2d673cdc3982d976dcef9f26fe3a9840fbdcbcd28d687b23cffaaa54045f9e1d46f78b5a21e475c24defa71cd7ffc9cf016775d36b7738b13475f7aa2c3970e4aba5055b663a4b512478bfde9ef0a89ef147941a5d01d31d95ec5c1e5fa16ddc4a9cbd6c744a69efb69e7fc24a77ad2ff1966831442e648b48fb47160d7d9e3a2b0fa6b7a7943251eb60bd351d660a0cfd371ce858c07aefa93d9c865306457515406a6cd7ee0b1d8448a2a1bc2a3fc28f27a8f2c84ae67300e514bc4ee249e2859c56a89477b397f138d2554ee1972c9a8381959fe4aaeba36f6836afee16d7231d1dbf0833a6c7f37bcb7df57ef4721c214bcefc15b4695d',
 
 // 'client_id'     => $client_id,
 // 'client_secret' => $client_secret,
 //'code'          => $code,
  'grant_type'    => 'authorization_code',
  
  'redirect_uri'  => $redirect_uri,
];

$curl = curl_init();
curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
curl_setopt($curl,CURLOPT_URL, $link);
curl_setopt($curl,CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
curl_setopt($curl,CURLOPT_HEADER, false);
curl_setopt($curl,CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
$out = curl_exec($curl);
$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);
$code = (int)$code;

$errors = [
	301 => 'Moved permanently.',
  400 => 'Wrong structure of the array of transmitted data, or invalid identifiers of custom fields.',
  401 => 'Not Authorized. There is no account information on the server. You need to make a request to another server on the transmitted IP.',
  403 => 'The account is blocked, for repeatedly exceeding the number of requests per second.',
  404 => 'Not found.',
  500 => 'Internal server error.',
  502 => 'Bad gateway.',
  503 => 'Service unavailable.'
];

if ($code < 200 || $code > 204) die( "Error $code. " . (isset($errors[$code]) ? $errors[$code] : 'Undefined error') );


$response = json_decode($out, true);

$arrParamsAmo = [
	"access_token"  => $response['access_token'],
	"refresh_token" => $response['refresh_token'],
	"token_type"    => $response['token_type'],
	"expires_in"    => $response['expires_in'],
	"endTokenTime"  => $response['expires_in'] + time(),
];

$arrParamsAmo = json_encode($arrParamsAmo);

$f = fopen($token_file, 'w');
fwrite($f, $arrParamsAmo);
fclose($f);

print_r($arrParamsAmo);