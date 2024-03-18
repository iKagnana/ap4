<?php
require_once ("../app/core/Database.php");
class Product
{
    public $id;
    public $name;
    public $price;
    public $stock;
    public $access_level;
    public $category;

    # used for order
    public $quantity = 1;
    public $totalPrice = 0;

    private $db;
    private $openState; # to handle update and delete state for view



    /** Constructor for the class product
     */
    public function __construct()
    {
        $this->db = new Database();
    }

    /** Set product
     * @param string $name
     * @param double $price
     * @param int $stock
     * @param int $access_level
     * @param []|string $category
     * @param int $id optionnal
     */
    public function setProduct($name, $price, $stock, $access_level, $category, $id = null)
    {
        # check if set
        if (!isset ($name) || !isset ($price) || !isset ($stock) || !isset ($access_level) || !isset ($category)) {
            return ["error" => "Certains champs sont vides."];
        }

        # check if not empty or 0
        if ($name == "" || $price == 0) {
            return ["error" => "Certains champs sont invalides"];
        }

        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->stock = $stock;
        $this->access_level = $access_level;
        $this->category = $category;
        $this->openState = false;
    }

    /** Method used when we want to order 
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        $this->totalPrice = $quantity * $this->price;
    }

    /** filter product by name
     * @param string $searchName
     * @param Product[] $products
     * @return Product[] filtered 
     */
    public function filterProduct($searchName, $products)
    {
        return array_filter($products, function ($product) use ($searchName) {
            return str_contains($product->name, $searchName);
        });
    }

    ################ Request to db

    ######### GET
    /** Function to retreive all the products 
     * @return Product[]
     */
    public function getProducts()
    {
        try {
            $this->db->query("SELECT products.*, name_cat FROM products INNER JOIN categories ON products.id_cat = categories.id_cat");
            $result = $this->db->fetchAll();
        } catch (PDOException $exception) {
            $error = "Les produits n'ont pas pu être récupérés.";
            return ["data" => [], "error" => $error];
        }

        $allProducts = [];
        for ($i = 0; $i < count($result); $i++) {
            $product = new Product();
            $product->setProduct(
                $result[$i]["name_p"],
                $result[$i]["price"],
                $result[$i]["stock"],
                $result[$i]["access_level"],
                ["name" => $result[$i]["name_cat"], "id" => $result[$i]["id_cat"]],
                $result[$i]["id_p"]
            );
            array_push($allProducts, $product);
        }

        return ["data" => $allProducts];
    }

    /** function to get only products accessible by the user
     * 
     */
    public function getProductAccessible()
    {
        try {
            $this->db->query("SELECT products.*, name_cat FROM products INNER JOIN categories ON products.id_cat = categories.id_cat WHERE access_level <= :accessLevel");
            $this->db->bind("accessLevel", $_SESSION["userLevelAccess"]);
            $result = $this->db->fetchAll();
        } catch (PDOException $exception) {
            echo "Couldn't get products beacause of error : " . $exception->getMessage();
            return ["data" => [], "erorr" => "Les produits n'ont pas pu être récupérés."];
        }

        $allProducts = [];
        for ($i = 0; $i < count($result); $i++) {
            $product = new Product();
            $product->setProduct(
                $result[$i]["name_p"],
                $result[$i]["price"],
                $result[$i]["stock"],
                $result[$i]["access_level"],
                ["name" => $result[$i]["name_cat"], "id" => $result[$i]["id_cat"]],
                $result[$i]["id_p"]
            );
            array_push($allProducts, $product);
        }
        return ["data" => $allProducts];
    }

    /** function to get product only by certain category
     * 
     */
    public function getProductByCategory($category)
    {
        try {
            $this->db->query("
            SELECT products.*, name_cat FROM products
            INNER JOIN categories ON products.id_cat = categories.id_cat
            WHERE products.id_cat = :cat");
            $this->db->bind("cat", $category);
            $result = $this->db->fetchAll();
        } catch (PDOException $exception) {
            return ["data" => [], "error" => "Nous n'avons pas pu récupérer les produits de la catégorie correspondante."];
        }

        $allProducts = [];
        for ($i = 0; $i < count($result); $i++) {
            $product = new Product();
            $product->setProduct(
                $result[$i]["name_p"],
                $result[$i]["price"],
                $result[$i]["stock"],
                $result[$i]["access_level"],
                ["name" => $result[$i]["name_cat"], "id" => $result[$i]["id_cat"]],
                $result[$i]["id_p"]
            );
            array_push($allProducts, $product);
        }
        return ["data" => $allProducts];
    }

    /** Function to get all categories
     * @return [] categories
     */
    public function getCategories()
    {
        try {
            $this->db->query("SELECT * FROM categories");
            $result = $this->db->fetchAll();
        } catch (PDOException $exception) {
            return ["data" => [], "error" => "Les catégories n'ont pas pu être récupérées."];
        }

        return ["data" => $result];
    }

    ######### POST

    /** Function to create product ressource from the object itself
     */
    public function createProduct()
    {
        try {
            $this->db->query("INSERT INTO products (name_p, price, stock, access_level, id_cat) VALUES (:name, :price, :stock, :access_level, :category)");
            $this->db->bind("name", $this->name);
            $this->db->bind("price", $this->price);
            $this->db->bind("stock", $this->stock);
            $this->db->bind("access_level", $this->access_level);
            $this->db->bind("category", $this->category);
            $this->db->fetch();
        } catch (PDOException $exception) {
            return ["error" => "Le produit n'a pas pu être ajouté."];
        }
    }

    ######### PATCH
    /** function to update a product by its id
     * @param int $id
     */
    public function updateProduct($id)
    {
        try {
            $this->db->query("UPDATE products SET name_p = :name, price = :price, stock = :stock, access_level = :access_level, id_cat = :cat WHERE id_p = :id");
            $this->db->bind("id", $id);
            $this->db->bind("name", $this->name);
            $this->db->bind("price", $this->price);
            $this->db->bind("stock", $this->stock);
            $this->db->bind("access_level", $this->access_level);
            $this->db->bind("cat", $this->category);
            $this->db->fetch();
        } catch (PDOException $exception) {
            return ["error" => "La modification n'a pas pu être enregistrée."];
        }
    }

    /** function to delete a product 
     */
    public function deleteProduct($id)
    {
        try {
            $this->db->query("DELETE FROM products WHERE id_p = :id");
            $this->db->bind("id", $id);
            $this->db->fetch();
        } catch (PDOException $exception) {
            $errorType = $this->db->getTypeError($exception->getCode());
            $errorMsg = $errorType == ErrorType::IsFK ? "Le produit est associé à une commande. Il ne peut pas être supprimé." : "Le produit n'a pas pu être supprimé";
            return ["error" => $errorMsg];
        }
    }

}