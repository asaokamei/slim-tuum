<?php
namespace Demo\Handler;

use Demo\Controller\ControllerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\InvocationStrategyInterface;
use Tuum\Respond\Responder;

/**
 * Class FoundHandler
 *
 * Call controllers without routeArguments.
 * The routeArguments are stored as query in $request.
 *
 * @package Demo\Handler
 */
class NotFoundHandler
{
    /**
     * @var Responder
     */
    private $responder;

    /**
     * @param Responder $responder
     */
    public function __construct(Responder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * Invoke a route callable.
     *
     * @param ServerRequestInterface $request        The request object.
     * @param ResponseInterface      $response       The response object.
     *
     * @return ResponseInterface|string The response from the callable.
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    )     {
        return $this->responder->error($request, $response)->notFound();
    }

}