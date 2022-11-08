<?php declare(strict_types=1);

namespace SBSEDV\Bundle\ResponseBundle\Exception;

use Symfony\Component\Translation\TranslatableMessage;

class BundledHttpException extends HttpException
{
    /**
     * {@inheritdoc}
     *
     * @param HttpException[] $exceptions The exceptions to bundle.
     */
    public function __construct(
        private array $exceptions,
        TranslatableMessage|string $message = '',
        int $code = 400,
        ?\Throwable $previous = null,
        array $headers = [],
        array $other = []
    ) {
        foreach ($this->exceptions as $exception) {
            $headers = \array_merge($headers, $exception->getHeaders());
        }

        if (\count($this->exceptions) > 150) {
            $this->exceptions = \array_slice($this->exceptions, 0, 150);
        }

        $isLoggable = false;
        foreach ($this->exceptions as $e) {
            if ($e->isLoggable()) {
                $isLoggable = true;
                break;
            }
        }

        parent::__construct($message, $code, $previous, 'bundled', null, $headers, $other, $isLoggable);
    }

    /**
     * The bundled exceptions.
     *
     * @return HttpException[]
     */
    public function getExceptions(): array
    {
        return $this->exceptions;
    }
}
