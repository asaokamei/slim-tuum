<?php

use Slim\Csrf\Guard;
use Tuum\Respond\Responder;
use Tuum\Slimmed\TuumStack;

$container = $app->getContainer();

/**
 * C.S.R.F. guardian by Slim. 
 * 
 * @return Guard
 */
$app->add($container['csrf']);

/**
 * use Tuum/Responder with Twig as renderer.
 */
$app->add(
    new TuumStack($container->get(Responder::class))
);

