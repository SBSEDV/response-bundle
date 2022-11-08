<?php declare(strict_types=1);

namespace SBSEDV\Bundle\ResponseBundle\Response;

use Symfony\Contracts\Translation\TranslatableInterface;

class ApiResponseErrorDto
{
    /**
     * @param TranslatableInterface|string $message The "message" property.
     * @param string                       $type    The "type" property.
     * @param string                       $cause   [optional] An optional "cause" property.
     * @param array<string, mixed>         $other   [optional] Additional properties.
     */
    public function __construct(
        public readonly TranslatableInterface|string $message,
        public readonly string $type,
        public ?string $cause = null,
        public array $other = []
    ) {
    }
}
