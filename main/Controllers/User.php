<?php

// use Slim\Views\Twig as Twig;
use \Psr\Container\ContainerInterface;

namespace App\Controllers;

class User {
  protected $container;

  public function __construct(\Psr\Container\ContainerInterface $container) {
    $this->container = $container;
  }

  public function home($request, $response, $args) {
    return $this->container->get('view')->render($response, 'pages/home.twig');
  }
}
