<?php

require 'vendor/autoload.php';

use Timple\Generator;
use Timple\Compiler;

// LOAD TEMPLATE
$generator = (new Generator)
    ->load(__DIR__ . '/site/static/page.html');

$generator
    ->select('h1')
    ->condition('title')
    ->select('span')
    ->content('title');

$generator
    ->select('h1 + p')
    ->condition('description')
    ->content('description', false);

$generator
    ->generate(__DIR__ . '/site/templates/page.php');


// LOAD DATA
$compiler = (new Compiler)
    ->load(__DIR__ . '/site/templates/page.php')
    ->compile(__DIR__ . '/site/data/svg.json', __DIR__ . '/site/static/svg.html');
