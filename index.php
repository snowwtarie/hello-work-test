#!/usr/bin/env php
<?php

use App\Command\RetrieveOffersCommand;
use App\Command\ShowOffersCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

$console = new Application();
$console->add(new RetrieveOffersCommand());
$console->add(new ShowOffersCommand());

$console->run();