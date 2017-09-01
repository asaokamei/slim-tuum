<?php
namespace Demo\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\UploadedFile;
use Tuum\Respond\Controller\DispatchByMethodTrait;
use Tuum\Respond\Interfaces\PresenterInterface;
use Tuum\Respond\Responder;

class UploadController
{
    use DispatchByMethodTrait;

    /**
     * UploadController constructor.
     *
     * @param Responder          $responder
     */
    public function __construct($responder)
    {
        $this->responder = $responder;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     * @return null|ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $args   += $request->getQueryParams();
        $request = $request->withQueryParams($args);
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
        $this->responder->getViewData()
            ->setData('isUploaded', true)
            ->setData('dump', print_r($uploaded, true))
            ->setData('upload', $upload)
            ->setData('error_code', $upload->getError());
        return $this->call(UploadViewer::class);
    }
}