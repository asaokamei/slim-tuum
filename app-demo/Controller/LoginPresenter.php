<?php
namespace Demo\Controller;

use Psr\Http\Message\ResponseInterface;
use Tuum\Respond\Controller\PresenterTrait;
use Tuum\Respond\Interfaces\PresenterInterface;
use Tuum\Respond\Responder;

class LoginPresenter implements PresenterInterface
{
    use PresenterTrait;

    /**
     * @param Responder $responder
     */
    public function __construct($responder)
    {
        $this->setResponder($responder);
    }

    /**
     * @param Responder $responder
     * @return LoginPresenter
     */
    public static function forge($responder)
    {
        $self            = new self($responder);

        return $self;
    }

    /**
     * renders $view and returns a new $response.
     *
     * @return ResponseInterface
     */
    public function dispatch()
    {
        $login = $this->session()->get('login.name');
        if ($login) {
            return $this->view()
                ->render('layouts/UserHeaderLogIn', ['login' => $login]);
        }

        return $this->view()
            ->render('layouts/UserHeaderLoginForm');
    }
}