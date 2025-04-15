<?php

namespace App\Infrastructure\Services;

use App\Application\Services\LoggerServiceInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;

class MonologLoggerService implements LoggerServiceInterface
{
    private Logger $logger;

    public function __construct(string $channel = 'app')
    {
        $this->logger = new Logger($channel);

        // Log a archivo con rotación diaria
        $this->logger->pushHandler(
            new RotatingFileHandler(
                dirname(__DIR__, 3) . '/logs/app.log',
                30, // días a mantener
                Logger::DEBUG
            )
        );

        // Log a stdout para desarrollo
        if (($env = $_ENV['APP_ENV'] ?? null) && $env === 'development') {
            $this->logger->pushHandler(
                new StreamHandler('php://stdout', Logger::DEBUG)
            );
        }
    }

    public function info(string $message, array $context = []): void
    {
        $this->logger->info($message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->logger->error($message, $context);
    }

    public function debug(string $message, array $context = []): void
    {
        $this->logger->debug($message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        $this->logger->warning($message, $context);
    }
}
