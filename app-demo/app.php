<?php
use Tuum\Builder\Builder;

/** @var Builder $settings */
$settings = $builder->get('settings');
$app = new \Slim\App($settings);
$builder->setApp($app);

return $app;
