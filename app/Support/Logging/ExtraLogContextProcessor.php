<?php

namespace App\Support\Logging;

use App\ApplicationContext;

class ExtraLogContextProcessor
{
    private $appContext;

    public function __construct(ApplicationContext $appContext)
    {
        $this->appContext = $appContext;
    }

    public function __invoke(array $record)
    {
        $record['extra']['version'] = $this->appContext->getAppVersion();
        $record['extra']['transaction_id'] = $this->appContext->getTransactionId();
        $record['extra']['trace_number'] = $this->appContext->getTraceNumber();
        $this->appContext->increaseTraceNumber();

        return $record;
    }
}
