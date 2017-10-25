<?php

use Demo\Controller\DocumentMap;
use Demo\Controller\JumpController;
use Demo\Controller\LoginController;
use Demo\Controller\PaginationController;
use Demo\Controller\UploadController;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Route;
use Slim\Router;
use Tuum\Form\Lists\Lists;
use Tuum\Respond\Responder;

/** @var App $app */

// Routes
$app->get('/', function (ServerRequestInterface $request, $response, $args) {
    return $this->responder->view($request, $response)->render('index', $args);
})->setName('home');

$app->get('/toHome', function (ServerRequestInterface $request, $response) {
    return $this->responder->redirect($request, $response)
                           ->setSuccess('redirected to "HOME"')
                           ->toRoute('home');
});

$app->get('/back', function (ServerRequestInterface $request, $response) {
    return $this->responder->redirect($request, $response)
                           ->setSuccess('redirected back to "REFERER"')
                           ->toReferrer();
});

$app->get('/forms', function (ServerRequestInterface $request, $response) {
    return $this->responder->view($request, $response)->render('forms');
})->setName('forms');

$app->get('/bad', function (ServerRequestInterface $request, $response) {
    return $this->responder->view($request, $response)->render('bad');
})->setName('bad');

$app->get('/throw', function () {
    throw new \BadMethodCallException('catch me if you can.');
});

$app->get('/info', function (ServerRequestInterface $request, $response) {
    return $this->responder->view($request, $response)->asObContents(function () {
        phpinfo();
    });
});

$app->group('/sample', function() {
    
    $this->any('/jump', JumpController::class)->setName('jump');
    $this->any('/upload', UploadController::class)->setName('upload');
    $this->any('/paginate', PaginationController::class)->setName('paginate');
    $this->any('/{name}', function (ServerRequestInterface $request, $response) {
        $this->responder->view($request, $response)->asContents("<h1>{$request->getAttribute('name')}</h1>");
    })->setName('hello');
    
})->add(function(ServerRequestInterface $req, $res, $next) {
    /**
     * middleware sample
     */
    /** @var Route $route */
    $route = $req->getAttribute('route');
    $args = $route->getArguments();
    if (isset($args['name'])) {
        $req = $req->withAttribute('name', $args['name'] . ' :middleware');
    }
    return $next($req, $res);
});

/**
 * jump and jumper to see the redirection and parameter in flash
 */
$app->any('/login', LoginController::class);

/**
 * document map
 */
$app->any('/docs/{contents}', DocumentMap::class);
$app->any('/docs/', DocumentMap::class);
$app->any('/docs', DocumentMap::class);
