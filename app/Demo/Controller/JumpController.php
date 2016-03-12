<?php
namespace App\Demo\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tuum\Respond\Responder;

class JumpController
{
    /**
     * @var Responder
     */
    private $responder;

    /**
     * @param Responder $responder
     */
    public function __construct(Responder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * show upload form.
     *
     * @param ServerRequestInterface  $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function onGet(ServerRequestInterface $request, ResponseInterface $response)
    {
        $viewData = $this->responder->getViewData();
        return $this->responder->view($request, $response)
            ->render('jump', $viewData);
    }

    /**
     * take care the uploaded file.
     * show the upload form with uploaded file information.
     *
     * @param ServerRequestInterface  $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function onPost(ServerRequestInterface $request, ResponseInterface $response)
    {
        $viewData = $this->responder->getViewData()
            ->setSuccess('redirected back!')
            ->setInputData($request->getParsedBody())
            ->setInputErrors(['jumped' => 'redirected error message']);
        return $this->responder->redirect($request, $response)
            ->toPath('jump', $viewData);
    }

}