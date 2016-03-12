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
        $setting   = $c->get('settings');
        $stream    = TwigViewer::forge($setting['app-dir'].'/Demo/twigs', [
            'cache' => $setting['var-dir'].'/twigs',
            'auto_reload' => true,
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