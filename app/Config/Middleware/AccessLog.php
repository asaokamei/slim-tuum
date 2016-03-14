<?php
namespace App\Config\Middleware;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

/**
 * Class AccessLog
 *
 */
class AccessLog
{
    /**
     * @var LoggerInterface The router container
     */
    private $logger;

    /**
     * Set the LoggerInterface instance.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param ServerRequestInterface $request
     * @return string
     */
    private static function getClientIp(ServerRequestInterface $request)
    {
        $server  = $request->getServerParams();
        $clineIP = isset($server['REMOTE_ADDR']) && filter_var($server['REMOTE_ADDR'],
            FILTER_VALIDATE_IP) ? $server['REMOTE_ADDR'] : '';

        return $clineIP;
    }

    /**
     * Execute the middleware.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable               $next
     * @return ResponseInterface
     * @throws Exception
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $time1 = microtime(true);
        /** @var ResponseInterface $response */
        try {

            $response = $next($request, $response);
            
        } catch (Exception $e) {
            $message = $this->format($request, $response, $time1);
            $this->logger->critical($message, ['exception' => $e, 'trace' => $e->getTrace()]);
            throw $e;
        }
        $message = $this->format($request, $response, $time1);
        if ($response->getStatusCode() >= 400 && $response->getStatusCode() < 600) {
            $this->logger->error($message);
        } else {
            $this->logger->info($message);
        }

        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param float                  $time1
     * @return string
     */
    private function format($request, $response, $time1)
    {
        $formats   = [];
        $formats[] = $request->getMethod();
        $formats[] = '"' . $request->getUri()->getPath() . '"';
        $formats[] = $response->getReasonPhrase() . '(' . $response->getStatusCode() . ')';
        $formats[] = sprintf('%0.2f kbyte', $response->getBody()->getSize() / 1024);
        $formats[] = sprintf('%0.2f msec', (microtime(true) - $time1) * 1000);
        
        return implode(', ', $formats);
    }
}