<?php
namespace Tuum\Slimmed;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tuum\Respond\Responder;
use Tuum\Respond\Service\ErrorView;
use Tuum\Respond\Service\SessionStorage;
use Tuum\Respond\Service\ViewStream;

class TuumStack
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
     * @param string     $viewDir
     * @param string     $content_file
     * @param array      $error_options
     * @param null|array $cookie
     * @return static
     */
    public static function forge($viewDir, $content_file, $error_options = [], $cookie = null)
    {
        // check options.
        $cookie  = is_null($cookie) ?: $_COOKIE;
        $error_options += [
            'default' => 'errors/error',
            'status'  => [],
            'handler' => false,
        ];
        // construct responders and its dependent objects.
        $session = SessionStorage::forge('slim-tuum', $cookie);
        $stream  = ViewStream::forge($viewDir);
        $errors  = ErrorView::forge($stream, $error_options);
        $respond = Responder::build($stream, $errors, $content_file)->withSession($session);
        $self    = new static($respond);
        return $self;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable               $next
     * @return mixed
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ) {
        $request = $request->withAttribute(Responder::class, $this->responder);
        return $next($request, $response);
    }
}