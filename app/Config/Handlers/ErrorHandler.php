<?php
namespace App\Config\Handlers;

use Exception;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tuum\Respond\Responder;

class ErrorHandler
{
    /**
     * @var Responder
     */
    private $responder;

    /**
     * @param ContainerInterface $c
     * @return callable
     */
    public function __invoke(ContainerInterface $c)
    {
        $this->responder = $c->get(Responder::class);
        return [$this, 'error'];
    }

    /**
     * @param ServerRequestInterface $req
     * @param ResponseInterface      $res
     * @param Exception              $e
     * @return ResponseInterface
     */
    public function error(ServerRequestInterface $req, ResponseInterface $res, $e)
    {
        $viewData = $this->responder->getViewData()->setError('Error.'.$e->getMessage());
        return $this->responder->error($req, $res)->asView(501, $viewData);
    }
}