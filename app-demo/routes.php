<?php

use Demo\Controller\DocumentMap;
use Demo\Controller\JumpController;
use Demo\Controller\LoginController;
use Demo\Controller\PaginationController;
use Demo\Controller\UploadController;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Route;

/** @var App $app */

// Routes
$app->get('/', function (ServerRequestInterface $request, $response, $args) {
    return $this->responder->view($request, $response)->render('index', $args);
})->setName('home');

$app->get('/toHome', function (ServerRequestInterface $request) {
    return $this->responder->redirect($request)
                           ->setSuccess('redirected to "HOME"')
                           ->toRoute('home');
});

$app->get('/back', function (ServerRequestInterface $request) {
    return $this->responder->redirect($request)
                           ->setSuccess('redirected back to "REFERER"')
                           ->toReferrer();
});

$app->get('/bad', function (ServerRequestInterface $request) {
    return $this->responder->view($request)->render('bad');
})->setName('bad');

$app->get('/throw', function () {
    throw new \BadMethodCallException('catch me if you can.');
});

$app->group('/sample', function () {
    
    $this->any('/jump', JumpController::class)->setName('jump');
    $this->any('/upload', UploadController::class)->setName('upload');
    $this->any('/paginate', PaginationController::class)->setName('paginate');
    
    $this->get('/forms', function (ServerRequestInterface $request) {
        return $this->responder->view($request)->render('forms');
    })->setName('forms');
    
    $this->get('/info', function (ServerRequestInterface $request) {
        return $this->responder->view($request)->asObContents(function () {
            phpinfo();
        });
    })->setName('phpInfo');
    
    $this->any('/{name}', function (ServerRequestInterface $request) {
        $this->responder->view($request)->asContents("<h1>{$request->getAttribute('name')}</h1>");
    })->setName('hello');

})->add(function (ServerRequestInterface $req, $res, $next) {
        /**
         * middleware sample
         */
        /** @var Route $route */
        $route = $req->getAttribute('route');
        $args = $route->getArguments();
        if (isset($args['name'])) {
            $req = $req->withAttribute('name', $args['name'] . ' :middleware');
        }
        $req = $req->withAttribute('menu-sample', ' active');
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
