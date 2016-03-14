<?php
namespace App\Config\Define;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Csrf\Guard;
use Slim\Http\Request;
use Slim\Http\Response;
use Tuum\Respond\Responder;

class GuardConfig
{
    /**
     * @var Responder
     */
    private $responder;

    /**
     * @param ContainerInterface $c
     * @return Guard
     */
    public function __invoke(ContainerInterface $c)
    {
        $this->responder = $c->get(Responder::class);
        $guard = new Guard();
        $guard->setFailureCallable([$this, 'forbidden']);
        return $guard;
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @return ResponseInterface
     */
    public function forbidden(Request $request, Response $response)
    {
        return $this->responder->error($request, $response)->forbidden();
    }
}