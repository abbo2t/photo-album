#! /usr/bin/env php

<?php

use Symfony\Component\Console\Application;

require dirname(__FILE__) . '/vendor/autoload.php';

$app = new Application('ApimGenerator', '1.0.0');

$app->add(new \PhotoAlbum\Commands\ApimCommand());

$app->run();
