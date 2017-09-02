<?php
use Demo\Handler\RespondMiddleware;
use Monolog\Logger;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;

/** @var App $app */

/**
 * logging and catching exceptions.
 */
$app->add(function(ServerRequestInterface $req, $res, $next) {
    try {
        /** @var Logger $log */
        $log = $this->logger;
        $log->info("{$req->getMethod()} {$req->getUri()->getPath()}");
        return $next($req, $res);

    } catch (\Exception $e) {

        $message = get_class($e) . ' exception: ' . $e->getMessage();
        $context = [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ];
        $log->critical($message, $context);
        return $this->responder->error($req, $res)->asView($e->getCode());
    }
});