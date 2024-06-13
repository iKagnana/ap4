<?php
require_once ("../app/core/Database.php");

class Particulier extends User
{
    public $metier;

    private $db;

    /**
     * Constructor for the class user
     */
    public function __construct()
    {
        $this->db = new Database();
    }

    public function getEntrepriseInfo($userId)
    {
        try {
            $this->db->query("SELECT * FROM particulier WHERE userId = :userId");
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
            $this->db->query("SELECT * FROM particulier JOIN users ON users.id_u = particulier.id_u;");
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


            $entreprise = new Particulier();
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

            $entreprise->metier = $result[$i]["metier"];

            array_push($allUsers, $entreprise);
        }

        return ["data" => $allUsers];

    }
}