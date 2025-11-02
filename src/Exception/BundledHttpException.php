<?php declare(strict_types=1);

namespace SBSEDV\Bundle\ResponseBundle\Exception;

use Symfony\Contracts\Translation\TranslatableInterface;

class BundledHttpException extends HttpException
{
    /**
     * @param HttpException[]                $exceptions The exceptions to bundle.
     * @param array<string, string|string[]> $headers
     * @param array<array-key, mixed>        $other
     */
    public function __construct(
        private array $exceptions,
        TranslatableInterface|string $message = '',
        int $code = 400,
        ?\Throwable $previous = null,
        array $headers = [],
        array $other = [],
    ) {
        foreach ($this->exceptions as $exception) {
            $headers = \array_merge($headers, $exception->getHeaders());
        }

        if (\count($this->exceptions) > 150) {
            $this->exceptions = \array_slice($this->exceptions, 0, 150);
        }

        parent::__construct($message, $code, $previous, 'bundled', null, $headers, $other);
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
