<?php
require_once("../app/models/User.php");

class UserController extends Controller
{
    private $user; # used for function db 

    public function __construct()
    {
        $this->user = $this->model("User");
    }

    /** page to create an account
     * @param $extra
     */
    public function index($extra = null)
    {
        $this->view("user/create-account-view");
    }

    public function create()
    {
        $lastname = $_POST["lastname"];
        $firstname = $_POST["firstname"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $enterprise = $_POST["enterprise"];
        $role = 1;

        if (isset($_POST["type"]) && $_POST["type"] == "client") {
            $role = 2;
        } else if (isset($_POST["type"]) && $_POST["type"] == "employee") {
            $enterprise = "GSB";
        }

        $newUser = new User();
        $newUser->setUser($enterprise, $lastname, $firstname, $email, $role, $password);
        $newUser->createUser();

        $this->view("user/login-view");
    }
}