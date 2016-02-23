<?php
/**
 * This script tests middleware of Slim 3.x micro-framework.
 * middleware #1, #2, and #G will be displayed as response.
 */
require dirname(__DIR__).'/vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Req;
use Psr\Http\Message\ResponseInterface as Res;

$app = new Slim\App();

/**
 * middleware #1
 */
$app->add(function(Req $req, Res $res, $next) {
    $res->getBody()->write("middleware #1\n");
    return $next($req, $res);
});

/**
 * middleware #2
 */
$app->add(function(Req $req, Res $res, $next) {
    $res->getBody()->write("middleware #2\n");
    return $next($req, $res);
});

/**
 * normal response
 */
$app->get('/hello/{name}', function (Req $request, Res $response, $args) {
    $response->getBody()->write("Hello, ".$args['name']);

    return $response->withHeader('Content-Type', 'text/plain');
});

/**
 * group with middleware #G.
 */
$app->group( '/grouped', function() {
    /** normal response. */
    $this->get('', function(Req $req, Res $res) {
        $res->getBody()->write('grouped top');
        return $res->withHeader('Content-Type', 'text/plain');
    });
})->add(function(Req $req, Res $res, $next) {
    $res->getBody()->write("middleware #G\n");
    return $next($req, $res);
});
$app->run();
