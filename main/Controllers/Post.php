<?php

namespace App\Controllers {
  use Psr\Container\ContainerInterface;
  use Psr\Http\Message\ServerRequestInterface as Request;
  use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
  use Psr\Http\Message\ResponseInterface as Response;
  use \AppConfig\EntityManager as EntityManager;

  class Post {
    public function create(Request $request, Response $response, array $args) {
      $body = $request->getParsedBody();
      list('title' => $title, 'mainText' => $mainText) = $body;
      
    }
  }
}