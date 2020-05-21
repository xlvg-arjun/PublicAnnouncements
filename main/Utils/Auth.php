<?php

namespace App\Utils {

  use \Firebase\JWT\JWT;

  use App\Utils\Exception\JwtKeyNotProvidedException as ExceptionJwtKeyNotProvidedException;

  class Auth
  {
    private static $KEY = null;
    private static $JWT_ALG = 'HS256';
    public static function setSession($username): void
    {
      $_SESSION['user'] = $username;
    }

    public static function encodeJwtToken($payload)
    {
      if (!self::$KEY) throw new ExceptionJwtKeyNotProvidedException();
      $encoded = JWT::encode($payload, self::$KEY, self::$JWT_ALG);
      return $encoded;
    }

    public static function decodeJwtToken($token)
    {
      if (!self::$KEY) throw new ExceptionJwtKeyNotProvidedException();
      $decoded = JWT::decode($token, self::$KEY, array(self::$JWT_ALG));
      return $decoded;
    }

    public static function setKey($value)
    {
      self::$KEY = $value;
    }

    public static function getKey()
    {
      return self::$KEY;
    }
  }
}
