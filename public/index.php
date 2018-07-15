<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

$request = Request::createFromGlobals();
$response = new Response();

include('../src/routes.php');

$context = new RequestContext();
$context->fromRequest($request);

$matcher = new UrlMatcher($routes, $context);

$path = $request->getPathInfo();

try {
    ob_start();
    extract($matcher->match($path));
    include(sprintf('../src/pages/%s.php', $_route));
    $response->setContent(ob_get_clean());
} catch (ResourceNotFoundException $e) {
    $response->setStatusCode(404);
    $response->setContent('Not found');
} catch (Exception $e) {
    $response->setStatusCode(500);
    $response->setContent('Something went wrong :/');
}

$response->send();
