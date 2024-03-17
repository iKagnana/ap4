<?php
require_once ("../app/models/User.php");

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

        if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
            return ["error" => "Le mot de passe doit contenir au minimum une majuscule, une minuscule, un chiffre et un spécial caractère. Il doit également avoir une longueur minimum de 8 caractères."];
        }

        if (isset ($_POST["type"]) && $_POST["type"] == "employee") {
            $enterprise = "GSB";
        }

        if (isset ($_POST["origin"]) && $_POST["origin"] == "user") {
            $check = $newUser->setUser($enterprise, $lastname, $firstname, $email, $password, $role);
            if (isset ($check["error"])) {
                $this->view("user/login-view", ["error" => $check["error"]]);
                return;
            }
            $res = $newUser->createUser();
            $this->view("user/login-view", ["error" => $res["error"] ?? null]);
        } else {
            $levelAccess = $_POST["levelAccess"];
            $check = $newUser->setUser($enterprise, $lastname, $firstname, $email, $password, $role, $levelAccess, "Validé");
            if (isset ($check["error"])) {
                $this->view("user/login-view", ["error" => $check["error"]]);
                return;
            }

            $res = $newUser->createUserAdmin();

            $this->index(["error" => $res["error"] ?? null]);
        }


    }


    ########## User list code
    /** method to nagivate to the list of users page
     * @param [] $extra
     */
    public function index($extra = null)
    {
        if (!$this->checkAccess("admin")) {
            $this->view("error/prohibited-view");
            return; # force exit
        }

        $filter = null;

        if (isset ($extra["filtered"])) {
            $users = $extra["filtered"];
            $filter = $extra["filter"];
        } else {
            $res = $this->user->getUsers();
            $users = $res["data"];
            $error = $res["error"] ?? null;
        }

        if (isset ($extra["error"])) {
            $error = $extra["error"];
        }

        if (isset ($extra["searchName"])) {
            $users = $this->user->filterUser($extra["searchName"], $users);
        }

        $this->view("user/handle/user-list-view", ["users" => $users, "filter" => $filter, "error" => $error]);

    }

    /** method to get filter result
     */
    public function filter()
    {
        if (!$this->checkAccess("admin")) {
            $this->view("error/prohibited-view");
            return; # force exit
        }

        $filter = $_GET["filter"] ?? "all";
        $searchName = $_GET["search"] ?? "";
        $res = $this->user->getUsers();
        $allUser = $res["data"];
        $error = $res["error"] ?? null;

        if ($filter == "waiting") {
            $allUser = $this->user->getWaitingUser($allUser);
        } else if ($filter == "valid") {
            $allUser = $this->user->getValideUser($allUser);
        } else if ($filter == "refused") {
            $allUser = $this->user->getRefusedUser($allUser);
        }

        $this->index(["filtered" => $allUser, "filter" => $filter, "searchName" => $searchName, "error" => $error]);
    }

    /** method to toggle details of an user
     */
    public function details()
    {
        if (!$this->checkAccess("admin")) {
            $this->view("error/prohibited-view");
            return; # force exit
        }

        $id = $_REQUEST["id"];
        $res = $this->user->getUsers();
        $allUsers = $res["data"];
        $error = $res["error"] ?? null;

        foreach ($allUsers as $user) {
            if ($user->id == $id) {
                $selected = $user;
                break;
            }
        }

        if (isset ($selected)) {
            $this->view("user/handle/user-details-view", ["selected" => $selected]);
        } else {
            $this->index(["error" => $error]);
        }
    }

    /** method to update the selected user
     */
    public function update()
    {
        if (!$this->checkAccess("admin")) {
            $this->view("error/prohibited-view");
            return; # force exit
        }

        $id = $_POST["id"];
        $enterprise = $_POST["enterprise"];
        $lastname = $_POST["lastname"];
        $firstname = $_POST["firstname"];
        $email = $_POST["email"];
        $role = $_POST["role"];
        $levelAccess = $_POST["levelAccess"];
        $status = $_POST["status"];

        $updateUser = new User();
        $updateUser->setUser($enterprise, $lastname, $firstname, $email, "", $role, $levelAccess, $status, $id);
        $res = $updateUser->patchUser();
        $error = $res["error"] ?? null;
        $this->index(["error" => $error]);
    }

    /** method to delete the selected user
     */
    public function delete()
    {
        if (!$this->checkAccess("admin")) {
            $this->view("error/prohibited-view");
            return; # force exit
        }

        $id = $_REQUEST["id"];
        $res = $this->user->deleteUser($id);
        $error = $res["error"] ?? null;

        $this->index(["error" => $error]);
    }


    /** method to navigate to the user form for the admin
     * 
     */
    public function form()
    {
        $this->view("user/handle/form-user-view");
    }
}