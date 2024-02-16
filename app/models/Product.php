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
        $this->openState = false;
    }

    /** filter product by name
     * @param string $searchName
     * @param Product[] $products
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

    /** method to get product only by certain category
     * 
     */
    public function getProductByCategory($category)
    {
        try {
            $this->db->query("
            SELECT id_p, name_p, stock, price, access_level, name_cat FROM products
            INNER JOIN categories ON products.id_cat = categories.id_cat
            WHERE products.id_cat = :cat");
            $this->db->bind("cat", $category);
            $res = $this->db->fetchAll();
            return $res;
        } catch (PDOException $exception) {
            echo "Problème lors de la récupération des produits, due à l'erreur :" . $exception->getMessage();
            return [];
        }
    }

    /** Function to get all categories
     * @return [] categories
     */
    public function getCategories()
    {
        try {
            $this->db->query("SELECT * FROM categories");
            $result = $this->db->fetchAll();
            return $result;
        } catch (PDOException $exception) {
            echo "Couldn't get categories : " . $exception->getMessage();
            return [];
        }
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
            $result = $this->db->fetch();
            echo $result;
            return $result;
        } catch (PDOException $exception) {
            echo "Couldn't add product because of error :" . $exception->getMessage();
            return false;
        }
    }

    ######### PATCH
    /** function to update a product by its id
     * @param int $id
     * @return bool 
     */
    public function updateProduct($id)
    {
        try {
            $this->db->query("UPDATE products SET name_p = :name, price = :price, stock = :stock, access_level = :access_level WHERE id_p = :id");
            $this->db->bind("id", $id);
            $this->db->bind("name", $this->name);
            $this->db->bind("price", $this->price);
            $this->db->bind("stock", $this->stock);
            $this->db->bind("access_level", $this->access_level);
            $this->db->fetch();
            return true;
        } catch (PDOException $exception) {
            echo "Couldn't update product" . $exception->getMessage();
            return false;
        }
    }

    /** function to delete a product 
     * @return bool
     */
    public function deleteProduct()
    {
        try {
            $this->db->query("DELETE FROM products WHERE id_p = :id");
            $this->db->bind("id", $this->id);
            $this->db->fetch();
            return true;
        } catch (PDOException $exception) {
            echo "Couldn't delete this product" . $exception->getMessage();
            return false;
        }
    }

}