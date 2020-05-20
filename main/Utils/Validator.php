<?php

namespace App\Utils {
  use \App\Utils\Exception\IPInvalidException;
  class Validator {
    static public function throwIfInvalidIP(string $ipAddress) 
    {
      $isValid = filter_var($ipAddress, FILTER_VALIDATE_IP);
      if(!$isValid) {
        throw new IPInvalidException();
      }
    }
  }
}