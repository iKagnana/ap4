<?php
require_once ("../app/core/Database.php");

class Provider
{
    public $id;
    public $name;
    public $email;

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /** set provider
     * @param string $name
     * @param string $email
     * @param int|null $id optionnal
     */
    public function setProvider($name, $email, $id = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->id = $id;
        if (!isset ($name) || !isset ($email) || $name == "" || $email == "") {
            return ["error" => "Certains champs sont vides."];
        }

        if (!preg_match("/[\w\-\.]+@([\w-]+\.)+[\w-]{2,4}/", $email)) {
            return ["error" => "L'email est invalide."];
        }
    }

    ################ Request to db

    ######### GET
    /** function to get provider 
     * @param string $searchName
     */
    public function getProvider($searchName = null)
    {
        try {
            $this->db->query("SELECT * FROM provider WHERE name_pro LIKE :searchName or email_pro LIKE :searchName");
            $this->db->bind("searchName", "%" . $searchName . "%");
            $result = $this->db->fetchAll();
        } catch (Exception $e) {
            return ["data" => [], "error" => "Impossible de récupérer les fournisseurs."];
        }

        $allProvider = [];
        for ($i = 0; $i < count($result); $i++) {

            $provider = new Provider();
            $provider->setProvider(
                $result[$i]["name_pro"],
                $result[$i]["email_pro"],
                $result[$i]["id_pro"],
            );

            array_push($allProvider, $provider);
        }

        return ["data" => $allProvider];

    }

    /** function that return the provider name with its id 
     * @param int $id
     */
    public function getProviderId($id)
    {
        try {
            $this->db->query("SELECT name_pro FROM provider WHERE id_pro = :id");
            $this->db->bind("id", $id);
            $result = $this->db->fetch();
        } catch (Exception $e) {
            echo "Could get provider name " . $e->getMessage();
            return ["data" => "", "error" => "Nous n'avons pas pu récupérer le fournisseur."];
        }

        return ["data" => $result];
    }

    ######### POST

    /** method add provider in db 
     * 
     */
    public function createProvider()
    {
        try {
            $this->db->query("INSERT INTO provider (name_pro, email_pro) VALUES (:name, :email)");
            $this->db->bind("name", $this->name);
            $this->db->bind("email", $this->email);
            $this->db->fetch();
        } catch (Exception $e) {
            return ["error" => "Impossible d'ajouter ce fournisseur pour le moment."];
        }
    }

    ######### PATCH
    /** method to patch provider
     * 
     */
    public function updateProvider()
    {
        try {
            $this->db->query("");
            $this->db->bind("name", $this->name);
            $this->db->bind("email", $this->email);
            $this->db->bind("id", $this->id);
            $this->db->fetch();
        } catch (Exception $e) {
            return ["error" => "Impossible de mettre à jour ce fournisseur pour le moment."];
        }
    }

    ######### DELETE
    /** method to delete provider
     * @param int $id
     */
    function deleteProvider($id)
    {
        try {
            $this->db->query("");
            $this->db->bind("id", $id);
            $this->db->fetch();
        } catch (Exception $e) {
            $errorType = $this->db->getTypeError($e->getCode());
            $errorMsg = $errorType == ErrorType::IsFK ? "Le fournissseur est associé à une commande. Il ne peut pas être supprimé." : "Le fournisseur n'a pas pu être supprimé";
            return ["error" => $errorMsg];
        }
    }
}

