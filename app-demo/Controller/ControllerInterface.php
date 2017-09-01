<?php
namespace Demo\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface ControllerInterface
 * 
 * Define __invoke for controller classes.
 * The routeArguments are stored as $request's query parameter. 
 *
 * @package Demo\Controller
 */
interface ControllerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @return null|ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response);
}