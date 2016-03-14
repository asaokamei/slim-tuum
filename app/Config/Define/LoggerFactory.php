<?php
namespace App\Config\Define;

use Interop\Container\ContainerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Tuum\Builder\AppBuilder;

class LoggerFactory
{
    /**
     * @var bool
     */
    private $isProduction = false;

    /**
     * LoggerFactory constructor.
     *
     * @param AppBuilder $builder
     */
    public function __construct(AppBuilder $builder)
    {
        $this->isProduction = $builder->isProduction();
    }

    /**
     * @param ContainerInterface $c
     * @return Logger
     */
    public function __invoke(ContainerInterface $c)
    {
        $var_dir = $c->get('settings')['var-dir'];
        $logger = new Logger('App');
        $logger->pushProcessor(new UidProcessor());
        $logger->pushHandler(new StreamHandler($var_dir.'/logs/app.log', Logger::DEBUG));
        $logger->pushHandler(new StreamHandler($var_dir.'/logs/app-error.log', Logger::ERROR));
        return $logger;
    }
}