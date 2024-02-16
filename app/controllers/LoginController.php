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

    /** Connect user
     * 
     */
    public function askLogin()
    {
        $email = $_POST["email"] ?? "";
        $password = $_POST["password"] ?? "";

        # if field are not empty 
        if ($email != "" && $password != "") {
            $res = $this->user->login($email, $password);
            if ($res && $_SESSION["userId"] != null) {
                $this->view("user/dashboard-view", ["lastname" => $_SESSION["userLastname"], "firstname" => $_SESSION["userFirstname"]]);
            } else {
                echo "Connexion échouée";
            }
        }
    }

    /** Logout the user
     * 
     */
    public function logout()
    {
        session_unset();
        $this->view("user/login-view");
    }
}

