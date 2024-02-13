<?php
/**
 * Class meant to be use for connection to the db and making request
 * selfService is the unique instance for the class. Use method getSelfService to get the value
 */

class DbService
{
    private static $host = "mysql:host=db";
    private static $dbName = "dbname=ap4";
    private static $user = "user";
    private static $password = "pass";
    private static $service; # PDO
    private static $selfService = null; # contains DbService object 

    # Encapsulation 
    private function __construct()
    {
        $dsn = DbService::$host . ";" . DbService::$dbName;
        try {
            DbService::$service = new PDO($dsn, DbService::$user, DbService::$password);
        } catch (PDOException $exception) {
            echo "Une erreur est survenu lors de la connection à la base :" . $exception->getMessage();
        }
    }

    /**
     * Get selfService, only instance for this class
     */
    public static function getSelfService()
    {
        if (self::$selfService === null) {
            self::$selfService = new DbService();
        }
        return self::$selfService;
    }

    /** function that will be used for user login
     * @param $email
     * @param $password
     * @return bool true if connected
     */
    public function login($email, $password)
    {

        try {
            $req = "SELECT id_u FROM users WHERE email_u=:email and password=:password";
            $stmt = DbService::$service->prepare($req);
            $stmt->execute(["email" => $email, "password" => $password]);

            $result = $stmt->fetch();
            if (count($result) > 0) {
                return true;
            } else {
                return false;
            }

        } catch (PDOException $exception) {
            echo "Une erreur est survenue lors de la connexion" . $exception->getMessage();
            return false;
        }
    }
}
?>