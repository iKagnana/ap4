<?php
include("app/models/user.class.php");
$user = new User();

switch ($_REQUEST["action"]) {
    case "goToLogin":
        include("app/views/user/login_view.php");
        break;
    case "askLogin":
        $email = $_POST["email"] ?? "";
        $password = $_POST["password"] ?? "";

        # if field are not empty 
        if ($email != "" && $password != "") {
            $res = $user->login($email, $password);
            if ($res) {
                include("app/views/user/dashboard_view.php");
            } else {
                echo "Connexion échouée";
            }
        }
        break;
}

