<?php

require_once 'vendor/autoload.php';

$stringToEncrypt = ['ArkaGdynia Arkasjsjsj 1238u19daj 12398na 1238hdas'];

$alohomora = new Alohomora\core\Alohomora();
$alohomora->setEntryFromArray($stringToEncrypt);
$alohomora->encryptEntry();