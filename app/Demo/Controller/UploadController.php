<?php
namespace App\Demo\Controller;

use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;
use Tuum\Respond\Responder;
use Tuum\Respond\Service\ViewData;

class UploadController
{
    /**
     * @var Responder
     */
    private $responder;

    /**
     * @param Responder $responder
     */
    public function __construct($responder)
    {
        $this->responder = $responder;
    }

    /**
     * show upload form.
     *
     * @param Request  $request
     * @param Response $response
     * @return ResponseInterface
     */
    public function onGet(Request $request, Response $response)
    {
        return $this->responder->view($request, $response)
            ->withReqAttribute('csrf_name', 'csrf_value')
            ->asView('upload');
    }

    /**
     * take care the uploaded file.
     * show the upload form with uploaded file information.
     *
     * @param Request  $request
     * @param Response $response
     * @return ResponseInterface
     */
    public function onPost(Request $request, Response $response)
    {
        $uploaded = $request->getUploadedFiles();
        $upload   = $uploaded['up'][0];

        $this->checkUploaded($upload);
        $this->responder = $this->responder->viewData(
            function (ViewData $view) use ($uploaded, $upload) {
                return $view->setData('isUploaded', true)
                    ->setData('dump', print_r($uploaded, true))
                    ->setData('upload', $upload);
            });

        return $this->onGet($request, $response);
    }

    /**
     * @param UploadedFile $upload
     */
    private function checkUploaded(UploadedFile $upload)
    {
        $this->responder = $this->responder->viewData(
            function (ViewData $view) use ($upload) {

                if ($upload->getError() === UPLOAD_ERR_NO_FILE) {
                    $view->error('please uploaded a file');
                } elseif ($upload->getError() === UPLOAD_ERR_FORM_SIZE || $upload->getError() === UPLOAD_ERR_INI_SIZE) {
                    $view->error('uploaded file size too large!');
                } elseif ($upload->getError() !== UPLOAD_ERR_OK) {
                    $view->error('uploading failed!');
                } else {
                    $view->success('uploaded a file');
                }
                return $view;
            });
    }
}