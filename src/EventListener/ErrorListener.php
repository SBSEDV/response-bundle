<?php declare(strict_types=1);

namespace SBSEDV\Bundle\ResponseBundle\EventListener;

use SBSEDV\Bundle\ResponseBundle\Exception\BundledHttpException;
use SBSEDV\Bundle\ResponseBundle\Exception\HttpException;
use Symfony\Component\HttpKernel\EventListener\ErrorListener as SymfonyErrorListener;

class ErrorListener extends SymfonyErrorListener
{
    /**
     * {@inheritdoc}
     */
    protected function logException(\Throwable $exception, string $message, ?string $logLevel = null): void
    {
        // skip exceptions that should not be logged.
        if ($exception instanceof HttpException) {
            if ($exception instanceof BundledHttpException) {
                foreach ($exception->getExceptions() as $e) {
                    if ($e->isLoggable()) {
                        parent::logException($e, $message, $logLevel);
                    }
                }

                return;
            }

            if (!$exception->isLoggable()) {
                return;
            }
        }

        parent::logException($exception, $message, $logLevel);
    }
}
