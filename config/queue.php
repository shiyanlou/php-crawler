<?php

return [
    'default' => env('QUEUE_DRIVER', 'interop'),

    'connections' => [
        'interop' => [
            'driver' => 'interop',
            'connection_factory_class' => \Enqueue\Fs\FsConnectionFactory::class,
            'path' => storage_path('enqueue'),

            //'driver' => 'amqp_interop',
            //'connection_factory_class' => \Enqueue\AmqpBunny\AmqpConnectionFactory::class,
            //'host' => 'rabbitmq',
            //'port' => 5672,
            //'user' => 'guest',
            //'pass' => 'guest',
            //'vhost' => '/',
            //'queue' => 'default',
        ],
    ],

    'failed' => [
        'database' => env('DB_CONNECTION', 'mysql'),
        'table' => 'failed_jobs',
    ],
];
