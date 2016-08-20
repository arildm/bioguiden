<?php

use Klavaro\Bioguiden\Client;
use Klavaro\Bioguiden\Service\RepertoireExport;

require __DIR__ . '/vendor/autoload.php';

$client = new Client('hagabion', 'hagabion', new RepertoireExport());
$response = $client->request();
var_dump($response);
