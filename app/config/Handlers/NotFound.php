<?php
namespace App\config\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tuum\Respond\Respond;

class NotFound
{
    /**
     * Known handled content types
     *
     * @var array
     */
    protected $knownContentTypes = [
        'application/json',
        'application/xml',
        'text/xml',
        'text/html',
    ];

    /**
     * Invoke not found handler
     *
     * @param  ServerRequestInterface $request  The most recent Request object
     * @param  ResponseInterface      $response The most recent Response object
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {

        $contentType = $this->determineContentType($request);
        switch ($contentType) {
            case 'application/json':
                $output = '{"message":"Not found"}';
                break;

            case 'text/xml':
            case 'application/xml':
                $output = '<root><message>Not found</message></root>';
                break;

            case 'text/html':
            default:
                return Respond::error($request, $response)->notFound();

                break;
        }

        $response->getBody()->write($output);

        return $response->withStatus(404)
            ->withHeader('Content-Type', $contentType)
            ;
    }

    /**
     * Determine which content type we know about is wanted using Accept header
     *
     * @param ServerRequestInterface $request
     * @return string
     */
    private function determineContentType(ServerRequestInterface $request)
    {
        $acceptHeader = $request->getHeaderLine('Accept');
        $selectedContentTypes = array_intersect(explode(',', $acceptHeader), $this->knownContentTypes);

        if (count($selectedContentTypes)) {
            return $selectedContentTypes[0];
        }

        return 'text/html';
    }
}