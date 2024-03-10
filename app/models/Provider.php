<?php
require_once("../app/core/Database.php");

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
    }

    /** search through provider and return result 
     * @param string $searchName
     * @param Provider[] $allProvider
     */
    public function searchProvider($searchName, $allProvider)
    {
        return array_filter($allProvider, function ($provider) use ($searchName) {
            return str_contains($provider->name, $searchName) || str_contains($provider->email, $searchName);
        });
    }

    ################ Request to db

    ######### GET
    /** function to get provider 
     * 
     */
    public function getProvider()
    {
        try {
            $this->db->query("SELECT * FROM provider");
            $result = $this->db->fetchAll();

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

            return $allProvider;
        } catch (Exception $e) {
            echo "Couldn't get provider because of error :" . $e->getMessage();
            return [];
        }
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
            echo "Couldn't create provider " . $e->getMessage();
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
            echo "Couldn't update provider" . $e->getMessage();
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
            echo "Couldn't delete provider " . $e->getMessage();
        }
    }
}