<?php declare(strict_types=1);

namespace SBSEDV\Bundle\ResponseBundle\EventListener;

use SBSEDV\Bundle\ResponseBundle\Exception\BundledHttpException;
use SBSEDV\Bundle\ResponseBundle\Exception\HttpException;
use Symfony\Component\HttpKernel\EventListener\ErrorListener as SymfonyErrorListener;

class ErrorListener extends SymfonyErrorListener
{
    protected function logException(\Throwable $exception, string $message, ?string $logLevel = null, ?string $logChannel = null): void
    {
        if ($exception instanceof BundledHttpException) {
            foreach ($exception->getExceptions() as $e) {
                parent::logException($e, $message, $e->getLogLevel(), $logChannel);
            }

            return;
        }

        if ($exception instanceof HttpException) {
            $logLevel = $exception->getLogLevel();
        }

        parent::logException($exception, $message, $logLevel, $logChannel);
    }
}
