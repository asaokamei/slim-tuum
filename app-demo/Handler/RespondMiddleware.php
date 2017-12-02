<?php
namespace Demo\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tuum\Respond\Responder;

class RespondMiddleware
{
    const CSRF_TOKEN   = '_token';
    const METHOD_TOKEN = '_method';

    /**
     * @var Responder
     */
    private $responder;

    /**
     * CsRfMiddleware constructor.
     *
     * @param Responder $responder
     */
    public function __construct(Responder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable               $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        $this->responder->setResponse($response);
        if (!$this->validateCsRfToken($request)) {
            return $this->responder->error($request, $response)->forbidden();
        }
        $request = $this->handleMethodToken($request);
        $response = $next($request, $response);
        $this->handleReferrer($request, $response);
        
        return $response;
    }
    
    /**
     * @param ServerRequestInterface $request
     * @return ServerRequestInterface|static
     */
    private function handleMethodToken(ServerRequestInterface $request)
    {
        if (isset($request->getParsedBody()[self::METHOD_TOKEN])) {
            $request = $request->withMethod($request->getParsedBody()[self::METHOD_TOKEN]);
        }
        return $request;
    }

    /**
     * @param ServerRequestInterface $request
     * @return bool
     */
    private function validateCsRfToken(ServerRequestInterface $request)
    {
        if ($request->getMethod() !== 'POST') {
            return true;
        }
        $post = $request->getParsedBody();
        $token = isset($post[self::CSRF_TOKEN]) ? $post[self::CSRF_TOKEN] : '';
        if (!$this->responder->session()->validateToken($token)) {
            return false;
        }
        return true;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     */
    private function handleReferrer(ServerRequestInterface $request, ResponseInterface $response)
    {
        if ($request->getMethod() !== 'GET') {
            return;
        }
        if ($response->getStatusCode() === 200) {
            $this->responder
                ->session()
                ->set(Responder\Redirect::REFERRER, $request->getUri()->__toString());
        }
    }
}