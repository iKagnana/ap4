<?php
include("app/core/db.class.php");

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
     * @param int $id optionnal
     */
    public function setUser($lastname, $firstname, $email, $password, $role, $id = null)
    {
        $this->id = $id;
        $this->lastname = $lastname;
        $this->firstname = $firstname;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
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
            $this->db->query("SELECT * FROM users WHERE email_u=:email and password=:password");
            $this->db->bind("email", $email);
            $this->db->bind("password", $password);
            $result = $this->db->fetch();
            if (count($result) > 0) {
                $user = new User();
                $user->setUser(
                    $result[1],
                    $result[2],
                    $result[3],
                    $result[4],
                    $result[5],
                    $result[0]
                );
                $_SESSION["user"] = $user;
                echo $_SESSION["user"]->lastname;
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo "Une erreur est survenue lors de la connexion" . $e->getMessage();
            return false;
        }
    }
}