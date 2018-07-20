<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

function render_template(Request $request): Response
{
    extract($request->attributes->all());
    ob_start();
    include(sprintf(__DIR__.'/../src/pages/%s.php', $_route));
    return new Response(ob_get_clean());
};

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
