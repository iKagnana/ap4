<?php
include("../app/models/User.php");
require_once("../app/core/Controller.php");

class LoginController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->user = $this->model("User");
    }
    public function index()
    {
        $this->view("user/login-view");
    }

    public function askLogin()
    {
        $email = $_POST["email"] ?? "";
        $password = $_POST["password"] ?? "";

        # if field are not empty 
        if ($email != "" && $password != "") {
            $res = $this->user->login($email, $password);
            if ($res) {
                $this->view("user/dashboard-view");
            } else {
                echo "Connexion échouée";
            }
        }
    }
}

