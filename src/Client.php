<?php

declare(strict_types=1);

namespace Chenzhtbb\Rpc;

class Client
{
  const TIME_OUT = 5;

  protected $serviceName = '';

  protected $connection = '';

  protected static $addressArray = array();

  protected static $instances = array();

  protected function __construct($serviceName)
  {
    $this->serviceName = $serviceName;
  }

  public static function instance($serviceName)
  {
    if (!isset(self::$instances[$serviceName])) {
      self::$instances[$serviceName] = new self($serviceName);
    }
    return self::$instances[$serviceName];
  }

  public static function config($address_array = array())
  {
    if (!empty($address_array)) {
      self::$addressArray = $address_array;
    }
    return self::$addressArray;
  }

  public function __call(string $method, array $args)
  {
    $this->send($method, $args);
    return $this->recv();
  }

  protected function send(string $method, array $args)
  {
    $this->open();
    $data = json_encode([
      'class'  => $this->serviceName,
      'method' => $method,
      'args'   => $args
    ]);
    if (fwrite($this->connection, $$data) !== strlen($data)) {
      throw new \Exception('send data failed');
    }
    return true;
  }

  protected function recv()
  {
    $retval = fgets($this->connection);
    if (!$retval) {
      throw new \Exception("recv data failed");
    }
    $this->close();
    return json_decode($retval, true);
  }

  protected function open()
  {
    $address = self::$addressArray[array_rand(self::$addressArray)];
    $this->connection = stream_socket_client($address, $err_no, $err_msg);
    if (!$this->connection) {
      throw new \Exception("can not connect to $address , $err_no:$err_msg");
    }
    stream_set_blocking($this->connection, true);
    stream_set_timeout($this->connection, self::TIME_OUT);
  }

  protected function close()
  {
    fclose($this->connection);
    $this->connection = null;
  }
}
