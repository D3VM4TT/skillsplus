<?php

function curl_get_contents($url) {
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

require('PaypalIPN.php');

$server = rtrim($_SERVER['SERVER_NAME'], '/');
$ipn = new PaypalIPN();
$ipn->useSandbox();
$verified = $ipn->verifyIPN();
if ($verified) {
    // pass all the $_POST variables to the craft controller
    curl_get_contents($server . '/actions/lantra/paypal/payment?' . http_build_query($_POST));
}
