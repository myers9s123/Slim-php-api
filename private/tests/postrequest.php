#!/usr/bin/php

<?php

$postData = array(
    'name'         => 'Test',
    'location'     => 'Seattle, WA',
    'phone_number' => '900-456-7890',
    'provides'     => array(
        'Diabetes Care',
    ),
);

$ch = curl_init('http://apitest.local/providers');
curl_setopt_array($ch, array(
    CURLOPT_POST           => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => array(
        'Content-Type: application/json',
    ),
    CURLOPT_POSTFIELDS => json_encode($postData),
));

$response = curl_exec($ch);

var_dump($response);

?>