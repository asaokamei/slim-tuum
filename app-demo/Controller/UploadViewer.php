<?php
namespace Demo\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UploadedFileInterface;
use Tuum\Respond\Controller\PresentByContentTrait;
use Tuum\Respond\Responder;
use Tuum\Respond\Interfaces\PresenterInterface;

class UploadViewer implements PresenterInterface
{
    const MAX_BYTES = 2048; // 2k byte
    use PresentByContentTrait;

    /**
     * @var Responder
     */
    private $responder;

    /**
     * UploadController constructor.
     *
     * @param Responder $responder
     */
    public function __construct($responder)
    {
        $this->responder = $responder;
    }

    /**
     * @return ResponseInterface
     */
    protected function html()
    {
        $data = $this->getViewData()->getData();
        if (array_key_exists('upload', $data)) {
            $upload = $data['upload'];
            $this->setUpMessage($data['upload']);
            return $this->view()
                        ->render('upload', [
                            'upload'  => $upload,
                            'maxSize' => self::MAX_BYTES,
                        ]);
        }
        return $this->view()
                    ->setSuccess('Please upload a file (max 512 byte). ')
                    ->render('upload', [
                        'maxSize' => self::MAX_BYTES,
                    ]);
    }

    /**
     * @param UploadedFileInterface $upload
     */
    private function setUpMessage($upload)
    {
        $viewData = $this->getViewData();
        $viewData->setData('upload', $upload);
        $error_code = $upload->getError();
        if (!$error_code) {
            $viewData->setSuccess('successfully uploaded a file!');
            return;
        }
        if ($error_code === UPLOAD_ERR_NO_FILE) {
            $viewData->setError('please uploaded a file');
        } elseif ($error_code === UPLOAD_ERR_FORM_SIZE) {
            $viewData->setError('uploaded file size too large!');
        } elseif ($error_code === UPLOAD_ERR_INI_SIZE) {
            $viewData->setError('uploaded file size too large!');
        } elseif ($error_code !== UPLOAD_ERR_OK) {
            $viewData->setError('uploading failed!');
        } else {
            $viewData->setError('uploaded a file');
        }
    }
}