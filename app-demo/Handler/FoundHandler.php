<?php
namespace Demo\Handler;

use Demo\Controller\ControllerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\InvocationStrategyInterface;

/**
 * Class FoundHandler
 *
 * Call controllers without routeArguments.
 * The routeArguments are stored as query in $request.
 *
 * @package Demo\Handler
 */
class FoundHandler implements InvocationStrategyInterface
{
    /**
     * Invoke a route callable.
     *
     * @param callable               $callable       The callable to invoke using the strategy.
     * @param ServerRequestInterface $request        The request object.
     * @param ResponseInterface      $response       The response object.
     * @param array                  $routeArguments The route's placeholder arguments
     *
     * @return ResponseInterface|string The response from the callable.
     */
    public function __invoke(
        callable $callable,
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $routeArguments
    ) {
        if (is_object($callable) && $callable instanceof ControllerInterface) {
            $routeArguments += $request->getQueryParams();
            $request = $request->withQueryParams($routeArguments);
            return call_user_func($callable, $request, $response);
        }
        
        return call_user_func($callable, $request, $response, $routeArguments);
    }
}