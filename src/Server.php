<?php

declare(strict_types=1);

namespace Chenzhtbb\Rpc;

use Workerman\Connection\TcpConnection;

class Server
{
  private function error($code = 400, $msg = 'invalid request')
  {
    return $this->result($code, $msg, null);
  }

  private function success($data = [])
  {
    return $this->result(0, 'ok', $data);
  }

  private function result($code, $msg, $data)
  {
    return json_encode([
      'code' => $code,
      'msg'  => $msg,
      'data' => $data
    ]);
  }

  public function onMessage(TcpConnection $connection, string $jsonEncode)
  {
    try {
      $request = json_decode($jsonEncode, true);
      if (empty($request['class']) || empty($request['method'])) {
        return $connection->send($this->error());
      }
      $namespace = config('plugin.chenzhtbb.rpc.app.server.namespace');
      $class  = $namespace . $request['class'];
      $method = $request['method'];
      $args   = $request['args'] ?? [];
      if (!class_exists($class)) {
        return $connection->send($this->error(404, 'class not found'));
      }

      if (!method_exists($class, $method)) {
        return $connection->send($this->error(404, 'method of class not found'));
      }

      return $connection->send($this->success(call_user_func_array([$class, $method], $args)));
    } catch (\Throwable $th) {
      $code = $th->getCode() ? $th->getCode() : 500;
      return $connection->send($this->error($code, $th->getMessage()));
    }
  }
}
