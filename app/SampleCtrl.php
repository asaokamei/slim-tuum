<?php
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class SampleCtrl
 *
 * a sample class for a controller
 */
class SampleCtrl
{
    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        $response->write("From a controller class!" . print_r($args, true));
        return $response;
    }
}