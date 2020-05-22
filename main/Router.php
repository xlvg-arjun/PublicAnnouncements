<?php

namespace App {

  use Slim\Views\TwigMiddleware;
  use Middlewares\TrailingSlash;
  use App\Controllers\User as UserController;
  use App\Controllers\Post as PostController;
  use Slim\Routing\RouteCollectorProxy;
  use Psr\Http\Message\ServerRequestInterface;
  use Psr\Log\LoggerInterface;
  use Slim\Factory\AppFactory;
    use Slim\Logger;
    use Slim\Psr7\Response;

  class Router
  {
    public function __construct(\Slim\App $app)
    {
      $customErrorHandler = function (
        ServerRequestInterface $request,
        \Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails,
        ?LoggerInterface $logger = null
      ) use ($app) {
        if($logger){
          $logger->error($exception->getMessage());
        }
    
        $payload = ['error' => $exception->getMessage()];
    
        $response = $app->getResponseFactory()->createResponse();
        $response->getBody()->write(
          json_encode($payload, JSON_UNESCAPED_UNICODE)
        );
    
        return $response;
      };

      $app->add(new TrailingSlash(true));
      $app->add(TwigMiddleware::createFromContainer($app));

      // $app->get('/check-db', UserController::class . ':check');
      // $app->get('/profile', UserController::class . ':profile')->add(UserController::class . ':checkAuth');      
      // $app->post('/post', PostController::class . ':create')->add(UserController::class . ':checkAuth');
      
      $app->group('', function (RouteCollectorProxy $group) {
        $group->get('/', UserController::class . ':home');
        $group->get('/auth', UserController::class . ':auth');

        $group->post('/login', UserController::class . ':login');
        $group->post('/register', UserController::class . ':register');
      });

      $app->group('', function (RouteCollectorProxy $group) {
        $group->get('/profile', UserController::class . ':profile');
      })->add(UserController::class . ':checkAuth');

      $app->group('/post', function (RouteCollectorProxy $group) {
        $group->get('', PostController::class . ':view');
        $group->post('', PostController::class . ':create');
      })->add(UserController::class . ':checkAuth');
      // $app->get('/')
      // $app->get('/auth', UserController::class . ':auth');

      $errorMiddleware = $app->addErrorMiddleware(true, true, true, new Logger());
      $errorMiddleware->setDefaultErrorHandler($customErrorHandler);
      // $app->add($customErrorHandler);
    }
  }
}
