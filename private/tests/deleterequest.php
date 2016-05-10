#!/usr/bin/php

<?php

$ch = curl_init('http://apitest.local/providers/2');
curl_setopt_array($ch, array(
    CURLOPT_CUSTOMREQUEST  => 'DELETE',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => array(
        'Content-Type: application/json',
    ),
));

$response = curl_exec($ch);

var_dump($response);

?>