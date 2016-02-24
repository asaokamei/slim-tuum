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
        return $this->responder->view($request, $response)
            ->withReqAttribute('csrf_name', 'csrf_value')
            ->asView('jump');
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
        return $this->responder->redirect($request, $response)
            ->withSuccess('redirected back!')
            ->withInputData(['jumped' => 'redirected text'])
            ->withInputErrors(['jumped' => 'redirected error message'])
            ->toPath('jump');
    }

}