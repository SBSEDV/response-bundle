<?php declare(strict_types=1);

namespace SBSEDV\Bundle\ResponseBundle\Exception;

use Psr\Log\LogLevel;
use Symfony\Contracts\Translation\TranslatableInterface;

class InvalidRequestErrorException extends HttpException
{
    /**
     * @param string $param The input parameter that is invalid.
     */
    public function __construct(
        TranslatableInterface|string $message,
        string $param,
        \Throwable $previous = null,
        string $cause = null,
        array $headers = [],
        array $other = [],
        bool $isLoggable = false,
        string $logLevel = LogLevel::INFO
    ) {
        $other['param'] = $param;

        parent::__construct($message, 400, $previous, 'invalid_request_error', $cause, $headers, $other, $isLoggable, $logLevel);
    }

    /**
     * Create from a ResourceNotFoundException.
     *
     * @param ResourceNotFoundException $previous The previous ResourceNotFoundException exception.
     * @param string                    $param    [optional] The param that is invalid.
     */
    public static function fromResourceNotFoundException(ResourceNotFoundException $previous, string $param): self
    {
        return new self($previous->getTranslatable() ?? $previous->getMessage(), $param, $previous, null, $previous->getHeaders(), $previous->getOther(), $previous->isLoggable(), $previous->getLogLevel());
    }
}
