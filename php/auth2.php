<?php

$subdomain = 'irinkasidorova1990'; //Поддомен нужного аккаунта
$link = 'https://' . $subdomain . '.amocrm.ru/oauth2/access_token'; //Формируем URL для запроса

/* Соберем данные для запроса */
$data = [
	'client_id' => 'd9416e6c-b774-4f2f-adfb-a268eb9ff110', // id нашей интеграции
	'client_secret' => '27zxPM8Jagtnmv4WrkVyIWsyQtrnSdBwSpW00WDFShi9Lxnfia1x9nyoGTA6r4GC', // секретный ключ нашей интеграции
	'grant_type' => 'authorization_code',
	'code' => 'def5020095ec23945aee9366c8b8f7da8ae6d2fdeee224f31f728566214e15f67b399d21ee9c3fb1e86186a3ba951706b1d6ff40d1530fdd830300f1f26ba37bca74893c5db5e3669c4a058ebb5fb803f3b14595ebeff12c140727c855e09accb0c7688e214d8f14c0c8d603214d382196912bec1807b22a4d4dde31af023badfa21f4757b8e36d78c7d16540b1fafe18456e73c408779950a471a6d34266ac2cab25d4c0436f9975569c2f3e340467e9dad85a84b515301ad731b56970c712d8705c31c9c0362e83c12095afb81ae2e6afacd5293158a6d8bc91105797fe2b77f487d9ebe7b4df89ecea2b73e7b64bc62146ff687062135d2c214a569afdf3a0b811bdc0456e7407e95d9d82592a50fbcf7bb207b1792750b55329eae0e36f192267cbf930923239a42f406ffcb37b14170b1a1c4627f39bb3ec9a31de8aa9be3b315f7659852f1cf76d321eb1b6c720c3b1b26c924c5e1c176800467a47a990a087fbeb1008fe200199a81bc2f5ff66dd9891a0b9c96df764b33b0a51dcc24a2507064ce51e73e400f54daecc95c815cf5e51a0b3971647b00d2568e79baaaade7f4a664d698ce0c25c513f558c093b38e374b2c8089c75fa288a781f6356c1e8c13be7e1c4af5e40680742e45bf714dc456ab8b2008902d28d9376c', // код авторизации нашей интеграции
	'redirect_uri' => 'http://i91960j3.beget.tech/',// домен сайта нашей интеграции
];

/**
 * Нам необходимо инициировать запрос к серверу.
 * Воспользуемся библиотекой cURL (поставляется в составе PHP).
 * Вы также можете использовать и кроссплатформенную программу cURL, если вы не программируете на PHP.
 */
$curl = curl_init(); //Сохраняем дескриптор сеанса cURL
/** Устанавливаем необходимые опции для сеанса cURL  */
curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
curl_setopt($curl,CURLOPT_URL, $link);
curl_setopt($curl,CURLOPT_HTTPHEADER,['Content-Type:application/json']);
curl_setopt($curl,CURLOPT_HEADER, false);
curl_setopt($curl,CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
$out = curl_exec($curl); //Инициируем запрос к API и сохраняем ответ в переменную
$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);
/** Теперь мы можем обработать ответ, полученный от сервера. Это пример. Вы можете обработать данные своим способом. */
$code = (int)$code;

// коды возможных ошибок
$errors = [
	400 => 'Bad request',
	401 => 'Unauthorized',
	403 => 'Forbidden',
	404 => 'Not found',
	500 => 'Internal server error',
	502 => 'Bad gateway',
	503 => 'Service unavailable',
];

try
{
	/** Если код ответа не успешный - возвращаем сообщение об ошибке  */
	if ($code < 200 || $code > 204) {
		throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undefined error', $code);
	}
}
catch(\Exception $e)
{
	die('Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode());
}

/**
 * Данные получаем в формате JSON, поэтому, для получения читаемых данных,
 * нам придётся перевести ответ в формат, понятный PHP
 */
$response = json_decode($out, true);

/* массив со всеми необходимыми данными, его вам нужно будет сохранить в файле или в БД, чтобы при каждом запросе получать токен */
$arrParamsAmo = [
	"access_token" => $response['access_token'],
	"refresh_token" => $response['refresh_token'],
	"token_type" => $response['token_type'],
	"expires_in" => $response['expires_in'],
	"endTokenTime" => $response['expires_in'] + time(),
];

$arrParamsAmo = json_encode($arrParamsAmo);

// выведем наши токены. Скопируйте их для дальнейшего использования
// access_token будет использоваться для каждого запроса как идентификатор интеграции
var_dump($arrParamsAmo);