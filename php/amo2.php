<?php

require_once 'access.php';



$name = 'тестовый от Александра';
$phone = $_POST['phone'];
$email = $_POST['email'];
$target = 'Цель';
$company = 'Название компании';



$ip = '1.2.3.4';
$domain = 'test.test';
$price = 0;
$pipeline_id = 5704864;
$status_id = 50155177;
$user_amo = 8483899;

$utm_source   = '';
$utm_content  = '';
$utm_medium   = '';
$utm_campaign = '';
$utm_term     = '';
$utm_referrer = '';




    
$data = [
         [
        "name" => 'Заявка ✓ Михайлов А.',
        "price" => $price,
        "responsible_user_id" => $user_amo,
        "status_id" =>  $status_id,
        "pipeline_id" =>  $pipeline_id,
        "_embedded" => [
            "metadata" => [
                "category" => "forms",
                "form_id" => 1,
                "form_name" => "Форма Александр Михайлов",
                "form_page" => $target,
                "form_sent_at" => strtotime(date("Y-m-d H:i:s")),
                "ip" => $ip,
                "referer" => $domain
            ],
            "contacts" => [
                [
                    "first_name" => $name,
                    "custom_fields_values" => [
                        [
                            "field_code" => "EMAIL",
                            "values" => [
                                [
                                    "enum_code" => "WORK",
                                    "value" => $email
                                ]
                            ]
                        ],
                        [
                            "field_code" => "PHONE",
                            "values" => [
                                [
                                    "enum_code" => "WORK",
                                    "value" => $phone
                                ]
                            ]
                        ],
                        
                    ]
                ]
            ],
            /*"companies" => [
                [
                    "name" => $company
                ]
            ]*/
        ]
    ]
    ];




$method = "/api/v4/leads/complex";
$method_take_unsorted = "/api/v4/leads/unsorted/8483899/decline";


$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $access_token,
];


$curl = curl_init();
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');
curl_setopt($curl, CURLOPT_URL, "https://$subdomain.amocrm.ru".$method);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_COOKIEFILE, 'amo/cookie.txt');
curl_setopt($curl, CURLOPT_COOKIEJAR, 'amo/cookie.txt');
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
$out = curl_exec($curl);
$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$code = (int) $code;

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


$Response = json_decode($out, true);
$Response = $Response['_embedded']['items'];
$output = 'ID добавленных элементов списков:' . PHP_EOL;
foreach ($Response as $v)
    if (is_array($v))
        $output .= $v['id'] . PHP_EOL;
//return $output;
//mail("alexmihailov91@gmail.com", "Заявка с сайта", "ФИО:".$name.". Телефон: ".$phone ,"From: example22131232@mail.ru \r\n");

mail("alexmihailov91@gmail.com",
     "Заявка с формы",
     "Имя: ".$name."\n".
     "Телефон ".$email,
     "From: script@mail.ru \r\n");

header("Location: http://i91960j3.beget.tech/sales_generator/index.html"); /* Перенаправление браузера */
exit( );
