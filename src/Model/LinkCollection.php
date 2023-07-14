<?php declare(strict_types=1);

namespace SBSEDV\Bundle\ResponseBundle\Model;

/**
 * @implements \IteratorAggregate<string, Link>
 * @implements \ArrayAccess<string, Link>
 */
class LinkCollection implements \IteratorAggregate, \ArrayAccess
{
    /**
     * @param array<string, Link> $collection
     */
    public function __construct(
        private array $collection = []
    ) {
        foreach ($this->collection as $key => $value) {
            $this->addLink($key, $value);
        }
    }

    public function addLink(string $name, Link $link): self
    {
        $this->collection[$name] = $link;

        return $this;
    }

    public function removeLink(string $name): void
    {
        unset($this->collection[$name]);
    }

    public function hasLink(string $name): bool
    {
        return \array_key_exists($name, $this->collection);
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->collection);
    }

    public function offsetExists(mixed $offset): bool
    {
        return $this->hasLink($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->collection[$offset] ?? throw new \InvalidArgumentException();
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->removeLink($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->addLink($offset, $value);
    }
}
