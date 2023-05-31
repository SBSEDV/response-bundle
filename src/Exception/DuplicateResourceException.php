<?php declare(strict_types=1);

namespace SBSEDV\Bundle\ResponseBundle\Exception;

use Psr\Log\LogLevel;
use Symfony\Contracts\Translation\TranslatableInterface;

class DuplicateResourceException extends HttpException
{
    /**
     * @param mixed $resourceIdentifier The duplicate resource identifier.
     */
    public function __construct(
        TranslatableInterface|string $message,
        mixed $resourceIdentifier = null,
        \Throwable $previous = null,
        string $cause = null,
        array $headers = [],
        array $other = [],
        bool $isLoggable = false,
        string $logLevel = LogLevel::INFO
    ) {
        if (null !== $resourceIdentifier && !\is_scalar($resourceIdentifier)) {
            throw new \InvalidArgumentException('The $resourceIdentifier must have a scalar value.');
        }

        if (null !== $resourceIdentifier) {
            $other['existingResourceIdentifier'] = $resourceIdentifier;
        }

        parent::__construct($message, 409, $previous, 'duplicate_resource', $cause, $headers, $other, $isLoggable, $logLevel);
    }
}
