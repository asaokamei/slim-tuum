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
     * @return \Tuum\Respond\Responder
     */
    public function __invoke()
    {
        $twig_dir  = dirname(dirname(__DIR__)).'/Demo/twigs';
        $stream    = TwigViewer::forge($twig_dir, []);
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