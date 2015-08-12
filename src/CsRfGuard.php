<?php
namespace Tuum\Slimmed;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tuum\Respond\RequestHelper;
use Tuum\Respond\Respond;
use Tuum\Respond\Responder;

class CsRfGuard implements ServiceProviderInterface
{
    /**
     * @var string
     */
    private $token_name = '_token';

    /**
     * @var array
     */
    private $methods = ['POST', 'PUT', 'PATCH', 'DELETE'];

    /**
     * @param null|string $tokenName
     * @param null|array  $methods
     */
    public function __construct($tokenName = null, $methods = null)
    {
        if (!is_null($tokenName)) {
            $this->token_name = $tokenName;
        }
        if (!is_null($methods)) {
            $this->methods = $methods;
        }
    }
    
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable               $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $token   = RequestHelper::getSessionMgr($request)->getToken();
        $request = $request->withAttribute($this->token_name, $token);
        if (in_array($request->getMethod(), $this->methods)) {
            return $this->validate($request, $response, $next);
        }

        return $next($request, $response);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable               $next
     * @return ResponseInterface
     */
    public function validate(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $post = $request->getParsedBody();
        if (!isset($post[$this->token_name]) || !$post[$this->token_name]) {
            return Respond::error($request, $response)->forbidden();
        }
        $value = $post[$this->token_name];

        if (!RequestHelper::getSessionMgr($request)->validateToken($value)) {
            return Respond::error($request, $response)->forbidden();
        }

        return $next($request, $response);
    }

    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $pimple['csrf'] = $this;
    }
}