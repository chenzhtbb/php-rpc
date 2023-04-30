<?php

return [
    'enable' => true,
    'server' => [
        // rpc namespace
        'namespace' => 'service\\rpc\\',
        // listen protocol
        'protocol'  => 'tcp',
        // listen address
        'address'   => '0.0.0.0',
        // listen port
        'port'      => 40000,
        // process count
        'count'     => 4
    ],
];
