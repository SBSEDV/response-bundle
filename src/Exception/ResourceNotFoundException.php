<?php declare(strict_types=1);

namespace SBSEDV\Bundle\ResponseBundle\Exception;

use Symfony\Component\Translation\TranslatableMessage;

class ResourceNotFoundException extends HttpException
{
    /**
     * {@inheritdoc}
     *
     * @param mixed $resourceIdentifier The resource identifier that was not found.
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
            $other['resourceIdentifier'] = $resourceIdentifier;
        }

        parent::__construct($message, 404, $previous, 'resource_not_found', $cause, $headers, $other, $isLoggable);
    }
}