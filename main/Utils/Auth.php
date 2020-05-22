<?php

namespace App\Utils {

  use \Firebase\JWT\JWT;

  use App\Utils\Exception\JwtKeyNotProvidedException as ExceptionJwtKeyNotProvidedException;
  use Psr\Http\Message\ResponseInterface as Response;

  class Auth
  {
    private static $KEY = null;
    private static $JWT_ALG = 'HS256';
    public static function setSession($user_id): void
    {
      session_start();
      $_SESSION['user_id'] = $user_id;
    }

    public static function getSession(): string
    {
      return $_SESSION['user_id'];
    }

    public static function isAuth(): bool
    {
      session_start();
      if (isset($_SESSION['user_id']) && $_SESSION['user_id']) {
        return true;
      } else {
        return false;
      }
      // return true;
    }

    public static function logout(Response $response, string $redirectPath = "auth"): Response
    {
      session_start();
      $_SESSION['user_id'] = null;
      session_destroy();
      return Url::redirectedResponse($response, $redirectPath);
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
