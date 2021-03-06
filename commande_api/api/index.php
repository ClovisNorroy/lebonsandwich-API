<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use system\Guzzle;
use system\Json;
use \Respect\Validation\Validator as v;
use \DavidePastore\Slim\Validation\Validation as Validation;

require '../src/vendor/autoload.php';
$validators = [
    'nom' => v::StringType()->alpha()->length(1, null),
    'prenom' => v::StringType()->alpha(),
    'mail' => v::email(),
    'livraison' => [
        'date' => v::date('Y-m-d')->min('now'),
        'heure' => v::date('G:i')
    ],
    'client_id' => v::optional(v::numeric()),
    'items' => v::notOptional()->arrayType()
];

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

$foo = new \lbs\command\control\CommandeController();
$app->post('/commands[/]', "\lbs\command\control\CommandeController:createCommand")->add(new Validation($validators));
$app->get('/commands/{id}', '\lbs\command\control\CommandeController:getCommand');
$app->put('/commands/{id}', '\lbs\command\control\CommandeController:updateCommand');
$app->get('/clients/{id}[/]', '\lbs\command\control\ClientController:getClientCard');
$app->post('/clients/{id}/auth', '\lbs\command\control\ClientController:authClient');

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

$app->run();