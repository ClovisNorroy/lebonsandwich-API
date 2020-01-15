<?php
namespace lbs\command\control;

use lbs\command\model\Commande;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use system\Json;

class CommandeController{


    public function list(Request $req, Response $resp, $args){
        $resp = $resp->withHeader('Content-Type', 'application/json');
        $commandes = Commande::select("id", "nom", "created_at", "livraison", "status");

        if(isset($_GET["s"]) && is_numeric($_GET["s"])){
            $commandes = $commandes->where("status", "=", $_GET["s"]);
        }

        if(isset($_GET["page"]) && is_numeric($_GET["page"])){
            if($_GET["page"] > 0){
                $page = $_GET["page"];
            }else{
                $page = 1;
            }
                if(isset($_GET["size"]) & is_numeric($_GET["size"])){
                    $commandes = $commandes->skip(($page-1)*$_GET["size"])->take($_GET["size"]);
                }else{
                    $commandes = $commandes->skip(($page-1)*10)->take(10);
                }

        }

        $new_commandes = [];
        foreach ($commandes->get()->toArray() as $commande){
            $new_commandes[] = ["commande" => $commande, "links" => ["href" => "/commandes/".$commande["id"]]];
        }

        $resp->getBody()->write(Json::collection("commandes", $new_commandes, Commande::all()->count())) ;
        return $resp;
    }

    public function get(Request $req, Response $resp, $args){
        $resp = $resp->withHeader('Content-Type', 'application/json');

        $id = $args['id'];

        $commande = Commande::find($id);

        if($commande){

            $resp->getBody()->write(Json::resource("commande", $commande->toArray()));
        }else{
            $resp = $resp->withStatus(404);
            $resp->getBody()->write(Json::error(404, "ressource non disponible"));

        }

        return $resp;
    }

    public function create(Request $req, Response $resp, $args){
        $resp = $resp->withHeader('Content-Type', 'application/json');

        $req_body = $req->getBody()->getContents();
        if(Json::isJson($req_body)){
            $body = json_decode($req_body, true);
            $resp = $resp->withStatus(500);

            if(isset($body["mail"]) && isset($body["nom"]) && isset($body["livraison"])){
                if(filter_var($body["mail"], FILTER_VALIDATE_EMAIL)){
                    if($body["nom"] != ""){
                        if($body["livraison"] != ""){

                            $uuid = Uuid::uuid1();

                            $commande = new Commande();
                            $commande->id = $uuid->toString();
                            $commande->nom = htmlspecialchars($body["nom"]);
                            $commande->mail = htmlspecialchars($body["mail"]);
                            $commande->livraison = htmlspecialchars($body["livraison"]);
                            $commande->save();

                            $resp->getBody()->write(Json::resource("commande", $commande->toArray()));
//$this->c['router' ]->pathFor('commande', ['id'=>$commande->id])
                            $resp = $resp->withHeader("Location" , "http://api.commande.local:19080/commandes/".$uuid->toString());
                            $resp = $resp->withStatus(201);

                        }else{
                            $resp->getBody()->write(Json::error(500, "merci de transmettre une livraison valide"));
                        }
                    }else{
                        $resp->getBody()->write(Json::error(500, "merci de transmettre un nom valide"));
                    }
                }else{
                    $resp->getBody()->write(Json::error(500, "merci de transmettre un mail valide"));
                }
            }else{
                $resp->getBody()->write(Json::error(500, "merci de transmettre du nom, email et livraison"));
            }
        }else{
            $resp->getBody()->write(Json::error(500, "merci de transmettre du JSON valide"));
        }

        return $resp;
    }

    public function update(Request $req, Response $resp, $args){
        $resp = $resp->withHeader('Content-Type', 'application/json');

        //Retourne une erreur 500 par défaut
        $resp = $resp->withStatus(500);

        $req_body = $req->getBody()->getContents();
        if(Json::isJson($req_body)) {
            $body = json_decode($req_body, true);

            if(isset($args["id"])){
                $commande = Commande::find($args["id"]);

                if($commande){
                    $commande->livraison = htmlspecialchars($body["livraison"]);
                    $commande->nom = htmlspecialchars($body["nom"]);
                    $commande->mail = htmlspecialchars($body["mail"]);
                    $commande->montant = htmlspecialchars($body["montant"]);
                    $commande->remise = htmlspecialchars($body["remise"]);
                    $commande->token = htmlspecialchars($body["token"]);
                    $commande->client_id = htmlspecialchars($body["client_id"]);
                    $commande->ref_paiement = htmlspecialchars($body["ref_paiement"]);
                    $commande->date_paiement = htmlspecialchars($body["date_paiement"]);
                    $commande->mode_paiement = htmlspecialchars($body["mode_paiement"]);
                    $commande->status = htmlspecialchars($body["status"]);
                    $commande->save();
                    $resp = $resp->withStatus(200);
                    $resp->getBody()->write(Json::resource("commande", $commande->toArray()));
                }else{
                    $resp->getBody()->write(Json::error(404, "Commande introuvable"));
                }
            }else{
                $resp->getBody()->write(Json::error(500, "Des données sont manquantes"));
            }
        }

        return $resp;
    }
}