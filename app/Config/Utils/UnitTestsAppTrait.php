<?php
namespace App\Config\Utils;

use Slim\App;
use Slim\Http\Uri;

trait UnitTestsAppTrait 
{
    use UnitTestsTrait;

    /**
     * @var App
     */
    protected $app;
    
    /**
     * @param String $method
     * @param Uri   $uri
     * @param array $post
     */
    protected function runApp($method, $uri, $post = [])
    {
        $this->buildApp();
        $this->buildRequest($method, $uri, $post);

        $this->app->getContainer()['request'] = $this->request;
        $this->app->getContainer()['response'] = $this->response;
        $this->response = $this->app->run(true);
        $this->response->getBody()->rewind();
        $this->html = $this->response->getBody()->getContents();
    }
    
    /**
     * run application with GET method.
     *
     * @param string $path
     * @param array  $query
     */
    protected function runGet($path = '', $query = [])
    {
        if (!empty($query)) {
            $path .= '?' . http_build_query($query);
        }
        $uri = Uri::createFromString($this->root_url . $path);
        $this->runApp('GET', $uri);
    }

    /**
     * run application with POST method.
     * adds C.S.R.F. token if $post is set.
     *
     * @param string $path
     * @param array  $post
     */
    protected function runPost($path = '', $post = [])
    {
        if (!empty($post)) { // add csrf tokens.
            $key = 'unit-csrf';
            $val = 'unit-csrf-value';
            $_SESSION['csrf'][$key] = $val;
            $post['csrf_name'] = $key;
            $post['csrf_value'] = $val;
        }
        $uri = Uri::createFromString($this->root_url . $path);
        $this->runApp('POST', $uri, $post);
    }

}
