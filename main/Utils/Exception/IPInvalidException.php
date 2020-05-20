<?php

namespace App\Utils\Exception {
  class IPInvalidException extends \Exception
  {
    public function __construct($message = "Provided IP address is invalid", $code = 0, \Exception $previous = null)
    {
      parent::__construct($message, $code, $previous);
    }
  }
}
