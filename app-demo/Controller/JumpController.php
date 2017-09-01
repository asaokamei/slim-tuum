<?php
namespace Demo\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tuum\Respond\Controller\DispatchByMethodTrait;
use Tuum\Respond\Responder;

class JumpController implements ControllerInterface
{
    use DispatchByMethodTrait;
    
    /**
     * @param Responder $responder
     */
    public function __construct(Responder $responder)
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
     * show upload form.
     *
     * @return ResponseInterface
     */
    public function onGet()
    {
        return $this->view()
            ->render('jump');
    }

    /**
     * take care the uploaded file.
     * show the upload form with uploaded file information.
     *
     * @return ResponseInterface
     */
    public function onPost()
    {
        $this->getViewData()
            ->setSuccess('redirected back!')
            ->setInput($this->getPost())
            ->setInputErrors(['jumped' => 'redirected error message']);
        return $this->redirect()
            ->toPath('jump');
    }

}