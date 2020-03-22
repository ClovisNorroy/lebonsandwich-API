<?php require_once "endpoints.php"; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Documentation API</title>
    <meta charset="UTF-8" />
    <style>
        body{
            display: flex;
            justify-content: center;
            font-family: Arial;
        }
        .container {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }

        @media (min-width: 576px) {
            .container {
                max-width: 540px;
            }
        }

        @media (min-width: 768px) {
            .container {
                max-width: 720px;
            }
        }

        @media (min-width: 992px) {
            .container {
                max-width: 960px;
            }
        }

        @media (min-width: 1200px) {
            .container {
                max-width: 1140px;
            }
        }

        .badge{
            color: white;
            padding: 0.5rem;
        }

        .badge-primary{
            background: gray;
        }

        .badge-secondary{
            background: lightslategray;
        }

        .url{
            background: dimgray;
            color: white;
            padding: 1rem;
            border-radius: 0.3rem;
        }

        ul li a{
            color: black;
            text-decoration: none;
        }

        ul li a:hover{
            color: dimgray;
        }

        table{
            width: 100%;
        }

        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        th, td {
            padding: 5px;
            text-align: left;
        }
        .prettyprint{
            padding: 1rem;
            border-radius: 0.3rem;
        }
        .action{
            margin-top: 3rem;
            border-bottom: 1px solid black;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Documentation de l'API "LeBonSandwich"</h1>
    <p>Sauf mention contraire, tous les paramètres doivent être passés en JSON.</p>
    <h2>Actions</h2>
   <?php foreach ($endpoints as $name_endpoint => $endpoint){
       echo "<h3>".$name_endpoint."</h3>";
       echo "<ul>";
       foreach ($endpoint as $name_action => $action){
           echo '<li><a href="#'.$name_action.'">'.$action["title"].'</a></li>';
       }
       echo "</ul>";
   }
   ?>
<?php foreach ($endpoints as $name_endpoint => $endpoint){
        echo "<h2>".$name_endpoint."</h2>";

        foreach ($endpoint as $name_action => $action){ ?>
            <div id="<?= $name_action; ?>" class="action">
                <h3><?= $action["title"]; ?></h3>
                <span class="badge badge-secondary"><?= $action["method"]; ?></span>
                <pre class="url"><?= $action["url"]; ?></pre>
                <?php if(isset($action["data"])){ ?>
                <h4>Paramètres</h4>
                <table>
                    <tr>
                        <th>Champ</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Obligatoire</th>
                    </tr>
                    <?php foreach ($action["data"] as $data){ ?>
                    <tr>
                        <td><?= $data["name"]; ?></td>
                        <td><?= $data["type"]; ?></td>
                        <td><?= $data["desc"]; ?></td>
                        <td><?= $data["required"] ? 'Oui' : 'Non'; ?></td>
                    </tr>
                    <?php } ?>
                </table>
                <?php } ?>
            </div>

        <?php }

    }
?>

</div>
<script src="https://cdn.jsdelivr.net/gh/google/code-prettify@master/loader/run_prettify.js"></script>
</body>
</html>