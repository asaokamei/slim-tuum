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
    public static function forge(ContainerInterface $c)
    {
        $self = new self;
        $self->responder = $c->get(Responder::class);
        $guard = new Guard();
        $guard->setFailureCallable([$self, 'forbidden']);
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