<?php
use Tuum\Builder\AppBuilder;

/**
 * creating a Slim3 Application, $app.
 */

session_start();
$builder = new AppBuilder(__DIR__.'/config', dirname(__DIR__).'/var');

/**
 * configure with config/builder.php
 */
$builder->set('twig-dir', __DIR__.'/Demo/twigs');
$builder->execConfig('builder');

/**
 * import routes
 */
$builder->execute(__DIR__.'/Demo/routes');

/**
 * done construction.
 */
return $builder->app;