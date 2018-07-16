<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$routes = new RouteCollection();
$routes->add('hello', new Route(
    '/hello/{name}',
    ['name' => 'World', '_controller' => 'render_template']
));
$routes->add('bye', new Route('/bye', ['_controller' => 'render_template']));
$routes->add('world-cup', new Route(
    '/has-won-the-world-cup/{country}',
    ['_controller' => function(Request $request) {
        $country = $request->attributes->get('country');
        if (strtolower($country) === 'france') {
            return render_template($request);
        }
        return new Response('No.');
    }]
));
