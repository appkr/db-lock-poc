<?php

namespace App\Support\Logging;

use Monolog\Formatter\LineFormatter;

class PrettyLogFormatter extends LineFormatter
{
    /**
     * {@inheritdoc}
     */
    protected function toJson($data, $ignoreErrors = false)
    {
        $json = json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

        if ($json === false) {
            $json = parent::toJson($data, $ignoreErrors);
        }

        return $json;
    }
}
