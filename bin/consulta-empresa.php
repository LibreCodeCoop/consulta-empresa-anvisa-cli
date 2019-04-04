#!/usr/bin/env php
<?php

if (PHP_SAPI !== 'cli') {
    echo 'Warning: Should be invoked via the CLI version of PHP, not the '.PHP_SAPI.' SAPI'.PHP_EOL;
}

require __DIR__.'/../src/bootstrap.php';

use ConsultaEmpresa\Console\Application;
use ConsultaEmpresa\Command\ConsultaCommand;

error_reporting(-1);

// run the command application
$application = new Application();
$command = new ConsultaCommand();
$application->add($command);
$application->setDefaultCommand($command->getName());
$application->run();
