<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        PDO::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            $sdb = $settings->get('db');
            switch ($sdb['driver']) :
                case 'sqlite':
                    $db = [
                        'dbname' => $sdb['dbname'],
                        'dbpath' => $sdb['dbpath'],
                    ];

                    $connection = new PDO("sqlite:" . $db['dbpath'] . $db['dbname']);
                    break;
                case 'mysql':
                    $db = [
                        'dbname' => $sdb['dbname'],
                        'user' => $sdb['username'],
                        'pass' => $sdb['password'],
                        'host' => $sdb['host']
                    ];
                    $connection = new PDO("mysql:host=" . $db['host'] .
                        ";port=3306;dbname=" . $db['dbname'], $db['user'], $db['pass']);
                    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            endswitch;
            // $connection->exec('CREATE TABLE IF NOT EXISTS deployments (id int, application text, version text, who text, time int, environment text)');
            return $connection;
        },
    ]);
};
