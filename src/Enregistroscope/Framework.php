<?php


namespace Enregistroscope;


use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

class Framework implements HttpKernelInterface
{
    /**
     * @var UrlMatcherInterface
     */
    private $urlMatcher;

    /**
     * @var ControllerResolverInterface
     */
    private $controllerResolver;

    /**
     * @var ArgumentResolverInterface
     */
    private $argumentResolver;

    /**
     * Framework constructor.
     * @param UrlMatcherInterface $urlMatcher
     * @param ControllerResolverInterface $controllerResolver
     * @param ArgumentResolverInterface $argumentResolver
     */
    public function __construct(UrlMatcherInterface $urlMatcher, ControllerResolverInterface $controllerResolver, ArgumentResolverInterface $argumentResolver)
    {
        $this->urlMatcher = $urlMatcher;
        $this->controllerResolver = $controllerResolver;
        $this->argumentResolver = $argumentResolver;
    }

    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true): Response
    {
        $response = new Response();

        $path = $request->getPathInfo();

        try {
            $request->attributes->add($this->urlMatcher->match($path));
            $controller = $this->controllerResolver->getController($request);
            $arguments = $this->argumentResolver->getArguments($request, $controller);
            $response = call_user_func_array($controller, $arguments);
        } catch (ResourceNotFoundException $e) {
            $response->setStatusCode(404);
            $response->setContent('Not found');
        } catch (Exception $e) {
            $response->setStatusCode(500);
            $response->setContent('Something went wrong :/');
        }

        return $response;
    }
}
