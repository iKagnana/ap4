<?php
require_once("../app/core/Database.php");

/**
 * Class user
 */
class User
{
    public $id;
    public $lastname;
    public $firstname;
    public $email;
    public $password;
    public $role;
    public $enterprise;
    public $levelAccess;
    public $status;

    private $db;

    /**
     * Constructor for the class user
     */
    public function __construct()
    {
        $this->db = new Database();
    }


    /** Set user
     * @param string $enterprise 
     * @param string $lastname
     * @param string $firstname
     * @param string $email 
     * @param string $password
     * @param int|string $role
     * @param int|null $levelAccess optionnal
     * @param string|null $status optionnal
     * @param int|null $id optionnal
     * 
     */
    public function setUser($enterprise, $lastname, $firstname, $email, $password, $role, $levelAccess = null, $status = null, $id = null)
    {
        $this->id = $id;
        $this->lastname = $lastname;
        $this->firstname = $firstname;
        $this->email = $email;
        $this->password = $password;
        $this->levelAccess = $levelAccess;
        $this->status = $status ?? "En attente de validation";
        $this->enterprise = $enterprise;
        $this->role = $role;
    }


    /** Function that save into $_SESSION user's infos
     * $_SESSION["userId"]
     * $_SESSION["userLastname"]
     * $_SESSION["userFirstname"]
     * $_SESSION["userEmail"]
     * $_SESSION["userRole"]
     */
    public function saveInSession()
    {
        $_SESSION["userId"] = $this->id;
        $_SESSION["userLastname"] = $this->lastname;
        $_SESSION["userFirstname"] = $this->firstname;
        $_SESSION["userEmail"] = $this->email;
        $_SESSION["userRole"] = $this->role;
        $_SESSION["userLevelAccess"] = $this->levelAccess;
    }


    ### filters

    /** filter product by firstname, lastname or email
     * @param string $searchName
     * @param User[] $user
     * @return User[] filtered 
     */
    public function filterUser($searchName, $users)
    {
        return array_filter($users, function ($user) use ($searchName) {
            return str_contains(strtolower($user->lastname), strtolower($searchName)) ||
                str_contains(strtolower($user->firstname), strtolower($searchName)) ||
                str_contains(strtolower($user->email), strtolower($searchName));
        });
    }

    public function getValideUser($users)
    {
        return array_filter($users, function ($user) {
            return $user->status == "Validé";
        });
    }

    public function getWaitingUser($users)
    {
        return array_filter($users, function ($user) {
            return $user->status == "En attente de validation";
        });
    }

    public function getRefusedUser($users)
    {
        return array_filter($users, function ($user) {
            return $user->status == "Refusé";
        });
    }

    ################ Request to db

    ######### GET
    /** function to retreive user collection
     * 
     */
    public function getUsers()
    {
        try {
            $this->db->query("SELECT * FROM users");
            $result = $this->db->fetchAll();
        } catch (Exception $e) {
            return ["data" => [], "error" => "Nous n'avons pas pu récupérer les utilisateurs pour le moment."];
        }

        $allUsers = [];
        for ($i = 0; $i < count($result); $i++) {
            $role = "";

            if ($result[$i]["role"] == 0) {
                $role = "Administrateur";
            } else if ($result[$i]["role"] == 1) {
                $role = "Utilisateur";
            } else if ($result[$i]["role"] == 2) {
                $role = "Client";
            }


            $user = new User();
            $user->setUser(
                $result[$i]["enterprise"],
                $result[$i]["lastname_u"],
                $result[$i]["firstname_u"],
                $result[$i]["email_u"],
                "",
                $role,
                $result[$i]["level_access"],
                $result[$i]["status"],
                $result[$i]["id_u"],
            );

            array_push($allUsers, $user);
        }

        return ["data" => $allUsers];

    }


    ######### POST

    /** function that will be used for user login
     * @param string
     * @param string
     * @return [] "data" = true if connected
     */
    public function login($email, $password)
    {
        try {
            $this->db->query("SELECT * FROM users WHERE email_u=:email");
            $this->db->bind("email", $email);
            $result = $this->db->fetch();
        } catch (Exception $e) {
            return ["data" => false, "error" => "Une erreur est survenue lors de la connexion."];
        }

        # prevent user to login when his status is not valid
        if ($result["status"] == "En attente de validation") {
            return ["data" => false, "error" => "L'administrateur n'a pas encore validé votre compte."];
        } else if ($result["status"] == "Refusé") {
            return ["data" => false, "error" => "L'administrateur a refusé votre demande"];
        }

        if (count($result) != 0 && password_verify($password, $result[4])) {
            $user = new User();
            $user->setUser(
                $result["enterprise"],
                $result["lastname_u"],
                $result["firstname_u"],
                $result["email_u"],
                "", # place for the password
                $result["role"],
                $result["level_access"],
                $result["status"],
                $result["id_u"],
            );
            $user->saveInSession();
            return ["data" => true];
        } else {
            return ["data" => false, "error" => "Vos identifiants sont invalides."];
        }

    }

    /** function to create account 
     * 
     */
    function createUser()
    {
        try {
            $this->db->query("INSERT INTO users (lastname_u, firstname_u, email_u, password, role, status, enterprise) VALUES (:lastname, :firstname, :email, :password, :role, :status, :client_name)");
            $this->db->bind("lastname", $this->lastname);
            $this->db->bind("firstname", $this->firstname);
            $this->db->bind("email", $this->email);
            $this->db->bind("role", $this->role);
            $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
            $this->db->bind("password", $hashedPassword);
            $this->db->bind("client_name", $this->enterprise ?? "");
            $this->db->bind("status", $this->status);
            $this->db->fetch();
        } catch (Exception $e) {
            return ["error" => "Impossible d'ajouter un utilisateur pour le moment."];
        }
    }

    /** function to create account as an admin
     * 
     */
    function createUserAdmin()
    {
        try {
            $this->db->query("INSERT INTO users (lastname_u, firstname_u, email_u, password, role, status, enterprise, level_access) VALUES (:lastname, :firstname, :email, :password, :role, :status, :client_name, :level_access)");
            $this->db->bind("lastname", $this->lastname);
            $this->db->bind("firstname", $this->firstname);
            $this->db->bind("email", $this->email);
            $this->db->bind("role", $this->role);
            $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
            $this->db->bind("password", $hashedPassword);
            $this->db->bind("client_name", $this->enterprise ?? "");
            $this->db->bind("status", $this->status);
            $this->db->bind("level_access", $this->levelAccess);
            $this->db->fetch();
        } catch (Exception $e) {
            return ["error" => "Impossible d'ajouter un utilisateur pour le moment."];
        }
    }

    ######### PATCH
    /** function to patch a user
     * 
     */
    function patchUser()
    {
        try {
            $this->db->query("UPDATE users SET enterprise = :enterprise, lastname_u = :lastname, firstname_u = :firstname, email_u = :email, role = :role, level_access = :levelAccess, status = :status WHERE id_u = :id");
            $this->db->bind("id", $this->id);
            $this->db->bind("enterprise", $this->enterprise);
            $this->db->bind("lastname", $this->lastname);
            $this->db->bind("firstname", $this->firstname);
            $this->db->bind("email", $this->email);
            $this->db->bind("role", $this->role);
            $this->db->bind("levelAccess", $this->levelAccess);
            $this->db->bind("status", $this->status);
            $this->db->fetch();
        } catch (Exception $e) {
            return ["error" => "Impossible de modifier un utilisateur pour le moment."];
        }

    }


    ######### DELETE
    /** function to delete a user
     * @param int $id
     */
    function deleteUser($id)
    {
        try {
            $this->db->query("DELETE FROM users WHERE id_u = :id");
            $this->db->bind("id", $id);
            $this->db->fetch();
        } catch (Exception $e) {
            $errorType = $this->db->getTypeError($e->getCode());
            $errorMsg = $errorType == ErrorType::IsFK ? "L'utilisateur est associé à une commande. Il ne peut pas être supprimé." : "L'utilisateur n'a pas pu être supprimé";
            return ["error" => $errorMsg];
        }
    }
}