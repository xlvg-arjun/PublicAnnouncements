<?php
namespace AppConfig {
  // require_once('load_env_values.php');

  class EntityManager {
    public static $internal_entityManager = null;

    private static function setInternalEntityManager(): void
    {
      $paths = array(__DIR__ . "/../entities");
      $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration($paths);
  
      $connectionParams = array(
        'driver' => 'pdo_mysql',
        'user' => getenv('DB_USER'),
        'password' => getenv('DB_PASSWORD'),
        'dbname' => getenv('DB_DBNAME'),
      );
  
      self::$internal_entityManager = \Doctrine\ORM\EntityManager::create($connectionParams, $config);
    }

    public static function getEntityManager(): \Doctrine\ORM\EntityManager
    {
      if (self::$internal_entityManager == null) {
        self::setInternalEntityManager();
      }
      return self::$internal_entityManager;
    }
  }
}