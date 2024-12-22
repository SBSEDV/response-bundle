<?php declare(strict_types=1);

namespace SBSEDV\Bundle\ResponseBundle\Model;

class Link
{
    /**
     * @param array<string, mixed> $routeParams
     * @param array<string, mixed> $other
     */
    public function __construct(
        public readonly string $href,
        public readonly array $routeParams = [],
        public readonly array $other = [],
    ) {
    }
}
