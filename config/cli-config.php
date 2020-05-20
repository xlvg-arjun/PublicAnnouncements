<?php
require_once('load_env_values.php');
require_once('EntityManager.php');

$entityManager = AppConfig\EntityManager::getEntityManager();
return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);