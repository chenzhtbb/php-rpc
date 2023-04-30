<?php

$config   = config('plugin.chenzhtbb.rpc.app.server');
$protocol = $config['protocol'];
$address  = $config['address'];
$port     = $config['port'];

return [
  'server' => [
    // 这里指定进程类
    'handler'    => \Chenzhtbb\Rpc\Server::class,
    // 监听的协议 ip 及端口 （可选）
    'listen'     => "$protocol://$address:$port",
    // 进程数 （可选，默认1）
    'count'      => $config['count'],
    // 进程运行用户 （可选，默认当前用户）
    'user'       => '',
    // 进程运行用户组 （可选，默认当前用户组）
    'group'      => '',
    // 当前进程是否支持reload （可选，默认true）
    'reloadable' => true,
    // 是否开启reusePort （可选，此选项需要php>=7.0，默认为true）
    'reusePort'  => true,
    // transport (可选，当需要开启ssl时设置为ssl，默认为tcp)
    'transport'  => 'tcp',
    // context （可选，当transport为是ssl时，需要传递证书路径）
    'context'    => [],
    // 进程类构造函数参数，这里为 process\Pusher::class 类的构造函数参数 （可选）
    'constructor' => [],
  ]
];
