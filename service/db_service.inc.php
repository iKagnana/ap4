<?php
/**
 * Class meant to be use for connection to the db and making request
 * selfService is the unique instance for the class. Use method getSelfService to get the value
 */

include("models/user_model.php");
include("models/product_model.php");
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
     * @return DbService
     */
    public static function getSelfService()
    {
        if (self::$selfService === null) {
            self::$selfService = new DbService();
        }
        return self::$selfService;
    }

    /** function that will be used for user login
     * @param string
     * @param string
     * @return bool true if connected
     */
    public function login($email, $password)
    {

        try {
            $req = "SELECT * FROM users WHERE email_u=:email and password=:password";
            $stmt = DbService::$service->prepare($req);
            $stmt->execute(["email" => $email, "password" => $password]);

            $result = $stmt->fetch();
            if (count($result) > 0) {
                $user = new User(
                    $result[1],
                    $result[2],
                    $result[3],
                    $result[4],
                    $result[5],
                    $result[0],
                );

                $_SESSION["user"] = $user;
                return true;
            } else {
                return false;
            }

        } catch (PDOException $exception) {
            echo "Une erreur est survenue lors de la connexion" . $exception->getMessage();
            return false;
        }
    }

    ############ PRODUCTS

    /** Function to retreive all the products 
     */
    public function getProducts()
    {
        try {
            $req = "SELECT id_p, name_p, stock, price, access_level, name_cat FROM products INNER JOIN categories WHERE products.id_cat = categories.id_cat";
            $stmt = DbService::$service->prepare($req);
            $stmt->execute();
            $result = $stmt->fetchAll();

            $allProducts = [];
            for ($i = 0; $i < count($result); $i++) {
                $product = new Product(
                    $result[$i]["name_p"],
                    $result[$i]["price"],
                    $result[$i]["stock"],
                    $result[$i]["access_level"],
                    $result[$i]["name_cat"],
                    $result[$i]["id_p"],
                );
                array_push($allProducts, $product);
            }
            return $allProducts;
        } catch (PDOException $exception) {
            echo "Couldn't get products beacause of error : " . $exception->getMessage();
            return [];
        }
    }

    /** Function to create product ressource
     * @param Product
     */
    public function createProduct($data)
    {
        try {
            $req = "INSERT INTO products (name_p, price, stock, access_level, id_cat) VALUES (:name, :price, :stock, :access_level, :cat)";
            $stmt = DbService::$service->prepare($req);
            $stmt->execute(["name" => $data->name, "price" => $data->price, "stock" => $data->stock, "access_level" => $data->access_level, "cat" => $data->category]);
            $result = $stmt->fetch();
            return true;
        } catch (PDOException $exception) {
            echo "Couldn't add product because of error :" . $exception->getMessage();
            return false;
        }
    }

    public function getCategories()
    {
        try {
            $req = "SELECT * FROM categories";
            $stmt = DbService::$service->prepare($req);
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $result;
        } catch (PDOException $exception) {
            echo "Couldn't get categories : " . $exception->getMessage();
        }
    }
}