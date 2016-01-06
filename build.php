<?php

require 'vendor/autoload.php';

use Timple\Compiler;
use Timple\Generator;

// LOAD TEMPLATE
$generator = (new Generator())
    ->load(__DIR__.'/site/static/page.html')
    ->addOutput('title, h1', 'title')
    ->addConditionAroundNode('h1 + p', 'description')
    ->addOutput('h1 + p', 'description', false)
    ->generate(__DIR__.'/site/templates/page.php');

// LOAD DATA
$compiler = (new Compiler())
    ->load(__DIR__.'/site/templates/page.php')
    ->compile(__DIR__.'/site/data/svg.json', __DIR__.'/site/static/svg.html');
