<?php

use Demo\Controller\DocumentMap;
use Demo\Controller\JumpController;
use Demo\Controller\LoginController;
use Demo\Controller\PaginationController;
use Demo\Controller\UploadController;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Tuum\Form\Lists\Lists;

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


/**
 * jump and jumper to see the redirection and parameter in flash
 */
$app->any('/jump', JumpController::class)->setName('jump');
$app->any('/upload', UploadController::class)->setName('upload');
$app->any('/paginate', PaginationController::class)->setName('paginate');
$app->any('/login', LoginController::class);

/**
 * document map
 */
$app->any('/docs/{contents}', DocumentMap::class);
$app->any('/docs/', DocumentMap::class);
$app->any('/docs', DocumentMap::class);
