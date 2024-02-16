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
        <?php require("../app/css/style.css"); ?>
    </style>
    <header>
        <?php
        if (isset($_SESSION["userId"])) {
            echo "
            <ul>
                <li><a href='http://localhost:8089/home'>Accueil</a></li>
                <li><a href='http://localhost:8089/product'>Stocks</a></li>
                <li><a>Commandes</a></li>
                <li><a href='http://localhost:8089/login/logout'>Déconnexion</a></li>
            </ul>
        ";
        }
        ?>
    </header>