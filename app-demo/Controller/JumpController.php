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
        $this->view()
            ->setData([
                'jumped' => 'original text',
                'date'   => date('Y-m-d'),
                'gender' => 3,
                'movie'  => [1, 2, 3],
                'happy'  => 'happy',
            ]);
        
        return $this->showForm();
    }

    /**
     * @return mixed
     */
    private function showForm()
    {
        return $this->view()->render('jump');
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

            ->setError('showing validation errors.')
            
            // set form input values.
            ->setInput($this->getPost())

            // set validation errors.
            ->setInputErrors([
                'jumped' => 'redirected error message',
                'date'   => 'your date',
                'gender' => 'your gender',
                'movie'  => 'selected movie',
                'happy'  => 'be happy!'
            ]);

        if ($this->getPost('_redirect')) {
            return $this->redirect()
                ->setError('redirected back!') // set error message.
                ->toPath('jump');
        }
        $this->view()
            ->setError('redrawn form!');

        return $this->showForm();
    }

}