<?php
require_once("../app/models/User.php");

class UserController extends Controller
{
    private $user; # used for function db 
    private $allUsers;

    public function __construct()
    {
        $this->user = $this->model("User");
        $this->allUsers = $this->user->getUsers();
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

            $allUsers = $this->user->getUsers();
            $this->allUsers = $allUsers;
            $this->index();
        }


    }


    ########## User list code
    /** method to nagivate to the list of users page
     * @param [] $extra
     */
    public function index($extra = null)
    {
        $sendData = $this->allUsers;
        $filter = null;

        if (isset($extra["filtered"])) {
            $sendData = $extra["filtered"];
            $filter = $extra["filter"];
        }

        if (isset($extra["searchName"])) {
            $sendData = $this->user->filterUser($extra["searchName"], $sendData);
        }

        $this->view("user/handle/user-list-view", ["users" => $sendData, "filter" => $filter]);

    }

    /** method to get filter result
     */
    public function filter()
    {
        $filter = $_GET["filter"] ?? "all";
        $searchName = $_GET["search"] ?? "";
        $allUser = $this->user->getUsers();

        if ($filter == "waiting") {
            $allUser = $this->user->getWaitingUser($allUser);
        } else if ($filter == "valid") {
            $allUser = $this->user->getValideUser($allUser);
        } else if ($filter == "refused") {
            $allUser = $this->user->getRefusedUser($allUser);
        }

        $this->index(["filtered" => $allUser, "filter" => $filter, "searchName" => $searchName]);
    }

    /** method to toggle details of an user
     */
    public function details()
    {
        $id = $_REQUEST["id"];

        foreach ($this->allUsers as $user) {
            if ($user->id == $id) {
                $selected = $user;
                break;
            }
        }

        if (isset($selected)) {
            $this->view("user/handle/user-details-view", ["selected" => $selected]);
        } else {
            $this->index();
            echo "Une erreur s'est produite.";
        }
    }

    /** method to update the selected user
     */
    public function update()
    {
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
        $updateUser->patchUser();

        $allUsers = $this->user->getUsers();
        $this->allUsers = $allUsers;

        $this->index();
    }

    /** method to delete the selected user
     */
    public function delete()
    {
        $id = $_REQUEST["id"];
        $this->user->deleteUser($id);

        $allUsers = $this->user->getUsers();
        $this->allUsers = $allUsers;
        $this->index();
    }


    /** method to navigate to the user form for the admin
     * 
     */
    public function form()
    {
        $this->view("user/handle/form-user-view");
    }
}