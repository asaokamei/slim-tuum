<?php
namespace Demo\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tuum\Respond\Controller\DispatchByMethodTrait;
use Tuum\Respond\Responder;

class LoginController implements ControllerInterface
{
    use DispatchByMethodTrait;

    /**
     * @param Responder $responder
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
    public function onPost()
    {
        $name = $this->getPost('name');
        if (!$name) {
            return $this->redirect()
                        ->setError('please enter login name')
                        ->toPath('/');
        }
        $this->session()->set('login.name', $name);
        return $this->redirect()
                    ->setSuccess('logged in!')
                    ->toPath('/');
    }

    /**
     * @return ResponseInterface
     */
    public function onDelete()
    {
        if (!$this->session()->get('login.name')) {
            return $this->redirect()
                        ->setError('not logged in!')
                        ->toPath('/');
        }
        $this->session()->set('login.name', null);
        return $this->redirect()
                    ->setSuccess('logged out!')
                    ->toPath('/');
    }
}
