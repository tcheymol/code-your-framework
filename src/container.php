<?php

use Enregistroscope\Framework;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpCache\HttpCache;
use Symfony\Component\HttpKernel\HttpCache\Store;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

require_once __DIR__.'/../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();

$containerBuilder->register('context', RequestContext::class);

$containerBuilder->register('matcher', UrlMatcher::class)
    ->addArgument($routes)
    ->addArgument(new Reference('context'));

$containerBuilder->register('request_stack', RequestStack::class);

$containerBuilder->register('router_listener', RouterListener::class)
    ->addArgument(new Reference('matcher'))
    ->addArgument(new Reference('request_stack'));

$containerBuilder->register('dispatcher', EventDispatcher::class)
    ->addMethodCall('addSubscriber', [new Reference('router_listener')]);

$containerBuilder->register('controller_resolver', ControllerResolver::class);

$containerBuilder->register('framework', Framework::class)
    ->addArgument(new Reference('dispatcher'))
    ->addArgument(new Reference('controller_resolver'));

$containerBuilder->setParameter('cache_directory', '../cache/');

$containerBuilder->register('cache_store', Store::class)
    ->addArgument('%cache_directory%');

$containerBuilder->register('cached_framework', HttpCache::class)
    ->addArgument(new Reference('framework'))
    ->addArgument(new Reference('cache_store'));

return $containerBuilder;
