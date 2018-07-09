<?php

namespace App\Providers;

use App\Http\Middleware\RequestResponseLogger;
use App\Support\Logging\ExtraLogContextProcessor;
use App\Support\Logging\PrettyLogFormatter;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\ServiceProvider;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class CustomLogServiceProvider extends ServiceProvider
{
    const REGEX_CONFIG_DELIMITER = '/\s*[|,]\s*/';
    const CONFIG_KEY_FOR_SKIPLIST = 'logging.skip_path';

    public function boot()
    {
        $config = $this->app->make(Repository::class);
        $formatter = new PrettyLogFormatter(null, null, true, true);
        $streamHandler = new StreamHandler(
            $this->app->storagePath().'/logs/laravel.log',
            $config->get('app.log_level', Logger::DEBUG)
        );
        $streamHandler->setFormatter($formatter);

        /** @var Logger $monolog */
        $monolog = $this->app->make(LoggerInterface::class)->getMonolog();
        $monolog->setHandlers([$streamHandler]);

        $extraLogContextProcessor = $this->app->make(ExtraLogContextProcessor::class);
        $monolog->pushProcessor($extraLogContextProcessor);
    }

    public function register()
    {
        $this->app->when(RequestResponseLogger::class)
            ->needs('$skipList')
            ->give($this->getSkipList());
    }

    private function getSkipList()
    {
        $skipListString = $this->app->make(Repository::class)
            ->get(self::CONFIG_KEY_FOR_SKIPLIST);

        return array_filter(
            preg_split(self::REGEX_CONFIG_DELIMITER, $skipListString)
        );
    }
}
