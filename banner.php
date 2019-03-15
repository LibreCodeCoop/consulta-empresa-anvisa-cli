<?php
require_once 'vendor/autoload.php';

use League\CLImate\CLImate;

if(php_sapi_name() !== 'cli'){
    die('Can only be executed via CLI');
}

$climate = new CLImate();
$climate->info('####################################################');
$climate->br();
$climate->lightGreen()->out('Bem vindo ao Consulta Empresea!');
$climate->br();
$climate->info('####################################################');
