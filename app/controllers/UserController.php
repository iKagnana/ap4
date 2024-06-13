<?php
require_once ("../app/models/User.php");

class UserController extends Controller
{
    private $user; # used for function db
    private $entreprise; # used for function db
    private $particulier; # used for function db

    public function __construct()
    {
        $this->user = $this->model("User");
        $this->entreprise = $this->model("Entreprise");
        $this->particulier = $this->model("Particulier");
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
        $role = $_POST["role"] ?? null;

        if (isset($_POST["role"]) && $_POST["role"] == 1) {
            echo "ehfziefhzuie";
            $enterprise = "GSB";
        }

        if (isset($_POST["origin"]) && $_POST["origin"] == "user") {
            $check = $newUser->setUser($enterprise, $lastname, $firstname, $email, $password, $role);
            if (isset($check["error"])) {
                $this->view("user/create-account-view", ["error" => $check["error"], "form" => $newUser]);
                return;
            }
            if (!preg_match("/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}/", $password)) {
                $error = "Le mot de passe doit contenir au minimum une majuscule, une minuscule, un chiffre et un spécial caractère. Il doit également avoir une longueur minimum de 8 caractères.";
                $this->view("user/create-account-view", ["error" => $error, "form" => $newUser]);
                return;
            }
            $res = $newUser->createUser();
            $this->view("user/login-view", ["error" => $res["error"] ?? null]);
        } else {
            $levelAccess = $_POST["levelAccess"];
            $check = $newUser->setUser($enterprise, $lastname, $firstname, $email, $password, $role, $levelAccess, "Validé");
            if (isset($check["error"])) {
                $this->form(["error" => $check["error"], "form" => $newUser]);
                return;
            }

            if (!preg_match("/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}/", $password)) {
                $error = "Le mot de passe doit contenir au minimum une majuscule, une minuscule, un chiffre et un spécial caractère. Il doit également avoir une longueur minimum de 8 caractères.";
                $this->form(["error" => $error, "form" => $newUser]);
                return;
            }
            $res = $newUser->createUserAdmin();
            $error = $res["error"] ?? null;

            $this->index(["error" => $error ?? null]);
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

        if (isset($extra["filter"]) && $extra["filter"] != "all") {
            $res = $this->user->getUsersByStatus($extra["filter"], $extra["searchName"] ?? null);
            $users = $res["data"];
            $error = $res["error"] ?? null;
        } else {
            $res = $this->user->getUsers($extra["searchName"] ?? null);
            $users = $res["data"];
            $error = $res["error"] ?? null;
        }

        $resEntreprise = $this->entreprise->getUsers($extra["searchName"] ?? null);
        $resParticulier = $this->particulier->getUsers($extra["searchName"] ?? null);
        $entreprises = $resEntreprise["data"];
        $particuliers = $resParticulier["data"];

        if (isset($extra["error"])) {
            $error = $extra["error"];
        }

        $this->view("user/handle/user-list-view", [
            "entreprises" => $entreprises,
            "particuliers" => $particuliers,
            "filter" => $filter,
            "error" => $error,
            "searchName" => $extra["searchName"] ?? null
        ]);

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

        $this->index(["filter" => $filter, "searchName" => $searchName]);
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

        if (isset($selected)) {
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
    public function form($extra = null)
    {
        if (isset($extra["form"])) {
            $newUser = $extra["form"];
        }
        $this->view("user/handle/form-user-view", ["error" => $extra["error"] ?? null, "form" => $newUser ?? null]);
    }
}