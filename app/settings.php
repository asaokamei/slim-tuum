<?php
use Tuum\Builder\Builder;

/** @var Builder $builder */

return $builder->getAll() + [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/templates',
        ],
        // Monolog settings
        'logger' => [
            'name' => 'app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : $builder->getVarDir() . '/logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
    ],
];