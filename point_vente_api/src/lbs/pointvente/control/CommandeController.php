<?php

namespace lbs\pointvente\control;

use \lbs\pointvente\model\Command;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use system\Json;

class CommandeController
{
    protected $categories;
    protected $sandwichs;

    public function __construct()
    {
        $mongo = new \MongoDB\Client("mongodb://dbcat");
        $mongoDB = $mongo->catalogue;
        $this->categories = $mongoDB->categories;
        $this->sandwichs = $mongoDB->sandwichs;
    }

    public function listCommands(Request $req, Response $resp, $args)
    {
        $resp = $resp->withHeader('Content-Type', 'application/json');
        $commandes = Command::select("id", "nom", "created_at", "livraison", "status");
        $count_commandes = Command::all()->count();
        if (isset($_GET["s"]) && is_numeric($_GET["s"])) {
            $commandes = $commandes->where("status", "=", $_GET["s"]);
        }

        if (isset($_GET["page"])) {
            if (isset($_GET["size"])) {
                $size = $_GET["size"];
            } else {
                $size = 10;
            }

            if ($_GET["page"] > ceil($count_commandes / $size)) {
                $page = ceil($count_commandes / $size);
            } elseif ($_GET["page"] > 0) {
                $page = $_GET["page"];
            } else {
                $page = 1;
            }
        } else {
            $page = 1;
            $size = 10;
        }

        $commandes = $commandes->skip(($page - 1) * $size)->take($size);


        $new_commandes = [];
        foreach ($commandes->get()->toArray() as $commande) {
            $new_commandes[] = ["commande" => $commande, "links" => ["href" => "/commandes/" . $commande["id"]]];
        }

        $resp->getBody()->write(Json::collection("commandes", $new_commandes, $count_commandes));
        return $resp;
    }

    public function getCommand(Request $req, Response $resp, $args)
    {
        $resp = $resp->withHeader('Content-Type', 'application/json');

        $commande = Command::find($args['id']);

        if(isset($_GET['token']) || isset($_SERVER["HTTP_X_LBS_TOKEN"])){
            $token = $_GET['token'];
        }else{
            $resp = $resp->withStatus(401);
            $resp->getBody()->write(Json::error(401, "Token non fournie"));
            return $resp;
        }
        if($token != $commande->token && $_SERVER["HTTP_X_LBS_TOKEN"] != $commande->token){
            $resp = $resp->withStatus(401);
            $resp->getBody()->write(Json::error(401, "Token incorrect"));
        }

        if ($commande) {
            $itemsCommand = [];
            foreach($commande->Items as $item){
                $detailItem = $this->sandwichs->findOne(["nom" => $item->libelle]);
                array_push($itemsCommand, $detailItem);
            }
            $commande->items = $itemsCommand;

            $resp->getBody()->write(Json::resource("command", [
                "links"=> [
                    "self"=>"/commands/".$args['id']."/",
                    "items"=>"/commands/".$args['id']."/items/"
                ],
                "command" => $commande->toArray()
            ]));
        } else {
            $resp = $resp->withStatus(404);
            $resp->getBody()->write(Json::error(404, "ressource non disponible"));
        }

        return $resp;
    }
}