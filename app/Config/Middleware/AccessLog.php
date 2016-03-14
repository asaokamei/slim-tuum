<?php
namespace App\Config\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

/**
 * Class AccessLog
 * 
 * @see
 * https://github.com/oscarotero/psr7-middlewares/blob/master/src/Middleware/AccessLog.php
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
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        /** @var ResponseInterface $response */
        $response = $next($request, $response);
        $message = $this->combinedFormat($request, $response);
        if ($response->getStatusCode() >= 400 && $response->getStatusCode() < 600) {
            $this->logger->error($message);
        } else {
            $this->logger->info($message);
        }
        return $response;
    }
    /**
     * Generates a message using the Apache's Common Log format
     * https://httpd.apache.org/docs/2.4/logs.html#accesslog.
     *
     * Note: The user identifier (identd) is ommited intentionally
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     *
     * @return string
     */
    private static function commonFormat(ServerRequestInterface $request, ResponseInterface $response)
    {
        $clineIP = self::getClientIp($request);
        return sprintf('%s %s [%s] "%s %s %s/%s" %d %d',
            $clineIP,
            $request->getUri()->getUserInfo() ?: '-',
            strftime('%d/%b/%Y:%H:%M:%S %z'),
            strtoupper($request->getMethod()),
            $request->getUri()->getPath(),
            strtoupper($request->getUri()->getScheme()),
            $request->getProtocolVersion(),
            $response->getStatusCode(),
            $response->getBody()->getSize()
        );
    }
    
    /**
     * Generates a message using the Apache's Combined Log format
     * This is exactly the same than Common Log, with the addition of two more fields: Referer and User-Agent headers.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     *
     * @return string
     */
    private static function combinedFormat(ServerRequestInterface $request, ResponseInterface $response)
    {
        return sprintf('%s "%s" "%s"',
            self::commonFormat($request, $response),
            $request->getHeaderLine('Referer'),
            $request->getHeaderLine('User-Agent')
        );
    }
}