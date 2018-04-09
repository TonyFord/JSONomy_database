<?php

require_once 'GoogleAuthenticator.php';

$ga = new PHPGangsta_GoogleAuthenticator();
$secret = $ga->createSecret();
echo "Secret is: ".$secret."<br><br>";

$qrCodeUrl = $ga->getQRCodeGoogleUrl('Stargate', $secret);
echo "Google Charts URL for the QR-Code: <img src='".$qrCodeUrl."'><br><br>";

$oneCode = $ga->getCode($secret);
echo "Checking Code '$oneCode' and Secret '$secret':<br>";

?>
