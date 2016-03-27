<?php
namespace App\Config\Handlers;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tuum\Respond\Responder;

class NotFoundHandler
{
    /**
     * @var Responder
     */
    private $responder;

    /**
     * @param ContainerInterface $c
     */
    public function __construct(ContainerInterface $c)
    {
        $this->responder = $c->get(Responder::class);
    }

    /**
     * @param ServerRequestInterface $req
     * @param ResponseInterface      $res
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $req, ResponseInterface $res)
    {
        return $this->responder->error($req, $res)->notFound();
    }
}