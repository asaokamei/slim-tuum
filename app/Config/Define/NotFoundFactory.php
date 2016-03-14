<?php
namespace App\Config\Define;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tuum\Respond\Responder;

class NotFoundFactory
{
    /**
     * @param ContainerInterface $c
     * @return \Closure
     */
    public function __invoke(ContainerInterface $c)
    {
        $responder = $c->get(Responder::class);
        return function (ServerRequestInterface $req, ResponseInterface $res) use($responder) {
            return $responder->error($req, $res)->notFound();
        };
    }
}