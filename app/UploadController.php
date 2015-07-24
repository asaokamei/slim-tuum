<?php
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;
use Tuum\Respond\Respond;

/**
 * Class UploadController
 *
 * a sample controller class for uploading a file. 
 */
class UploadController
{
    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        $method = 'on'.ucwords($request->getMethod());
        return $this->$method($request, $response);
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @return ResponseInterface
     */
    public function onGet(Request $request, Response $response)
    {
        return Respond::view($request, $response)->asView('upload');
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @return ResponseInterface
     */
    public function onPost(Request $request, Response $response)
    {
        $responder = Respond::view($request, $response);
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
            ->asView('upload');
    }
}