<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

function render_template(Request $request): Response
{
    extract($request->attributes->all());
    ob_start();
    include(sprintf(__DIR__.'/../src/pages/%s.php', $_route));
    return new Response(ob_get_clean());
};

$request = Request::createFromGlobals();
$response = new Response();

include('../src/routes.php');

$context = new RequestContext();
$context->fromRequest($request);

$matcher = new UrlMatcher($routes, $context);
$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

$path = $request->getPathInfo();

try {
    $request->attributes->add($matcher->match($path));
    $controller = $controllerResolver->getController($request);
    $arguments = $argumentResolver->getArguments($request, $controller);
    $response = call_user_func_array($controller, $arguments);
} catch (ResourceNotFoundException $e) {
    $response->setStatusCode(404);
    $response->setContent('Not found');
} catch (Exception $e) {
    $response->setStatusCode(500);
    $response->setContent('Something went wrong :/');
}

$response->send();
