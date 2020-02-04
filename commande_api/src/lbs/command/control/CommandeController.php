<?php

namespace lbs\command\control;

use lbs\command\model\Commande;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use system\Json;

class CommandeController
{

    public function getCommand(Request $req, Response $resp, $args)
    {

        $resp = $resp->withHeader('Content-Type', 'application/json');

        $id = $args['id'];

            if(isset($_GET['token'])){
                $token = $_GET['token'];
            }else{
                $resp = $resp->withStatus(401);
                $resp->getBody()->write(Json::error(401, "Token non fournie"));
                return $resp;
            }

        $commande = Commande::find($id);

        if ($commande) {
            if($token == $commande->token || $_SERVER["HTTP_X_LBS_TOKEN"] == $commande->token){
                $resp->getBody()->write(Json::resource("commande", $commande->toArray()));
            }else{
                $resp = $resp->withStatus(401);
                $resp->getBody()->write(Json::error(401, "Token incorrect"));
            }

        } else {
            $resp = $resp->withStatus(404);
            $resp->getBody()->write(Json::error(404, "ressource non disponible"));
        }
        return $resp;
    }

    public function createCommand(Request $req, Response $resp, $args)
    {
        $resp = $resp->withHeader('Content-Type', 'application/json');

        $req_body = $req->getBody()->getContents();
        if (Json::isJson($req_body)) {
            $body = json_decode($req_body, true);
            $resp = $resp->withStatus(500);
            if (isset($body["mail"]) && isset($body["nom"]) && isset($body["livraison"])) {
                if (filter_var($body["mail"], FILTER_VALIDATE_EMAIL)) {
                    if ($body["nom"] != "") {
                        if ($body["livraison"] != "") {

                            if(isset($body["livraison"]["date"])){
                                if(isset($body["livraison"]["heure"])){

                                    try {
                                        $uuid = Uuid::uuid1();
                                    } catch (\Exception $e) {}

                                    $commande = new Commande();
                                    $commande->id = $uuid->toString();
                                    $commande->nom = filter_var($body["nom"], FILTER_SANITIZE_STRING);
                                    $commande->mail = filter_var($body["mail"], FILTER_SANITIZE_STRING);
                                    $commande->token = bin2hex(openssl_random_pseudo_bytes(32));
                                    $commande->montant = 0;
                                    $commande->livraison = $body["livraison"]["date"]." ".$body["livraison"]["heure"];


                                    if(isset($body["items"]) && is_array($body["items"])){
                                        $items = [];
                                        foreach ($body["items"] as $item){
                                            $commande->addItem($item);
                                            array_push($items, $item);
                                        }
                                    }

                                    $commande->save();
                                    $commande->items = $items;
                                    $resp->getBody()->write(Json::resource("commande", $commande->toArray()));

                                    $resp = $resp->withHeader("Location", "http://api.commande.local:19080/commands/" . $uuid->toString());
                                    $resp = $resp->withStatus(201);
                                }
                            }

                        } else {
                            $resp->getBody()->write(Json::error(500, "merci de transmettre une livraison valide"));
                        }
                    } else {
                        $resp->getBody()->write(Json::error(500, "merci de transmettre un nom valide"));
                    }
                } else {
                    $resp->getBody()->write(Json::error(500, "merci de transmettre un mail valide"));
                }
            } else {
                $resp->getBody()->write(Json::error(500, "merci de transmettre du nom, email et livraison"));
            }
        } else {
            $resp->getBody()->write(Json::error(500, "merci de transmettre du JSON valide"));
        }

        return $resp;
    }

    public function updateCommand(Request $req, Response $resp, $args)
    {
        $resp = $resp->withHeader('Content-Type', 'application/json');

        //Retourne une erreur 500 par défaut
        $resp = $resp->withStatus(500);

        $req_body = $req->getBody()->getContents();
        if (Json::isJson($req_body)) {
            $body = json_decode($req_body, true);

            if (isset($args["id"])) {
                $commande = Commande::find($args["id"]);

                if ($commande) {
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
                } else {
                    $resp->getBody()->write(Json::error(404, "Commande introuvable"));
                }
            } else {
                $resp->getBody()->write(Json::error(500, "Des données sont manquantes"));
            }
        }

        return $resp;
    }
}