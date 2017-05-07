<?php

use Router\Router;
use Symfony\Component\Yaml\Yaml;

session_start();

require __DIR__ . '/vendor/autoload.php';

$publicConfig = Yaml::parse(file_get_contents('config/config.yml'));
$privateConfig = Yaml::parse(file_get_contents('config/private.yml'));

$loader = new Twig_Loader_Filesystem('views/');
$twig = new Twig_Environment($loader, array(//todo activate cache, deactivate debug BEFORE FINAL COMMIT! IMPORTANT!!!!
    // 'cache' => 'cache/twig/',
    'cache' => false,
    'debug' => true
));
$twig->addExtension(new Twig_Extension_Debug());//todo activate cache, deactivate debug BEFORE FINAL COMMIT! IMPORTANT!!!!

$router = new Router($publicConfig['routes'], $twig);
if (empty($_GET['action']))
{
    $_GET['action'] = $publicConfig['defaut_route'];
}

$router->callAction($_GET['action']);
