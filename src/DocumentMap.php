<?php
namespace Tuum\Slimmed;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tuum\Locator\FileMap;
use Tuum\Respond\Respond;

class DocumentMap implements ServiceProviderInterface
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
        if (empty($info)) {
            return Respond::error($request, $response)->asView(500);
        }
        list($fp, $mime) = $info;
        if (is_resource($fp)) {
            return Respond::view($request, $response)->asFileContents($fp, $mime);
        }
        if (is_string($fp)) {
            return Respond::view($request, $response)->asContents($fp);
        }
        return Respond::error($request, $response)->asView(500);
    }

    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $pimple[self::class] = $this;
    }
}