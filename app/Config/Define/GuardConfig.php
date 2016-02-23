<?php
namespace App\Config\Define;

use Slim\Csrf\Guard;
use Slim\Http\Request;
use Slim\Http\Response;
use Tuum\Respond\Respond;

class GuardConfig
{
    /**
     * @return Guard
     */
    public function __invoke()
    {
        $guard = new Guard();
        $guard->setFailureCallable(function(Request $request, Response $response){
            return Respond::error($request, $response)->forbidden();
        });
        return $guard;
    }
}