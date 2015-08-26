<?php
use Psr\Http\Message\ResponseInterface;
use Slim\Http\UploadedFile;
use Tuum\Respond\Controller\DispatchByMethodTrait;
use Tuum\Slimmed\AbstractController;

/**
 * Class UploadController
 *
 * a sample controller class for uploading a file. 
 */
class UploadController extends AbstractController
{
    use DispatchByMethodTrait;
    
    /**
     * @return ResponseInterface
     */
    public function onGet()
    {
        return $this->view()
            ->withReqAttribute('csrf_name', 'csrf_value')
            ->asView('upload');
    }

    /**
     * @return ResponseInterface
     */
    public function onPost()
    {
        $responder = $this->redirect();
        $uploaded  = $this->getRequest()->getUploadedFiles();
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
    }
}