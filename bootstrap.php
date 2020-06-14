<?php

require_once 'vendor/autoload.php';

$stringToEncrypt = 'ArkaGdynia Arkasjsjsj 1238u19daj 12398na 1238hdas';

$alohomora = new Alohomora\core\Alohomora();
$alohomora->setEntry($stringToEncrypt);
$alohomora->setOutputDirectory(__DIR__.'/enc');
$alohomora->setFileName('encrypted');
$publicKey = file_get_contents(__DIR__."/public.pem");
$alohomora->encryptEntry($publicKey);