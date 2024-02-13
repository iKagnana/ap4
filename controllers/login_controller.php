<?php

switch ($_REQUEST["action"]) {
    case "goToLogin":
        include("views/login_view.php");
        break;
    case "askLogin":
        $email = $_POST["email"] ?? "";
        $password = $_POST["password"] ?? "";

        # if field are not empty 
        if ($email != "" && $password != "") {
            $res = $dbService->login($email, $password);
            if ($res) {
                $_SESSION["userId"] = $res;
                include("views/dashboard_view.php");
            } else {
                echo "Connexion échouée";
            }
        }
        break;
}

