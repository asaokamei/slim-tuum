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
$builder->configure('builder');
$builder->configure('build-twig');

/**
 * import routes
 */
$builder->evaluate(__DIR__.'/Demo/routes');

/**
 * done construction.
 */
return $builder->app;