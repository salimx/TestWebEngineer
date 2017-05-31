<?php
declare(strict_types = 1);
use TestWebEngineer\JsonApi\Controller\TrackApiController;
use TestWebEngineer\JsonApi\Controller\FavoriteApiController;
use TestWebEngineer\JsonApi\Controller\UserApiController;

use TestWebEngineer\SimpleRouter\Router;

require_once '../app/autoload.php';

$routingFile = '../app/config/routing.ini';
if (! $routing = parse_ini_file($routingFile, true)) {
    throw new \Exception('Unable to open ' . $routingFile . '.');
}

$configsFile = '../app/config/config.ini';
if (! $configs = parse_ini_file($configsFile, true)) {
    throw new \Exception('Unable to open ' . $configsFile . '.');
}

try {
    $trackApiController = new TrackApiController($configs);
    $favoriteApiController = new FavoriteApiController($configs);
    $userApiController = new UserApiController($configs);

    $router = new Router($routing, [
        'TrackApiController' => $trackApiController,
        'FavoriteApiController' => $favoriteApiController,
        'UserApiController' => $userApiController
    ]);
    $router->execute($_SERVER['PATH_INFO'], $_SERVER['REQUEST_METHOD']);
} catch (Exception $e) {
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
