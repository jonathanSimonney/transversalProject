<?php

use Router\Router;
use Symfony\Component\Yaml\Yaml;

session_start();

require __DIR__ . '/vendor/autoload.php';

$publicConfig = Yaml::parse(file_get_contents('config/config.yml'));
$privateConfig = Yaml::parse(file_get_contents('config/private.yml'));

$loader = new Twig_Loader_Filesystem('views/');
$twig = new Twig_Environment($loader, array(
    // 'cache' => 'cache/twig/',
    'cache' => false,
));

$router = new Router($publicConfig['routes'], $twig);
if (!empty($_GET['action']))
    $router->callAction($_GET['action']);
else
    $router->callAction($publicConfig['defaut_route']);
