<?php
namespace App\Demo\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\UploadedFile;
use Tuum\Respond\Interfaces\PresenterInterface;
use Tuum\Respond\Responder;

class UploadController
{
    /**
     * @var PresenterInterface
     */
    private $viewer;

    /**
     * @var Responder
     */
    private $responder;

    /**
     * UploadController constructor.
     *
     * @param PresenterInterface $viewer
     * @param Responder          $responder
     */
    public function __construct($viewer, $responder)
    {
        $this->viewer = $viewer;
        $this->responder = $responder;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $method = $request->getMethod() === 'POST' ? 'onPost' : 'onGet';
        return $this->$method($request, $response);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @return ResponseInterface
     */
    public function onGet(ServerRequestInterface $request, ResponseInterface $response)
    {
        $viewData = $this->responder->getViewData();
        return $this->viewer->__invoke($request, $response, $viewData);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @return ResponseInterface
     */
    public function onPost(ServerRequestInterface $request, ResponseInterface $response)
    {
        /** @var UploadedFile $upload */
        $uploaded = $request->getUploadedFiles();
        $upload   = $uploaded['up'][0];
        $viewData = $this->responder->getViewData()
            ->setData('isUploaded', true)
            ->setData('dump', print_r($uploaded, true))
            ->setData('upload', $upload)
            ->setData('error_code', $upload->getError());
        return $this->viewer->__invoke($request, $response, $viewData); // callable
    }
}