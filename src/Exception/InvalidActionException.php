<?php declare(strict_types=1);

namespace SBSEDV\Bundle\ResponseBundle\Exception;

use Symfony\Component\Translation\TranslatableMessage;

class InvalidActionException extends HttpException
{
    /**
     * {@inheritdoc}
     */
    public function __construct(
        TranslatableMessage|string $message,
        int $code = 400,
        ?\Throwable $previous = null,
        ?string $cause = null,
        array $headers = [],
        array $other = [],
        bool $isLoggable = false
    ) {
        parent::__construct($message, $code, $previous, 'invalid_action', $cause, $headers, $other, $isLoggable);
    }
}
