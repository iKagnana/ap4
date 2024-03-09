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
    public function account($extra = null)
    {
        $this->view("user/create-account-view");
    }

    public function create()
    {
        $newUser = new User();
        $lastname = $_POST["lastname"];
        $firstname = $_POST["firstname"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $enterprise = $_POST["enterprise"];
        $role = $_POST["role"];

        if (isset($_POST["type"]) && $_POST["type"] == "employee") {
            $enterprise = "GSB";
        }

        if (isset($_POST["origin"]) && $_POST["origin"] == "user") {
            $newUser->setUser($enterprise, $lastname, $firstname, $email, $password, $role);
            $newUser->createUser();
            $this->view("user/login-view");
        } else {
            $levelAccess = $_POST["levelAccess"];
            $newUser->setUser($enterprise, $lastname, $firstname, $email, $password, $role, $levelAccess, "Validé");
            $newUser->createUserAdmin();
            $this->index();
        }


    }


    ########## User list code
    /** method to nagivate to the list of users page
     * @param [] $extra
     */
    public function index($extra = null)
    {
        $allUsers = $this->user->getUsers();
        $data = ["users" => $allUsers];

        if (isset($extra["searchName"])) {
            $sendData = $this->user->filterUser($extra["searchName"], $allUsers);
            $this->view("user/handle/user-list-view", ["users" => $sendData]);
        } else {
            $this->view("user/handle/user-list-view", $data);
        }

    }

    public function search()
    {
        $searchName = $_GET["search"];
        $this->index(["searchName" => $searchName]);
    }


    /** method to navigate to the user form for the admin
     * 
     */
    public function form()
    {
        $this->view("user/handle/form-user-view");
    }
}