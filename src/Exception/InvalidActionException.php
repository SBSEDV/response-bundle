<?php declare(strict_types=1);

namespace SBSEDV\Bundle\ResponseBundle\Exception;

use Psr\Log\LogLevel;
use Symfony\Contracts\Translation\TranslatableInterface;

class InvalidActionException extends HttpException
{
    public function __construct(
        TranslatableInterface|string $message,
        int $code = 400,
        ?\Throwable $previous = null,
        ?string $cause = null,
        array $headers = [],
        array $other = [],
        bool $isLoggable = false,
        string $logLevel = LogLevel::INFO,
    ) {
        parent::__construct($message, $code, $previous, 'invalid_action', $cause, $headers, $other, $isLoggable, $logLevel);
    }
}
