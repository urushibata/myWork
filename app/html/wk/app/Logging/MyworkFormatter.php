<?php

declare(strict_types=1);

namespace App\Logging;

use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;

class MyworkFormatter
{
    public function __invoke($monolog)
    {
        $fields = [
            "%datetime%",
            "%level_name%",
            "%extra.class%::%extra.function%#%extra.line%",
            "%message%",
        ];
        $logFormat = implode("\t", $fields) . PHP_EOL;
        $formatter = new LineFormatter($logFormat, 'ymd-His.v');

        $ip = new IntrospectionProcessor(Logger::DEBUG, ['Illuminate\\']);
        foreach ($monolog->getHandlers() as $handler) {
            $handler->setFormatter($formatter);
            $handler->pushProcessor($ip);
        }
    }
}
