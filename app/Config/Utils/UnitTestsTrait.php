<?php
namespace App\Config\Utils;

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
    }
}