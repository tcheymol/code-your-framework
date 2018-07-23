<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();

$routes = include('../src/routes.php');
$container = include('../src/container.php');
$framework = $container->get('cached_framework');

$response = $framework->handle($request);

$response->send();
