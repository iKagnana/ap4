<?php
require_once("../app/models/Product.php");

class ProductController extends Controller
{
    private $product;
    public function __construct()
    {
        $this->product = $this->model("Product");
    }

    public function index()
    {
        $allProducts = $this->product->getProducts();
        $this->view("product/products-view", $allProducts);
    }

    public function displayFormProduct()
    {
        ## test if the user has access
        if (!isset($_SESSION["user"])) {
            echo "Pas d'utilisateur connecté";
        }
        if ($_SESSION["user"]->role != 0) {
            $this->view("app/views/no_authorized_view.php");
            return;
        }

        $allCategories = $this->product->getCategories();
        # display form to add product
        $this->view("product/form-product-view", $allCategories);
    }

    public function createProduct()
    {
        $name = $_POST["name"];
        $price = $_POST["price"];
        $stock = $_POST["stock"];
        $access_level = $_POST["access_level"];
        $category = $_POST["category"];
        $newProduct = new Product();
        $newProduct->setProduct($name, $price, $stock, $access_level, $category);
        $newProduct->createProduct();
    }

}