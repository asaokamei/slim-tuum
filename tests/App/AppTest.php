<?php
namespace tests\App;

use App\Config\Utils\UnitTestsAppTrait;

class AppTest extends \PHPUnit_Framework_TestCase
{
    use UnitTestsAppTrait;

    function setup()
    {
        $_SESSION = [];
        $this->app_config = [];
        $this->root_url   = 'http://localhost:8888/';
    }
    
    function tearDown()
    {
        unset($_SESSION);
        parent::tearDown();
    }

    /**
     * @test
     */
    function top_page()
    {
        $this->runGet('');
        $this->assertContains('<h1>Slim 3 + Tuum/Respond</h1>', $this->html);
    }

    /**
     * @test
     */
    function jump_page()
    {
        /**
         * display jump form with default value.
         */
        $this->runGet('jump');
        $this->responseContains('<h1>Let\'s Jump!!</h1>');
        $this->responseContains('value="original text"');

        /**
         * post jump with jumped text. 
         */
        $this->buildApp();
        $this->runPost('jump', ['jumped' => 'unit-tested']);
        $this->responseIsRedirect('jump');

        /**
         * redirected back, showing redirected value. 
         */
        $this->buildApp();
        $this->runGet('jump');
        $this->responseContains('<h1>Let\'s Jump!!</h1>');
        $this->responseNotContains('value="original text"');
        $this->responseContains('value="unit-tested"');
    }
}
