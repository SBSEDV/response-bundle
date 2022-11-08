<?php declare(strict_types=1);

namespace SBSEDV\Bundle\ResponseBundle\Exception;

use Symfony\Component\Translation\TranslatableMessage;

class InvalidRequestErrorException extends HttpException
{
    /**
     * {@inheritdoc}
     *
     * @param string $param The input parameter that is invalid.
     */
    public function __construct(
        TranslatableMessage|string $message,
        string $param,
        ?\Throwable $previous = null,
        ?string $cause = null,
        array $headers = [],
        array $other = [],
        bool $isLoggable = false
    ) {
        $other['param'] = $param;

        parent::__construct($message, 400, $previous, 'invalid_request_error', $cause, $headers, $other, $isLoggable);
    }

    /**
     * Create from a ResourceNotFoundException.
     *
     * @param ResourceNotFoundException $previous The previous ResourceNotFoundException exception.
     * @param string                    $param    [optional] The param that is invalid.
     */
    public static function fromResourceNotFoundException(ResourceNotFoundException $previous, string $param): self
    {
        return new self($previous->getTranslatable() ?? $previous->getMessage(), $param, $previous);
    }
}
