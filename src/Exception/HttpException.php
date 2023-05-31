<?php declare(strict_types=1);

namespace SBSEDV\Bundle\ResponseBundle\Exception;

use Psr\Log\LogLevel;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Contracts\Translation\TranslatableInterface;

class HttpException extends \Exception implements HttpExceptionInterface
{
    protected string $type;

    protected ?TranslatableInterface $translatable = null;

    /**
     * @param TranslatableInterface|string $message    The Exception message to throw.
     * @param int                          $code       [optional] The Exception code.
     * @param \Throwable|null              $previous   [optional] The previous throwable used for the exception chaining.
     * @param string|null                  $cause      [optional] The error cause.
     * @param array                        $headers    [optional] Additional http response headers.
     * @param array                        $other      [optional] Additional error parameters.
     * @param bool                         $isLoggable [optional] Whether the exception should be logged.
     * @param string                       $logLevel   [optional] The desired log level.
     */
    public function __construct(
        TranslatableInterface|string $message,
        int $code = 500,
        \Throwable $previous = null,
        string $type = null,
        protected ?string $cause = null,
        protected array $headers = [],
        protected array $other = [],
        protected bool $isLoggable = false,
        protected string $logLevel = LogLevel::ERROR
    ) {
        $this->type = $type ?? 'server_error';

        if ($message instanceof TranslatableInterface) {
            $this->translatable = $message;

            if ($message instanceof TranslatableMessage) {
                $message = $message->getDomain() === null ? $message->getMessage() : $message->getDomain().'.'.$message->getMessage();

                if (null === $this->cause) {
                    $this->cause = $message;
                }
            } elseif ($message instanceof \Stringable) {
                $message = (string) $message;
            } else {
                $message = '';
            }
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * The optional Translatable object.
     */
    public function getTranslatable(): ?TranslatableInterface
    {
        return $this->translatable;
    }

    /**
     * The error cause.
     */
    public function getCause(): ?string
    {
        return $this->cause;
    }

    /**
     * The type of error.
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Get the extra data.
     */
    public function getOther(): array
    {
        return $this->other;
    }

    public function getStatusCode(): int
    {
        return $this->code;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Whether the exception should be logged.
     */
    public function isLoggable(): bool
    {
        return $this->isLoggable;
    }

    public function getLogLevel(): string
    {
        return $this->logLevel;
    }
}
