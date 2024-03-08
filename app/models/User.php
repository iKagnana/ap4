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

    private $db;

    /**
     * Constructor for the class user
     */
    public function __construct()
    {
        $this->db = new Database();
    }


    /** Set user
     * @param string $lastname
     * @param string $firstname
     * @param string $email 
     * @param string $password
     * @param int $role
     * @param int $levelAccess
     * @param int $id optionnal
     */
    public function setUser($lastname, $firstname, $email, $password, $role, $id = null, $enterprise = null, $levelAccess = null)
    {
        $this->id = $id;
        $this->lastname = $lastname;
        $this->firstname = $firstname;
        $this->email = $email;
        $this->password = $password;
        $this->levelAccess = $levelAccess;
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
    }

    ################ Request to db

    /** function that will be used for user login
     * @param string
     * @param string
     * @return bool true if connected
     */
    public function login($email, $password)
    {
        try {
            $this->db->query("SELECT * FROM users WHERE email_u=:email");
            $this->db->bind("email", $email);
            $result = $this->db->fetch();
            if (count($result) != 0 && password_verify($password, $result[4])) {
                $user = new User();
                $user->setUser(
                    $result[1],
                    $result[2],
                    $result[3],
                    $result[4],
                    $result[5],
                    $result[0]
                );
                $user->saveInSession();
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo "Une erreur est survenue lors de la connexion" . $e->getMessage();
            return false;
        }
    }

    ######### POST

    /** function to create account 
     * 
     */
    function createUser()
    {
        try {
            $this->db->query("INSERT INTO users (lastname_u, firstname_u, email_u, password, role, isValid, client_name) VALUES (:lastname, :firstname, :email, :password, :role, :isValid, :client_name)");
            $this->db->bind("lastname", $this->lastname);
            $this->db->bind("firstname", $this->firstname);
            $this->db->bind("email", $this->email);
            $this->db->bind("role", $this->role);
            $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
            $this->db->bind("password", $hashedPassword);
            $this->db->bind("isValid", 0);
            $this->db->bind("client_name", $this->enterprise ?? "");
            $this->db->fetch();
        } catch (Exception $e) {
            echo "Could add user error :" . $e->getMessage();
        }
    }
}