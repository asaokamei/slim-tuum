<?php
namespace App\Config\Utils;

use Slim\App;
use Slim\Http\Body;
use Slim\Http\Environment;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Uri;

trait UnitTestsTrait
{
    /**
     * @var string
     */
    protected $root_url = 'http://example.com/';

    /**
     * @var array
     */
    protected $app_config = [];
    
    /**
     * @var App
     */
    protected $app;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var string
     */
    protected $html;

    abstract public function assertContains(
        $needle, 
        $haystack, 
        $message = '', 
        $ignoreCase = false, 
        $checkForObjectIdentity = true, 
        $checkForNonObjectIdentity = false
    );
    
    abstract public function assertTrue($condition, $message = '');

    abstract public function assertEquals(
        $expected, 
        $actual, 
        $message = '', 
        $delta = 0.0, 
        $maxDepth = 10, 
        $canonicalize = false, 
        $ignoreCase = false
    );
    
    abstract public function assertNotContains(
        $needle, 
        $haystack, 
        $message = '', 
        $ignoreCase = false, 
        $checkForObjectIdentity = true, 
        $checkForNonObjectIdentity = false
    );
    
    /**
     * builds application.
     */
    protected function buildApp()
    {
        /** @var callable $script */
        $script = include dirname(dirname(__DIR__)) . '/app.php';
        $config = $this->app_config + [
            'debug' => true,
            'env'   => 'test',
        ];
        $this->app = $script($config);
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

    /**
     * assert that the response body contains $text.
     * 
     * @param string $text
     */
    protected function responseContains($text)
    {
        $this->assertContains($text, $this->html);
    }

    /**
     * assert that the response body DOES NOT contain $text.
     *
     * @param string $text
     */
    protected function responseNotContains($text)
    {
        $this->assertNotContains($text, $this->html);
    }

    /**
     * assert that the response is a redirect with $path (if set).
     * 
     * @param string $path
     */
    protected function responseIsRedirect($path = '')
    {
        $this->assertTrue($this->response->isRedirect());
        if ($path) {
            $this->assertEquals($this->root_url . $path, $this->response->getHeaderLine('Location'));
        }
    }

    /**
     * build request and response to run Slim 3 application.
     * 
     * @param string $method
     * @param Uri   $uri
     * @param array $post
     */
    protected function buildRequest($method, $uri, $post = [])
    {
        $headers        = new Headers();
        $cookies        = [];
        $env            = Environment::mock();
        $serverParams   = $env->all();
        $body           = new Body(fopen('php://temp', 'r+'));
        $this->request  = new Request($method, $uri, $headers, $cookies, $serverParams, $body);
        if (!empty($post)) {
            $parsed = new \ReflectionProperty($this->request, 'bodyParsed');
            $parsed->setAccessible(true);
            $parsed->setValue($this->request, $post);
        }
        $this->response = new Response;
        
        $this->app->getContainer()['request'] = $this->request;
        $this->app->getContainer()['response'] = $this->response;
    }

    /**
     * @param String $method
     * @param Uri   $uri
     * @param array $post
     */
    protected function runApp($method, $uri, $post = [])
    {
        $this->buildApp();
        $this->buildRequest($method, $uri, $post);
        $this->response = $this->app->run(true);
        $this->response->getBody()->rewind();
        $this->html = $this->response->getBody()->getContents();
    }
}