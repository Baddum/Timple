<?php

require 'vendor/autoload.php';

use Timple\Generator;
use Timple\Compiler;

// LOAD TEMPLATE
$generator = new Generator();
$generator->load(__DIR__ . '/site/static/page.html');
$generator->addOutput('title, h1', 'title');
$generator->generate(__DIR__ . '/site/templates/page.php');


// LOAD DATA
$compiler = new Compiler();
$compiler->load(__DIR__ . '/site/templates/page.php');
$compiler->compile(__DIR__ . '/site/data/svg.json', __DIR__ . '/site/static/svg.html');
