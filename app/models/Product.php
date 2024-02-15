<?php
require_once("../app/core/Database.php");
class Product
{
    public $id;
    public $name;
    public $price;
    public $stock;
    public $access_level;
    public $category;
    private $db;

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
     * @param string | int $category
     * @param int $id optionnal
     */
    public function setProduct($name, $price, $stock, $access_level, $category, $id = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->stock = $stock;
        $this->access_level = $access_level;
        $this->category = $category;
    }

    ################ Request to db

    /** Function to retreive all the products 
     */
    public function getProducts()
    {
        try {
            $this->db->query("SELECT id_p, name_p, stock, price, access_level, name_cat FROM products INNER JOIN categories WHERE products.id_cat = categories.id_cat");
            $result = $this->db->fetchAll();

            $allProducts = [];
            for ($i = 0; $i < count($result); $i++) {
                $product = new Product();
                $product->setProduct(
                    $result[$i]["name_p"],
                    $result[$i]["price"],
                    $result[$i]["stock"],
                    $result[$i]["access_level"],
                    $result[$i]["name_cat"],
                    $result[$i]["id_p"]
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
            $result = $this->db->fetch();
            return $result;
        } catch (PDOException $exception) {
            echo "Couldn't add product because of error :" . $exception->getMessage();
            return false;
        }
    }

    public function getCategories()
    {
        try {
            $this->db->query("SELECT * FROM categories");
            $result = $this->db->fetchAll();
            return $result;
        } catch (PDOException $exception) {
            echo "Couldn't get categories : " . $exception->getMessage();
        }
    }
}