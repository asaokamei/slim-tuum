<?php
namespace Tuum\Slimmed;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tuum\Respond\RequestHelper;
use Tuum\Respond\Responder;
use Tuum\Respond\Service\ErrorView;
use Tuum\Respond\Service\SessionStorage;
use Tuum\Respond\Service\TuumViewer;
use Tuum\Respond\Service\TwigStream;
use Tuum\Respond\Service\TwigViewer;
use Tuum\Respond\Service\ViewerInterface;
use Tuum\Respond\Service\ViewStream;
use Tuum\Respond\Service\ViewStreamInterface;

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
     * @return static
     */
    public static function forge($viewDir, $content_file = null, $error_options = [])
    {
        $stream = TuumViewer::forge($viewDir);
        return self::build($stream, $content_file, $error_options);
    }

    /**
     * @param string     $twigRoot
     * @param array      $twigOptions
     * @param string     $content_file
     * @param array      $error_options
     * @return static
     */
    public static function forgeTwig(
        $twigRoot,
        array $twigOptions,
        $content_file = null,
        $error_options = []
    ) {
        $stream = TwigViewer::forge($twigRoot, $twigOptions);
        return self::build($stream, $content_file, $error_options);
    }

    /**
     * @param ViewerInterface $stream
     * @param string              $content_file
     * @param array               $errors
     * @return static
     */
    private static function build($stream, $content_file, $errors)
    {
        // check options.
        $errors += [
            'default' => 'errors/error',
            'status'  => [],
            'handler' => false,
        ];
        // construct responders and its dependent objects.
        $errors  = ErrorView::forge($stream, $errors);
        $respond = Responder::build($stream, $errors, $content_file);
        $self    = new static($respond);

        return $self;
    }

    /**
     * save session and responder as $request's attribute.
     *
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