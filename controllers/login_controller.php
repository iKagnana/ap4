<?php
$email = $_POST["email"] ?? "";
$password = $_POST["password"] ?? "";

# if field are not empty 
if ($email != "" && $password != "") {
    $res = $dbService->login($email, $password);
    if ($res) {
        echo "Vous êtes connecté.e";
    } else {
        echo "Connexion échouée";
    }
}
