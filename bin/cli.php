#!/usr/bin/env php
<?php
// application.php

require __DIR__.'/../vendor/autoload.php';

use App\Commands\Mars;
use App\Services\Grid\Grid;
use Symfony\Component\Console\Application;

$application = new Application();

// ... register commands

$gridService = new Grid();

$application->add(new Mars($gridService));


$application->run();