<?php

return [
   	'age_range_female' => [
        '18-25'  => '18-25',
        '26-30'  => '26-30',
        '31-35'  => '31-35'
    ],
   	'age_range_male' => [
        '18-24'  => '18-24',
        '24-32'  => '24-32',
        '32-40'  => '32-40'
    ],

    'http_code'=>[
        'ok'=>200,
        'unauthorize'=>401,
        'no_content'=>204,
        'login_fail'=>401,
        'unprocessable entity'=>422,
        'mail_fail'=>535,
        'exception'=>500,
        'notFound'=>404,
        'forbidden'=>403,
        'expectation_failed'=>417
    ],
    'ADMIN_URL'=>'admin',
];
