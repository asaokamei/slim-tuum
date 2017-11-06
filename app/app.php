<?php
use Tuum\Builder\Builder;

/** @var Builder $builder */
$builder->loadEnv();

$settings = $builder->load('settings');
$app = new \Slim\App($settings);
$builder->setApp($app);

$builder->load('dependencies');
$builder->load('middleware');
$builder->load('routes');

return $app;
