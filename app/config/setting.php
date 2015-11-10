<?php
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tuum\Builder\AppBuilder;
use Tuum\Respond\Respond;

/** @var AppBuilder $builder */

$setting = [
    'settings'        => [],
    'notFoundHandler' => function () {
        return function (ServerRequestInterface $req, ResponseInterface $res) {
            return Respond::error($req, $res)->notFound();
        };
    },

];

if ($builder->debug) {
    $setting['settings']['displayErrorDetails'] = true;
}

$builder->set('setting', $setting);