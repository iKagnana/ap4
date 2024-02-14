<?php
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

    /**
     * Constructor 
     * @param string $lastname
     * @param string $firstname
     * @param string $email 
     * @param string $password
     * @param int $role
     * @param int $id optionnal
     */
    public function __construct($lastname, $firstname, $email, $password, $role, $id = null)
    {
        $this->id = $id;
        $this->lastname = $lastname;
        $this->firstname = $firstname;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }
}