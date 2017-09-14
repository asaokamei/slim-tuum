<?php
namespace Demo\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\UploadedFile;
use Tuum\Respond\Controller\DispatchByMethodTrait;
use Tuum\Respond\Responder;

class UploadController implements ControllerInterface
{
    use DispatchByMethodTrait;

    /**
     * UploadController constructor.
     *
     * @param Responder          $responder
     */
    public function __construct($responder)
    {
        $this->setResponder($responder);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @return null|ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        return $this->dispatch($request, $response);
    }
    
    /**
     * @return ResponseInterface
     */
    public function onGet()
    {
        return $this->call(UploadViewer::class);
    }

    /**
     * @return ResponseInterface
     */
    public function onPost()
    {
        /** @var UploadedFile $upload */
        $uploaded = $this->getRequest()->getUploadedFiles();
        $upload   = $uploaded['up'][0];
        return $this->call(UploadViewer::class, [
            'upload' => $upload,
        ]);
    }
}