<?php

namespace App\Providers;

use App\Http\Middleware\RequestResponseLogger;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class CustomLogServiceProvider extends ServiceProvider
{
    const REGEX_CONFIG_DELIMITER = '/\s*[|,]\s*/';
    const CONFIG_KEY_FOR_SKIPLIST = 'logging.skip_path';

    public function boot()
    {
        /** @var Logger $monolog */
        $monolog = $this->app->make(LoggerInterface::class)->getMonolog();
        $request = $this->app->make(Request::class);
        $monolog->pushProcessor(function (array $record) use ($request){
            try {
                $record['extra']['instanceId'] = env('EC2_INSTANCE_ID', gethostname());
                $record['extra']['version'] = env('APP_VERSION', trim(exec('git log --pretty="%h" -n1 HEAD')));
                $record['extra']['fingerprint'] = $request->fingerprint();
            } catch (\RuntimeException $e) {
                // 웹 요청일때만 Request 인스턴스에서 핑거프린트를 구할 수 있습니다.
                $record['extra']['fingerprint'] = $e->getMessage();
            }
            return $record;
        });
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
