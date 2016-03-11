<?php
namespace App\Config\Define;

use Interop\Container\ContainerInterface;
use Tuum\Respond\Helper\ResponderBuilder;
use Tuum\Respond\Service\ErrorView;
use Tuum\Respond\Service\SessionStorage;
use Tuum\Respond\Service\TwigViewer;

class ResponderFactory
{
    /**
     * @param ContainerInterface $c
     * @return \Tuum\Respond\Responder
     */
    public function __invoke(ContainerInterface $c)
    {
        $stream    = TwigViewer::forge($c->get('twig-dir'), [
            'cache' => $c->get('root-dir').'/vars/twigs',
        ]);
        $errors    = ErrorView::forge($stream, [
            'default' => 'errors/error',
            'status'  => [
                '404' => 'errors/notFound',
                '403' => 'errors/forbidden',
            ],
        ]);

        return ResponderBuilder::withServices($stream, $errors, 'layouts/contents')
            ->withSession(SessionStorage::forge('slim-tuum'));
    }
}