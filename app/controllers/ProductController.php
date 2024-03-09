<?php
require_once("../app/models/Product.php");

class ProductController extends Controller
{
    private $product;
    private $filterOrder = "asc";
    private $filterBy = "none";
    private $openedProduct = null;
    public function __construct()
    {
        $this->product = $this->model("Product");
    }

    public function index()
    {
        $allProducts = $this->product->getProducts();
        $data = ["products" => $allProducts, "order" => $this->filterOrder];
        $this->view("product/products-view", $data);
    }

    public function form()
    {
        ## test if the user has access
        if ($_SESSION["userRole"] != 0) {
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
        $res = $newProduct->createProduct();
        if ($res == null) {
            $allProducts = $newProduct->getProducts();
            $_SESSION["products"] = $allProducts;
            $data = ["products" => $allProducts, "order" => $this->filterOrder];
            $this->view("product/products-view", $data);
        }
    }

    public function searchProduct()
    {
        $search = $_REQUEST["search"];

        $allProducts = $this->product->getProducts();

        $filteredData = $this->product->filterProduct($search, $allProducts);
        $_SESSION["searchProductText"] = $search;
        $data = ["products" => $filteredData, "order" => $this->filterOrder];
        $this->view("product/products-view", $data);
    }

    public function filter()
    {
        $search = isset($_SESSION["search"]) ? $_SESSION["search"] : "";

        $allProducts = $this->product->getProducts();
        $filteredData = $this->product->filterProduct($search, $allProducts);

        $order = isset($_REQUEST["order"]) ? $_REQUEST["order"] : "asc";

        if ($order != $this->filterOrder) {
            $allProducts = array_reverse($filteredData);
            $this->filterOrder = $order;
        }

        $data = ["products" => $allProducts, "order" => $this->filterOrder];
        $this->view("product/products-view", $data);
    }

    public function open()
    {
        $id = $_REQUEST["id"];

        $allProducts = $this->product->getProducts();

        # get the right product and update it
        if (array_key_exists($id, $allProducts)) {
            $this->openedProduct = array_column($allProducts, null, "id")[$id] ?? false;
        }

        $data = ["products" => $allProducts, "order" => $this->filterOrder, "open" => $this->openedProduct];
        $this->view("product/products-view", $data);

    }

    public function update()
    {
        $id = $_REQUEST["id"];
        $productClass = new Product();
        $productClass->setProduct(
            $_REQUEST["name"],
            $_REQUEST["price"],
            $_REQUEST["stock"],
            $_REQUEST["access_level"],
            0
        );

        $productClass->updateProduct($id);
        $this->index();
    }

    public function delete()
    {
        $id = $_REQUEST["id"];
        $productClass = new Product();
        $productClass->deleteProduct($id);
        $this->index();
    }
}