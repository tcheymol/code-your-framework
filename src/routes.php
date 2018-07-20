<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();
$routes->add('hello', new Route(
    '/hello/{name}',
    ['name' => 'World', '_controller' => 'render_template']
));
$routes->add('bye', new Route('/bye', ['_controller' => 'render_template']));
$routes->add('world-cup', new Route(
    '/has-won-the-world-cup/{country}',
    ['_controller' => 'WorldCup\Controller\WorldCupController::hasWonTheWorldCup']
));
