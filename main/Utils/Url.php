<?php

namespace App\Utils {
  use Psr\Http\Message\ResponseInterface as Response;
  class Url {
    public static function forNewLocation(string $newPath): string {
      $start = (array_key_exists('HTTPS', $_SERVER) && $_SERVER["HTTPS"] == "on") ? "https" : "http";
      $url = $start . "://" . $_SERVER['HTTP_HOST'] . "/$newPath" ;
      return $url;
    }

    public static function redirectedResponse(Response $response, string $newPath): Response {
      $newLocationUrl = self::forNewLocation($newPath);
      $response = $response->withHeader("Location", $newLocationUrl);
      return $response;
    }
  }
}
