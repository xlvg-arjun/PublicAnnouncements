<?php

namespace App\Controllers {
  use Psr\Container\ContainerInterface;
  use Psr\Http\Message\ServerRequestInterface as Request;
  use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
  use Psr\Http\Message\ResponseInterface as Response;  
  use \AppConfig\EntityManager as EntityManager;
  use \App\Utils\Validator as Validator;

  use \Vectorface\Whip\Whip;

  use \Models\User as UserModel;

  class User {
    protected $container;
  
    public function __construct(ContainerInterface $container) {
      $this->container = $container;
    }

    public function auth(Request $request, Response $response, array $args) {
      return $this->container->get('view')->render($response, 'pages/auth.twig');
    }

    public function handleAuth(Request $request, Response $response, array $args) {
      // $body = $request->getParsedBody();
      $whip = new Whip();
      $body = $request->getParsedBody();
      $username = $body["username"];
      $password = $body["password"];
      echo $username;
      echo $password;
      // $user = new UserModel($username, $ip);
      return $response;
    }

    public function check(Request $request, Response $response, array $args) {
      $body = $response->getBody();
      try {
        $em = EntityManager::getEntityManager();
        $em->getConnection()->connect();
        $body = $response->getBody();
        $body->write("Success");
      } catch (\Exception $e) {
        // print_r($e);
        $body->write("Failure");
      } finally {
        return $response->withBody($body);
      }
      // $name = getenv('DB_DBNAME');
      // $body = $response->getBody();
      // $body->write($name);
      // return $response->withBody($body);
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
      // return $this->container->get('view')->render($response, 'pages/ipAuth.twig');
    }
  
    public function home(Request $request, Response $response, array $args) {
      return $this->container->get('view')->render($response, 'pages/home.twig');
    }
  }
  
}