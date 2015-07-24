<?php
namespace Tuum\Slimmed;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tuum\Respond\Controller\ControllerTrait;

abstract class AbstractController
{
    use ControllerTrait;
    
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     * @return null|ResponseInterface
     */
    public function invokeController(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        return $this->invokeController($request, $response);
    }
}