<?php

namespace lbs\command\control;

use lbs\command\model\Client;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Firebase\JWT\JWT;
use system\Json;

class ClientController
{

    public function getClientCard(Request $req, Response $resp, $args){
        $resp = $resp->withHeader('Content-Type', 'application/json');

        if(isset($args["id"])){
            $client = Client::find($args["id"]);
            if($client){

                $token = explode(" ", $req->getHeader("Authorization")[0])[1];
                $tokenDecoded = JWT::decode($token, "lul", array('HS512'));
                $client = Client::find($tokenDecoded->id);
                $resp->getBody()->write(Json::resource("client", $client->toArray()));
            }else{
                $resp->getBody()->write(Json::error(404, "Le client est introuvable."));
            }
        }else{
            $resp->getBody()->write(Json::error(404, "Merci d'entrer un identifiant valide."));
        }

        return $resp;
    }

    public function authClient(Request $req, Response $resp, $args)
    {
        $resp = $resp->withHeader('Content-Type', 'application/json');

        //Retourne une erreur 401 par défaut
        $resp = $resp->withStatus(401);

        if($req->getHeader("Authorization")){
            $auth = explode(" ", $req->getHeader("Authorization")[0]);
            $auth = explode(":", base64_decode($auth[1]));

            if (isset($args["id"])) {
                $user = Client::find($args["id"]);
                if ($user){
                    if(password_verify($auth[1], $user->passwd)){
                        $resp = $resp->withStatus(200);
                        $token = JWT::encode( [ 'id'=> $args["id"] ],'lul', 'HS512' );
                        $resp->getBody()->write(json_encode(["token" => $token]));
                    }else{
                        $resp->getBody()->write(Json::error(401, "incorrect password"));
                    }
                } else {
                    $resp->getBody()->write(Json::error(404, "Client introuvable"));
                }
            } else {
                $resp->getBody()->write(Json::error(500, "Des données sont manquantes"));
            }

        }else{
            $resp->getBody()->write(Json::error(401, "no authorization header present"));
        }



        return $resp;
    }

}