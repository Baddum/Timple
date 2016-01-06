<?php

require 'vendor/autoload.php';

use Timple\TwigTemplate;

// LOAD TEMPLATE
$template = (new TwigTemplate)
    ->fromFile(__DIR__ . '/site/static/page.html');

$template
    ->query('title')
    ->content('document.title');

$template
    ->query('h1')
    ->condition('document.title')
        ->query('span', true)
        ->content('document.title');

$template
    ->query('h1 + p')
    ->conditionalContent('document.description', false);

$template
    ->query('ul');

$template
    ->generateFile(__DIR__ . '/site/templates/page.html.twig');

