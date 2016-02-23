<?php
use App\Config\Define\GuardConfig;
use App\Config\Define\NotFoundFactory;
use App\Config\Define\ResponderFactory;
use Tuum\Builder\AppBuilder;
use Tuum\Respond\Responder;

/** @var AppBuilder $builder */

/**
 * settings for Slim's Container.
 */
$setting = [
    'settings'        => [],
    'notFoundHandler' => new NotFoundFactory(),
    'csrf'            => new GuardConfig(),
    Responder::class  => new ResponderFactory(),
];

if ($builder->debug) {
    $setting['settings']['displayErrorDetails'] = true;
}

$builder->set('setting', $setting);