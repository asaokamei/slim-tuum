<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Tuum\Respond\Respond;

/**
 * @var Slim\App $app
 */
$app->get('/', function (Request $request, Response $response, $args) {
    return $response->write('
<h1>hello Slim 3</h1>
<ul>
    <li><a href="/respond" >tuum</a></li>
</ul>
    ');
});

$app->any('/respond{pathInfo:[-/_0-9a-zA-Z]*}',
    function (Request $request, Response $response, $args) {
        return Respond::view($request, $response)
            ->with('args', $args)
            ->asView('respond');
    });

$app->get('/hello/{name}',
    function (Request $request, Response $response, $args) {
        $response->write("Hello, " . $args['name']);
        return $response;
    })->setName('hello');


/** @var Container $c */
$app->get('/class[/{pathInfo:.*}]', SampleCtrl::class)->setName('class');

