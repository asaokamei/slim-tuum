<?php
use Tuum\Builder\Builder;

$builder = Builder::forge(__DIR__, dirname(__DIR__) . '/var', false);
$builder->loadEnv();
$settings = $builder->load('settings');
$app = new \Slim\App($settings);
$builder->setApp($app);

$builder->load('dependencies');
$builder->load('middleware');
$builder->load('routes');

return $app;