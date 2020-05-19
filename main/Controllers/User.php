<?php

namespace App\Controllers {
  use Psr\Container\ContainerInterface;
  use Psr\Http\Message\ServerRequestInterface as Request;
  use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
  use Psr\Http\Message\ResponseInterface as Response;
  use Slim\Router as Router;
  

  class User {
    protected $container;
  
    public function __construct(ContainerInterface $container) {
      $this->container = $container;
    }

    public function auth(Request $request, Response $response, array $args) {
      return $this->container->get('view')->render($response, 'pages/ipAuth.twig');
    }
  
    public function ipAuthenticate(Request $request, RequestHandler $handler) {
      if ($request->hasHeader("Redirected")) {

      }
      // $response = $handler->handle($request);
      $response = $handler->handle($request);
      $start = (array_key_exists('HTTPS', $_SERVER) && $_SERVER["HTTPS"] == "on") ? "https" : "http";
      $url = $start . "://" . $_SERVER['HTTP_HOST'] . "/auth" ;
      echo $url;
      // header("Location: $url");
      $response = $response->withHeader("Location", $url);
      return $response;
      // return $this->router->dispatch('GET', '/');
      // return $this->container->get('view')->render($response, 'pages/ipAuth.twig');
    }
  
    public function home(Request $request, Response $response, array $args) {
      return $this->container->get('view')->render($response, 'pages/home.twig');
    }
  }
  
}