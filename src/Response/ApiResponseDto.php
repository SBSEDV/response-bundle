<?php declare(strict_types=1);

namespace SBSEDV\Bundle\ResponseBundle\Response;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatableInterface;

class ApiResponseDto
{
    /**
     * @param TranslatableInterface|string|null $message The "message" property.
     * @param mixed                             $data    The "data" property.
     * @param ApiResponseErrorDto[]             $errors  The "errors" property.
     * @param int                               $status  The "status" property.
     */
    public function __construct(
        public readonly TranslatableInterface|string|null $message = null,
        public readonly mixed $data = null,
        public readonly array $errors = [],
        public readonly int $status = Response::HTTP_OK,
    ) {
    }
}
