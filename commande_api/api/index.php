<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use src\controllers\CommandeController;
use system\Guzzle;
use system\Json;

require '../src/vendor/autoload.php';


define('ROOTPATH', dirname(__FILE__)."/../");

$config_slim = ['settings' => ['displayErrorDetails' => true]];
$config_illuminate = parse_ini_file("conf/db.ini");

$db = new Illuminate\Database\Capsule\Manager();

$db->addConnection($config_illuminate);
$db->setAsGlobal();
$db->bootEloquent();

Guzzle::init();

$c = new\Slim\Container($config_slim);
$app = new \Slim\App($c);

$app->get('/commands[/]', "\lbs\command\control\CommandeController:list");
$app->post('/commands[/]', "\lbs\command\control\CommandeController:createCommand");
$app->get('/commands/{id}', '\lbs\command\control\CommandeController:get');
$app->put('/commands/{id}', '\lbs\command\control\CommandeController:update');

$c['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $response->withStatus(400)
            ->withHeader('Content-Type', 'application/json')
            ->write(Json::error(400, "requête mal formée"));
    };
};

$c['notAllowedHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $response->withStatus(405)
            ->withHeader('Content-Type', 'application/json')
            ->write(Json::error(405, "methode non disponible"));
    };
};
/*
$c['errorHandler'] = function ($c) {
    return function ($request, $response) use ($c) {

        return $response->withStatus(500)
            ->withHeader('Content-Type', 'application/json')
            ->write(Json::error(500, "erreur serveur"));
    };
};
*/
$app->run();