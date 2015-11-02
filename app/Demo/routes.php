<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;
use Tuum\Respond\Respond;
use Tuum\Slimmed\DocumentMap;

/**
 * routes
 * 
 * @var Slim\App $app
 */

/**
 * top page
 */
$app->get('/', function (Request $request, Response $response) {
    return Respond::view($request, $response)->asView('index');
});

/**
 * jump and jumper to see the redirection and parameter in flash
 */
$app->get('/jump', function ($request, Response $response) {
    return Respond::view($request, $response)
        ->withReqAttribute('csrf_name', 'csrf_value')
        ->asView('jump');
});

$app->post('/jump', function (Request $request, Response $response) {
    return Respond::redirect($request, $response)
        ->withMessage('redirected back!')
        ->withInputData(['jumped' => 'redirected text'])
        ->withInputErrors(['jumped' => 'redirected error message'])
        ->toPath('jump');
});

/**
 * check asContents
 */
$app->get('/content', function(Request $request, Response $response) {
    return Respond::view($request, $response)
        ->asContents('<h1>Contents</h1><p>this is a string content in a layout file</p>');
});

/**
 * check uncaught exception
 */
$app->get('/throw', function() {
    throw new \RuntimeException('This page throws a RuntimeException!');
});

/**
 * file upload example
 */
$app->get('/upload', function(Request $request, Response $response) {
    return Respond::view($request, $response)
        ->withReqAttribute('csrf_name', 'csrf_value')
        ->asView('upload');
});

$app->post('/upload', function(Request $request, Response $response) {
    $responder = Respond::redirect($request, $response);
    $uploaded  = $request->getUploadedFiles();
    $responder
        ->with('isUploaded', true)
        ->with('dump', print_r($uploaded, true));
    /** @var UploadedFile $upload */
    $upload = $uploaded['up'][0];
    $responder->with('upload', $upload);

    if ($upload->getError()===UPLOAD_ERR_NO_FILE) {
        $responder->withErrorMsg('please uploaded a file');
    } elseif ($upload->getError()===UPLOAD_ERR_FORM_SIZE || $upload->getError()===UPLOAD_ERR_INI_SIZE) {
        $responder->withErrorMsg('uploaded file size too large!');
    } elseif ($upload->getError()!==UPLOAD_ERR_OK) {
        $responder->withErrorMsg('uploading failed!');
    } else {
        $responder->withMessage('uploaded a file');
    }
    return $responder
        ->toPath('/upload');
});

/**
 * FileMap for Document files
 */
$app->getContainer()[DocumentMap::class] = function() {
    return DocumentMap::forge(dirname(__DIR__).'/docs', dirname(dirname(__DIR__)).'/vars/markUp');
};
$app->any('/docs/{pathInfo:.*}', DocumentMap::class);