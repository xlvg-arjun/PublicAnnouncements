<?php

namespace App\Controllers {

  use Psr\Container\ContainerInterface;
  use Psr\Http\Message\ServerRequestInterface as Request;
  use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
  use Psr\Http\Message\ResponseInterface as Response;

  use \AppConfig\EntityManager as EntityManager;
  use App\Utils\Auth as AppAuth;
  use Models\Post as PostModel;

class Post {
    public function view(Request $request, Response $response, array $args) {
      echo "I will save you";
      return $response;
    }

    public function create(Request $request, Response $response, array $args) {
      $body = $request->getParsedBody();
      list('postTitle' => $title, 'postMainText' => $mainText) = $body;
      $user_id = AppAuth::getSession();
      $new_post = new PostModel($title, $mainText);
      $em = EntityManager::getEntityManager();
      $query = $em->createQuery("SELECT u FROM \Models\User u WHERE u.id = :user_id")->setParameter('user_id', $user_id);
      $user = $query->getSingleResult();
      $new_post->setUser($user);
      $user->addPost($new_post);
      $em->persist($new_post);
      $em->persist($user);
      $em->flush();
      $body = $response->getBody();
      $body->write("Success");
      return $response->withBody($body);
    }
  }
}