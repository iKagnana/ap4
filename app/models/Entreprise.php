<?php
require_once ("../app/core/Database.php");

class Entreprise extends User
{
    public $n_siret;
    public $codeAPE;

    private $db;

    /**
     * Constructor for the class user
     */
    public function __construct()
    {
        $this->db = new Database();
    }

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

        if (
            !isset($lastname) || !isset($firstname) || !isset($email) || !isset($password) || !isset($role) ||
            $lastname == "" || $firstname == "" || $email == ""
        ) {
            return ["error" => "Certains champs sont vides."];
        }

        if (!preg_match("/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/", $email)) {
            return ["error" => "L'email est invalide."];
        }
    }

    public function getEntrepriseInfo($userId)
    {
        try {
            $this->db->query("SELECT * FROM entreprise WHERE userId = :userId");
            $this->db->bind("userId", $userId);
            $result = $this->db->fetchAll();
        } catch (Exception $e) {
            return ["data" => [], "error" => "Nous n'avons pas pu récupérer les informations."];
        }

        return ["data" => $result[0]];
    }

    public function getUsers($searchName = null)
    {
        try {
            $this->db->query("SELECT * FROM entreprise JOIN users ON users.id_u = entreprise.id_u;");
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


            $entreprise = new Entreprise();
            $entreprise->setUser(
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

            $entreprise->n_siret = $result[$i]["n_siret"];
            $entreprise->codeAPE = $result[$i]["codeAPE"];

            array_push($allUsers, $entreprise);
        }

        return ["data" => $allUsers];

    }
}