<?php
namespace App\Config\Define;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tuum\Respond\Respond;

class NotFoundFactory
{
    public function __invoke()
    {
        return function (ServerRequestInterface $req, ResponseInterface $res) {
            return Respond::error($req, $res)->notFound();
        };
    }
}