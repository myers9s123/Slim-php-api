#!/usr/bin/php

<?php

$putData = array(
    'name' => 'Test New',
);

$ch = curl_init('http://apitest.local/providers/2');
curl_setopt_array($ch, array(
    CURLOPT_CUSTOMREQUEST  => 'PUT',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => array(
        'Content-Type: application/json',
    ),
    CURLOPT_POSTFIELDS => json_encode($putData),
));

$response = curl_exec($ch);

var_dump($response);

?>