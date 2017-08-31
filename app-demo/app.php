<?php

use Tuum\Builder\Builder;

$builder = Builder::forge(__DIR__, dirname(__DIR__) . '/var', true);
$settings = $builder->load('settings');
$app = new \Slim\App($settings);
$builder->set('app', $app);

$builder->load('dependencies');
$builder->load('middleware');
$builder->load('routes');

return $app;