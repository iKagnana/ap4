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
        require ("../app/css/style.css");
        require ("../app/css/header.css");
        require ("../app/css/user.css");
        require ("../app/css/form.css");
        ?>
    </style>
    <header>
        <?php
        $host = getenv("HOST");
        if (isset ($_SESSION["userId"])) {
            echo "
            <div class='menu'>
                <div class='menu-item'><a href='$host/home'>Accueil</a></div>";
            if ($_SESSION["userRole"] < 2) {
                echo "<div class='menu-item'><a href='$host/product'>
                Stocks</a><div class='dropdown-menu'>";


                echo "<a href='$host/product'>Consulter</a>";
                echo "<a href='$host/product/form'>Ajouter</a>";

                echo "</div>
                </div>";
            }
            # clear seperation
            echo "<div class='menu-item'><a href='$host/order'>
               Commande</a>";
            echo "<div class='dropdown-menu'><a href='$host/order'>Consulter</a>";
            echo "<a href='$host/order/form'>Ajouter</a>";
            if ($_SESSION["userRole"] == 0) {
                echo "<a href='$host/order/control'>Contrôler</a>";
            }
            echo "</div>
                </div>";
            # clear seperation
            if ($_SESSION["userRole"] == 0) {
                echo "<div class='menu-item'><a href='$host/user'>
               Utilisateurs</a>";
                echo "<div class='dropdown-menu'><a href='$host/user'>Consulter</a>";
                echo "<a href='$host/user/form'>Ajouter</a>";
                echo "</div>
                </div>";
            }
            # clear seperation
            if ($_SESSION["userRole"] == 0) {
                echo "<div class='menu-item'><a href='$host/provider'>
               Fournisseurs</a>";
                echo "<div class='dropdown-menu'><a href='$host/provider'>Consulter</a>";
                echo "<a href='$host/provider/form'>Ajouter</a>";
                echo "</div>
                </div>";
            }
            # clear seperation
            echo "<div class='menu-item'><a href='$host/login'>Déconnexion</a></div>
            </div>
        ";
        }
        ?>
    </header>