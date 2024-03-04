<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GSB</title>

</head>

<body>
    <!-- In order to get the style -->
    <style>
        <?php
        require("../app/css/style.css");
        require("../app/css/header.css");
        ?>
    </style>
    <header>
        <?php
        if (isset($_SESSION["userId"])) {
            echo "
            <div class='menu'>
                <div class='menu-item'><a href='http://localhost:8089/home'>Accueil</a></div>";
            echo "<div class='menu-item'><a href='http://localhost:8089/product'>
                Stocks</a><div class='dropdown-menu'>";

            if ($_SESSION["userRole"] < 2) {
                echo "<a href='http://localhost:8089/product'>Consulter</a>";
            }
            if ($_SESSION["userRole"] == 0) {
                echo "<a href='http://localhost:8089/product/form'>Ajouter</a>";
            }
            echo "</div>
                </div>";
            # clear seperation
            echo "<div class='menu-item'><a href='http://localhost:8089/order'>
               Commande</a>";
            echo "<div class='dropdown-menu'><a href='http://localhost:8089/order'>Consulter</a>";
            echo "<a href='http://localhost:8089/order/form'>Ajouter</a>";
            if ($_SESSION["userRole"] == 0) {
                echo "<a>Contrôler</a>";
            }
            echo "</div>
                </div>";
            # clear seperatiob
            echo "<div class='menu-item'><a href='http://localhost:8089/login/logout'>Déconnexion</a></div>
            </div>
        ";
        }
        ?>
    </header>