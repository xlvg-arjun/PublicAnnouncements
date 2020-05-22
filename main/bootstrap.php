<?php

use Slim\Exception\NotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use App\Controllers\User as UserController;
use App\Controllers\Post as PostController;
use App\Utils\Auth as AppAuth;
// use App\Router as AppRouter;

require_once(__DIR__ . "/../vendor/autoload.php");
require_once(__DIR__ . "/../config/load_env_values.php");

AppAuth::setKey(getenv('JWT_KEY'));

$container = new Container();
AppFactory::setContainer($container);

$container->set('view', function() {
  return Twig::create(__DIR__ . "/../templates");
});

$app = AppFactory::create();

// $appRouter = new AppRouter($app);

$app->add(TwigMiddleware::createFromContainer($app));
// $app->get('/', UserController::class . ':home')->add(UserController::class . ':ipAuthenticate');
// $app->get('/auth', UserController::class . ':auth');
// $app->get('/check-db', UserController::class . ':check');
// $app->post('/login', UserController::class . ':login');
// $app->get('/profile', UserController::class . ':profile');
// $app->post('/register', UserController::class . ':register');
// $app->post('/post', PostController::class . ':create');
// $app->get('/auth', UserController::class . ':handleAuth');

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
  $group->get('/all', PostController::class . ':all');
})->add(UserController::class . ':checkAuth');


// $app->get('/', function (Request $request, Response $response, array $args) {
  // $response->getBody()->write("Hello world!");
  // return $response;
// });

// $app->

$app->run();
