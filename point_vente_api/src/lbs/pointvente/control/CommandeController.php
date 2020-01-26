<?php

namespace lbs\pointvente\control;

use lbs\pointvente\model\Commande;
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


    public function list(Request $req, Response $resp, $args)
    {
        $resp = $resp->withHeader('Content-Type', 'application/json');
        $commandes = Commande::select("id", "nom", "created_at", "livraison", "status");
        $count_commandes = Commande::all()->count();
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

    public function get(Request $req, Response $resp, $args)
    {
        $resp = $resp->withHeader('Content-Type', 'application/json');

        $id = $args['id'];

        $commande = Commande::find($id);

        if ($commande) {

            $resp->getBody()->write(Json::resource("commande", $commande->toArray()));
        } else {
            $resp = $resp->withStatus(404);
            $resp->getBody()->write(Json::error(404, "ressource non disponible"));

        }

        return $resp;
    }
}