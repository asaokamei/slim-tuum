<?php
namespace App\Config\Define;

use Interop\Container\ContainerInterface;
use Slim\Csrf\Guard;
use Slim\Http\Request;
use Slim\Http\Response;
use Tuum\Respond\Responder;

class GuardConfig
{
    /**
     * @param ContainerInterface $c
     * @return Guard
     */
    public function __invoke(ContainerInterface $c)
    {
        $responder = $c->get(Responder::class);
        $guard = new Guard();
        $guard->setFailureCallable(function(Request $request, Response $response) use($responder) {
            return $responder->error($request, $response)->forbidden();
        });
        return $guard;
    }
}