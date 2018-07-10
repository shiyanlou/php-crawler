<?php

return [
    'client' => [
        'transport' => [
            'default' => [
                'alias' => 'amqp'
            ],
            'amqp' => [
                'driver' => 'bunny',
                'host' => 'rabbitmq',
                'port' => 5672,
                'user' => 'guest',
                'pass' => 'guest',
                'vhost' => '/',
            ],
        ],
        'client' => [
            'router_topic'             => 'default',
            'router_queue'             => 'default',
            'default_processor_queue'  => 'default',
        ],
    ],
];
