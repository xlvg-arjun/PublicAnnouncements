<?php

namespace App\Controllers {

  use Psr\Container\ContainerInterface;
  use Psr\Http\Message\ServerRequestInterface as Request;
  use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
  use Psr\Http\Message\ResponseInterface as Response;

  use \AppConfig\EntityManager as EntityManager;
  use App\Utils\Auth as AppAuth;
    use Models\Comment;
    use Models\Post as PostModel;

class Post {
    protected $container;

    public function __construct(ContainerInterface $container)
    {
      $this->container = $container;
    }

    public function all(Request $request, Response $response, array $args) {
      $em = EntityManager::getEntityManager();
      // $query = $em->createQuery("SELECT p FROM \Models\Post p");
      // $posts = $query->getResult();
      // $posts = "";
      $posts = $em->getRepository("\Models\Post")->findAll();
      // print_r($posts);
      return $this->container->get('view')->render($response, 'pages/posts.twig', [ 'posts' => $posts ]);
      // return $response;
    }

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

    public function makeComment(Request $request, Response $response, array $args) {
      $body = $request->getParsedBody();
      list('commentText' => $commentText, 'postId' => $post_id) = $body;
      $user_id = AppAuth::getSession();
      $em = EntityManager::getEntityManager();
      $query = $em->createQuery("SELECT p FROM \Models\Post p WHERE p.id = :post_id")->setParameter('post_id', $post_id);
      $post = $query->getSingleResult();
      $query = $em->createQuery("SELECT u FROM \Models\User u WHERE u.id = :user_id")->setParameter('user_id', $user_id);
      $user = $query->getSingleResult();
      $new_comment = new Comment($commentText);
      $new_comment->setUser($user);
      $post->addComment($new_comment);
      $em->persist($user);
      $em->persist($post);
      $em->persist($new_comment);
      $em->flush();
      $body = $response->getBody();
      $body->write("Success");
      return $response->withBody($body);
    }
  }
}