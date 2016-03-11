<?php
namespace App\Config\Utils;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Interop\Container\Exception\NotFoundException;
use League\Container\ReflectionContainer;

class Container implements ContainerInterface, \ArrayAccess
{
    /**
     * @var \League\Container\Container
     */
    private $container;

    /**
     * Container constructor.
     *
     * @param \League\Container\Container $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * @return Container
     */
    public static function forge()
    {
        $container = new \League\Container\Container();
        $container->delegate(
            new ReflectionContainer
        );
        return new self($container);
    }
    
    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundException  No entry was found for this identifier.
     * @throws ContainerException Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        return $this->container->get($id);
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return boolean
     */
    public function has($id)
    {
        if ($this->container->has($id)) {
            return true;
        }
        if (is_string($id) && class_exists($id)) {
            return true;
        }
        return false;
    }

    /**
     * Whether a offset exists
     *
     * @param mixed $offset An offset to check for.
     * @return boolean true on success or false on failure.
     *                      The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return $this->container->has($offset);
    }

    /**
     * Offset to retrieve
     *
     * @param mixed $offset The offset to retrieve.
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->container->get($offset);
    }

    /**
     * Offset to set
     *
     * @param mixed $offset The offset to assign the value to.
     * @param mixed $value  The value to set.
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $builder = $this->container->add($offset, $value, true);
        if ($builder && is_callable($value)) {
            $builder->withArgument($this);
        }
    }

    /**
     * Offset to unset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        throw new \BadMethodCallException;
    }
}