<?php
namespace Tuum\Slimmed;

use RuntimeException;
use Interop\Container\ContainerInterface;
use Slim\Interfaces\CallableResolverInterface;

/**
 * Class CallableResolver
 *
 * Resolves a string to a callable;
 * string maybe a class implementing __invoke method, or
 * specify the method as: "className:methodName".
 *
 * @package Tuum\Respond\Slim
 */
final class CallableResolver implements CallableResolverInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var callable
     */
    protected $resolved;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Resolve toResolve into a closure that that the router can dispatch.
     *
     * If toResolve is of the format 'class:method', then try to extract 'class'
     * from the container otherwise instantiate it and then dispatch 'method'.
     *
     * @param mixed $toResolve
     * @return \Closure
     */
    public function resolve($toResolve)
    {
        // if it's callable, then it's already resolved
        if (is_callable($toResolve)) {
            return $toResolve;
        } elseif (is_string($toResolve)) {
            return $this->resolveClassAndMethod($toResolve);
        }
        throw new RuntimeException(sprintf('could not resolve to a callable'));
    }

    /**
     * finds class and method name of a $this->toResolve as string.
     *
     * @param string $toResolve
     * @return array
     */
    private function resolveClassAndMethod($toResolve)
    {
        $callable_pattern = '!^([^\:]+)\:([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)$!';
        if (strpos($toResolve, ':') === false) {
            // check for a "class" with __invoke method.
            $class  = $toResolve;
            $method = '__invoke';
        } elseif (preg_match($callable_pattern, $toResolve, $matches)) {
            // check for slim callable as "class:method"
            $class  = $matches[1];
            $method = $matches[2];
        } else {
            throw new RuntimeException(sprintf('%s is not resolvable', $toResolve));
        }
        if ($this->container->has($class)) {
            return [$this->container->get($class), $method];
        }
        if (class_exists($class)) {
            return [new $class, $method];
        }
        throw new RuntimeException(sprintf('Callable %s does not exist', $class));
    }
}