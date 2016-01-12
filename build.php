<?php

require 'vendor/autoload.php';

use Timple\TwigTemplate;

// LOAD TEMPLATE
$template = (new TwigTemplate)
    ->fromFile(__DIR__ . '/site/static/page.html');

$template
    ->query('title')
    ->content('document.title')
    ->innerCondition('document.title');

$template
    ->query('h1')
    ->outerCondition('document.title')
        ->query('> span')
        ->content('document.title');

$template
    ->query('h1 + p')
    ->outerCondition('document.description')
    ->content('document.description', false);

$template
    ->query('ul')
    ->innerLoop('link in document.links')
        ->query('> li')
        ->content('link')
        ->outerCondition('loop.index < 2');

$template
    ->generateFile(__DIR__ . '/site/templates/page.html.twig');

