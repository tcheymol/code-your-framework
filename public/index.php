<?php

require_once __DIR__.'/../vendor/autoload.php';

use Enregistroscope\Framework;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpCache\HttpCache;
use Symfony\Component\HttpKernel\HttpCache\Store;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

$request = Request::createFromGlobals();

include('../src/routes.php');

$context = new RequestContext();

$matcher = new UrlMatcher($routes, $context);
$controllerResolver = new ControllerResolver();
$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new RouterListener($matcher, new RequestStack()));

$framework = new Framework($dispatcher, $controllerResolver);
$framework = new HttpCache($framework, new Store('../cache'));

$response = $framework->handle($request);

$response->send();
