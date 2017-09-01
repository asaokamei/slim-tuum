<?php
use Demo\Handler\FoundHandler;
use Demo\Handler\NotFoundHandler;
use Psr\Container\ContainerInterface;

return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/templates',
        ],
        // Monolog settings
        'logger' => [
            'name' => 'officers-net',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../var/logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
    ],
    'foundHandler' => function() {return new FoundHandler();},
    'notFoundHandler' => function (ContainerInterface $c) {return new NotFoundHandler($c->get('responder'));}
];