<?php declare(strict_types=1);

namespace SBSEDV\Bundle\ResponseBundle\Model;

/**
 * @implements \IteratorAggregate<string, Link>
 */
class LinkCollection implements \IteratorAggregate
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

    /**
     * {@inheritdoc}
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->collection);
    }
}
