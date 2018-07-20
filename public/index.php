<?php

require_once __DIR__.'/../vendor/autoload.php';

use Enregistroscope\Framework;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
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

include('../src/routes.php');

$context = new RequestContext();
$context->fromRequest($request);

$matcher = new UrlMatcher($routes, $context);
$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

$framework = new Framework($matcher, $controllerResolver, $argumentResolver);

$response = $framework->handle($request);

$response->send();
