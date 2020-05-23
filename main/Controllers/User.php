<?php

namespace App\Controllers {

  use Psr\Container\ContainerInterface;
  use Psr\Http\Message\ServerRequestInterface as Request;
  use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
  use Psr\Http\Message\ResponseInterface as Response;
  use \AppConfig\EntityManager as EntityManager;
  use \App\Utils\Url as Url;
  use \App\Utils\Auth as AppAuth;

  use \Models\User as UserModel;
  use Slim\Psr7\Response as Psr7Response;

class User
  {
    protected $container;

    public function __construct(ContainerInterface $container)
    {
      $this->container = $container;
    }

    public function auth(Request $request, Response $response, array $args)
    {
      // echo "What";
      return $this->container->get('view')->render($response, 'pages/auth.twig');
    }

    public function profile(Request $request, Response $response, array $args)
    {
      return $this->container->get('view')->render($response, 'pages/profile.twig');
    }

    public function register(Request $request, Response $response, array $args)
    {
      $body = $request->getParsedBody();
      $username = $body['signupUsername'];
      $password = $body['signupPassword'];
      $password_hashed = password_hash($password, PASSWORD_BCRYPT, ["cost" => 12]);
      $user = new UserModel($username, $password_hashed);
      $em = EntityManager::getEntityManager();
      $em->persist($user);
      $em->flush();
      $response = Url::redirectedResponse($response, "profile");
      return $response;
    }

    public function login(Request $request, Response $response, array $args)
    {
      $body = $request->getParsedBody();
      $username = $body['loginUsername'];
      $password = $body['loginPassword'];
      $em = EntityManager::getEntityManager();
      $query = $em->createQuery("SELECT u from \Models\User u WHERE u.username = :username");
      $query->setParameter('username', $username);
      $user = $query->getSingleResult();
      $comparison_result = password_verify($password, $user->getPassword());
      if ($comparison_result) {
        AppAuth::setSession($user->getId());
        $response = Url::redirectedResponse($response, "profile");
      } else {
        $response = Url::redirectedResponse($response, "auth");
      }
      return $response;
    }

    public function viewUser(Request $request, Response $response, array $args)
    {
      $user_id = $args['user_id'];
      if (AppAuth::isAuth() && (AppAuth::getSession() == $user_id)) {
        $response = Url::redirectedResponse($response, 'profile');
        return $response;
      }
      $em = EntityManager::getEntityManager();
      $query = $em->createQuery("SELECT u FROM \Models\User u WHERE u.id = :user_id")->setParameter('user_id', $user_id);
      $user = $query->getSingleResult();
      return $this->container->get('view')->render($response, 'pages/view_user.twig', ['user' => $user]);
    }

    public function checkAuth(Request $request, RequestHandler $requestHandler)
    {
      if(AppAuth::isAuth()) {
        $response = $requestHandler->handle($request);
      } else {
        $response = new Psr7Response();
        $response = Url::redirectedResponse($response, "auth");
      }
      // $response = new Psr7Response();
      // $response = Url::redirectedResponse($response, "auth");
      return $response;
    }

    public function logout(Request $request, Response $response, array $args)
    {

    }

    public function check(Request $request, Response $response, array $args)
    {
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

    public function ipAuthenticate(Request $request, RequestHandler $handler)
    {
      if ($request->hasHeader("Redirected")) {
      }
      // $response = $handler->handle($request);
      $response = $handler->handle($request);
      $start = (array_key_exists('HTTPS', $_SERVER) && $_SERVER["HTTPS"] == "on") ? "https" : "http";
      $url = $start . "://" . $_SERVER['HTTP_HOST'] . "/auth";
      echo $url;
      // header("Location: $url");
      $response = $response->withHeader("Location", $url);
      return $response;
      // return $this->container->get('view')->render($response, 'pages/ipAuth.twig');
    }

    public function home(Request $request, Response $response, array $args)
    {
      return $this->container->get('view')->render($response, 'pages/home.twig');
    }
  }
}
