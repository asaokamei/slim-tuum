<?php
namespace Tuum\Slimmed;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tuum\Respond\RequestHelper;
use Tuum\Respond\Responder;
use Tuum\Respond\Service\ErrorView;
use Tuum\Respond\Service\SessionStorage;
use Tuum\Respond\Service\TwigStream;
use Tuum\Respond\Service\ViewStream;
use Tuum\Respond\Service\ViewStreamInterface;

class TuumStack
{
    /**
     * @var Responder
     */
    private $responder;

    /**
     * @var SessionStorage
     */
    private $session;

    /**
     * @param Responder      $responder
     * @param SessionStorage $session
     */
    public function __construct(Responder $responder, SessionStorage $session)
    {
        $this->responder = $responder;
        $this->session   = $session;
    }

    /**
     * @param string     $viewDir
     * @param string     $content_file
     * @param array      $error_options
     * @param null|array $cookie
     * @return static
     */
    public static function forge($viewDir, $content_file = null, $error_options = [], $cookie = null)
    {
        $stream  = ViewStream::forge($viewDir);
        return self::build($stream, $content_file, $error_options, $cookie);
    }

    /**
     * @param string     $twigRoot
     * @param array      $twigOptions
     * @param string     $content_file
     * @param array      $error_options
     * @param null|array $cookie
     * @return static
     */
    public static function forgeTwig($twigRoot, array $twigOptions, $content_file = null, $error_options = [], $cookie = null)
    {
        $stream  = TwigStream::forge($twigRoot, $twigOptions);
        return self::build($stream, $content_file, $error_options, $cookie);
    }

    /**
     * @param ViewStreamInterface $stream
     * @param string              $content_file
     * @param array               $errors
     * @param array|null          $cookie
     * @return static
     */
    private static function build($stream, $content_file, $errors, $cookie)
    {
        // check options.
        $cookie = is_null($cookie) ? $_COOKIE: $cookie;
        $errors += [
            'default' => 'errors/error',
            'status'  => [],
            'handler' => false,
        ];
        // construct responders and its dependent objects.
        $session = SessionStorage::forge('slim-tuum', $cookie);
        $errors  = ErrorView::forge($stream, $errors);
        $respond = Responder::build($stream, $errors, $content_file)->withSession($session);
        $self    = new static($respond, $session);

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
        $request = RequestHelper::withSessionMgr($request, $this->session);
        $request = $request->withAttribute(Responder::class, $this->responder);
        return $next($request, $response);
    }
}