<?php
namespace Demo\Handler;

use Slim\Router;
use Tuum\Respond\Interfaces\NamedRoutesInterface;

class NamedRoutes implements NamedRoutesInterface
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @param Router $router
     */
    public function __construct($router)
    {
        $this->router = $router;
    }

    /**
     * returns url for a give route name.
     *
     * @param string $routeName
     * @param array  $options
     * @return string
     */
    public function route($routeName, $options = [])
    {
        return $this->router->pathFor($routeName, $options);
    }
}