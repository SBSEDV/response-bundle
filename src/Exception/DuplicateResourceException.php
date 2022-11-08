<?php declare(strict_types=1);

namespace SBSEDV\Bundle\ResponseBundle\Exception;

use Symfony\Component\Translation\TranslatableMessage;

class DuplicateResourceException extends HttpException
{
    /**
     * {@inheritdoc}
     *
     * @param mixed $resourceIdentifier The duplicate resource identifier.
     */
    public function __construct(
        TranslatableMessage|string $message,
        mixed $resourceIdentifier = null,
        ?\Throwable $previous = null,
        ?string $cause = null,
        array $headers = [],
        array $other = [],
        bool $isLoggable = false
    ) {
        if (null !== $resourceIdentifier && !\is_scalar($resourceIdentifier)) {
            throw new \InvalidArgumentException('The $resourceIdentifier must have a scalar value.');
        }

        if (null !== $resourceIdentifier) {
            $other['existingResourceIdentifier'] = $resourceIdentifier;
        }

        parent::__construct($message, 409, $previous, 'duplicate_resource', $cause, $headers, $other, $isLoggable);
    }
}