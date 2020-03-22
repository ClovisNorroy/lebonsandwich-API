<?php

$endpoints = [
    "Commande" => [
        "create_command" => [
            "title" => "Créer une commande",
            "method" => "POST",
            "url" => "/commands",
            "data" => [
                ["name" => "nom", "type" => "String", "desc" => "Nom du client", "required" => true],
                ["name" => "mail", "type" => "String", "desc" => "Email du client", "required" => true],
                ["name" => "livraison date", "type" => "date", "desc" => "Date de livraison", "required" => true],
                ["name" => "livraison heure", "type" => "time", "desc" => "Heure de livraison", "required" => true],
                ["name" => "items", "type" => "array", "desc" => "Liste des items", "required" => true],
            ]
        ],
        "get_command" => [
            "title" => "Récupérer une commande",
            "method" => "GET",
            "url" => "/commands/{id}",
            "data" => [
                ["name" => "id", "type" => "Integer", "desc" => "ID de la commande", "required" => true],
            ]
        ],
        "edit_command" => [
            "title" => "Modifier une commande",
            "method" => "PUT",
            "url" => "/commands/{id}",
            "data" => [
                ["name" => "id", "type" => "Integer", "desc" => "ID de la commande", "required" => true],
            ]
        ],
        "get_client" => [
            "title" => "Récupérer un client",
            "method" => "GET",
            "url" => "/clients/{id}",
            "data" => [
                ["name" => "id", "type" => "Integer", "desc" => "ID du client", "required" => true],
            ]
        ],
        "auth_client" => [
            "title" => "Authentifier un client",
            "method" => "POST",
            "url" => "/clients/{id}/auth",
            "data" => [
                ["name" => "id", "type" => "Integer", "desc" => "ID du client", "required" => true],
                ["name" => "username", "type" => "Header Basic Authentification", "desc" => "Adresse email du client", "required" => true],
                ["name" => "password", "type" => "Header Basic Authentification", "desc" => "Mot de passe du client", "required" => true],
            ]
        ]
    ],

    "Catalogue" => [
        "list_sandwich" => [
            "title" => "Lister les sandwichs",
            "method" => "GET",
            "url" => "/sandwichs"
        ],
        "get_sandwich" => [
            "title" => "Récupérer les informations d'un sandwich",
            "method" => "GET",
            "url" => "/sandwichs/{ref}",
            "data" => [
                ["name" => "ref", "type" => "String", "desc" => "Référence du sandwich", "required" => true],
            ]
        ],
        "get_category" => [
            "title" => "Récupérer une catégorie",
            "method" => "GET",
            "url" => "/categories/{id}",
            "data" => [
                ["name" => "id", "type" => "Integer", "desc" => "ID de la catégorie", "required" => true],
            ]
        ],
        "get_sandwich_by_categorie" => [
            "title" => "Récupérer les sandwichs d'une catégorie",
            "method" => "GET",
            "url" => "/categories/{id}/sandwichs",
            "data" => [
                ["name" => "id", "type" => "Integer", "desc" => "ID de la catégorie", "required" => true],
            ]
        ]
    ],
    "Point de vente" => [
        "list_command" => [
            "title" => "Lister les commandes",
            "method" => "GET",
            "url" => "/commands"
        ],
        "get_command" => [
            "title" => "Récupérer une commande",
            "method" => "GET",
            "url" => "/commands/{id}",
            "data" => [
                ["name" => "id", "type" => "Integer", "desc" => "ID de la commande", "required" => true],
            ]
        ]
    ]
];