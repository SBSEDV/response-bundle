<?php declare(strict_types=1);

namespace SBSEDV\Bundle\ResponseBundle\Exception;

use Psr\Log\LogLevel;
use Symfony\Contracts\Translation\TranslatableInterface;

class AccessDeniedException extends HttpException
{
    /**
     * {@inheritdoc}
     */
    public function __construct(
        TranslatableInterface|string $message = 'Access denied.',
        ?\Throwable $previous = null,
        ?string $cause = null,
        array $headers = [],
        array $other = [],
        bool $isLoggable = false,
        string $logLevel = LogLevel::NOTICE
    ) {
        parent::__construct($message, 403, $previous, 'access_denied', $cause, $headers, $other, $isLoggable, $logLevel);
    }
}
