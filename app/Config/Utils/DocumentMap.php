<?php
namespace App\Config\Utils;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tuum\Locator\FileMap;
use Tuum\Respond\Respond;

class DocumentMap
{
    /**
     * @var FileMap
     */
    private $map;

    /**
     * @param FileMap $map
     */
    public function __construct(FileMap $map)
    {
        $this->map = $map;
    }

    /**
     * @param string $doc_dir
     * @param string $var_dir
     * @return DocumentMap
     */
    public static function forge($doc_dir, $var_dir)
    {
        return new DocumentMap(FileMap::forge($doc_dir, $var_dir));
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     * @return ResponseInterface
     */
    public function __invoke($request, $response, $args)
    {
        $path = isset($args['pathInfo']) ? $args['pathInfo'] : '';
        $info = $this->map->render($path);
        if (!$info->found()) {
            return Respond::error($request, $response)->asView(500);
        }
        if ($fp = $info->getResource()) {
            return Respond::view($request, $response)->asFileContents($fp, $info->getMimeType());
        }
        return Respond::view($request, $response)->asContents($info->getContents());
    }
}