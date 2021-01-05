<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

use Monolog\Formatter\LineFormatter;
use Monolog\Logger;

$data = [];

// 日志分组
$loggers = ['upload', 'register', 'SMS', 'task', 'signIn', 'withdrawal', 'recharge', 'efuPay', 'gaga_pay', 'gaga_payout', 'payment'];
// 日志级别
$levels = [
    // Logger::DEBUG,
    Logger::INFO,
    // Logger::NOTICE,
    // Logger::WARNING,
    Logger::ERROR
];

foreach ($loggers as $logger) {
    $data[$logger] = [
        'handlers' => array_map(function ($level) use ($logger) {
            $levelName = strtolower(Logger::getLevelName($level));
            return [
                'class'       => Monolog\Handler\RotatingFileHandler::class,
                'constructor' => [
                    'filename' => BASE_PATH . "/runtime/logs/{$logger}/{$levelName}.log",
                    'level'    => $level,
                ],
                'formatter' => [
                    'class' => LineFormatter::class,
                    'constructor' => [
                        'format' => null,
                        'dateFormat' => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => true,
                    ]
                ],
            ];
        }, $levels)];
}

return array_merge($data, [
    'default' => [
        'handler' => [
            'class' => Monolog\Handler\StreamHandler::class,
            'constructor' => [
                'stream' => BASE_PATH . '/runtime/logs/hyperf.log',
                'level' => Monolog\Logger::DEBUG,
            ],
        ],
        'formatter' => [
            'class' => Monolog\Formatter\LineFormatter::class,
            'constructor' => [
                'format' => null,
                'dateFormat' => null,
                'allowInlineLineBreaks' => true,
            ],
        ],
    ],
]);
