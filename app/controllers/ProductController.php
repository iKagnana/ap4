<?php
require_once("../app/models/Product.php");

class ProductController extends Controller
{
    private $product;
    private $openedProduct = null;
    public function __construct()
    {
        $this->product = $this->model("Product");
    }

    public function index($extra = null)
    {

        // filter by cat or get all
        if (isset($extra["filterCat"]) && $extra["filterCat"] != "all") {
            $resPro = $this->product->getProductByCategory($extra["filterCat"]);
        } else {
            $resPro = $this->product->getProducts();
        }

        $allProducts = $resPro["data"];
        $error = $resPro["error"] ?? null;

        // get categories for filter
        $resCat = $this->product->getCategories();
        $allCats = $resCat["data"];
        $error = $resCat["error"] ?? null ? (isset($error) ? "Impossible de récupérer les données." : $resCat["error"]) : $error;

        // filter by search 
        if (isset($extra["searchName"])) {
            $allProducts = $this->product->filterProduct($extra["searchName"], $allProducts);
        }

        // set error if have
        if (isset($extra["error"])) {
            $error = $extra["error"];
        }

        $data = [
            "products" => $allProducts,
            "categories" => $allCats,
            "error" => $error,
            "filterCat" => $extra["filterCat"] ?? null
        ];
        $this->view("product/products-view", $data);
    }

    public function form()
    {
        ## test if the user has access
        if ($_SESSION["userRole"] != 0) {
            $this->view("app/views/no_authorized_view.php");
            return;
        }

        $res = $this->product->getCategories();
        # display form to add product
        $this->view("product/form-product-view", ["allCat" => $res["data"], "error" => $res["error"] ?? null]);
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
        $this->index(["error" => $res["error"] ?? null]);
    }

    public function filter()
    {
        $search = $_GET["search"] ?? null;
        $category = $_GET["category"] ?? null;

        $this->index(["searchName" => $search, "filterCat" => $category]);
    }

    /** method to add to cart from product page
     * 
     */
    public function cart()
    {
        $idProduct = $_POST["id"];
        # get product with its id 
        $res = $this->product->getProducts();
        $allProducts = $res["data"];
        $error = $res["error"] ?? null;

        $index = array_search($idProduct, array_column($allProducts, "id"));
        $selectedProduct = $allProducts[$index] ?? null;

        if (isset($_SESSION["cart"])) {
            $cart = $_SESSION["cart"];
        } else {
            $cart = array();
        }

        if (isset($selectedProduct)) {
            # change format in order to add in cart
            $productArray = array(
                "id" => $idProduct,
                "name" => $selectedProduct->name,
                "quantity" => 1,
                "price" => $selectedProduct->price,
                "category" => $selectedProduct->category,
                "totalPrice" => $selectedProduct->price
            );

            array_push($cart, $productArray);
            $_SESSION["cart"] = $cart;
        } else {
            $error = "Nous n'avons pas pu ajouter ce produit au panier";
        }

        $this->index(["error" => $error ?? null]);
    }

    public function details()
    {
        $id = $_REQUEST["id"];

        $allProducts = $this->product->getProducts()["data"];

        # get the right product and update it
        $index = array_search($id, array_column($allProducts, "id"));
        $openedProduct = $allProducts[$index] ?? null;

        $res = $this->product->getCategories();
        $allCat = $res["data"];
        $error = $res["error"] ?? null;

        if (isset($openedProduct)) {
            $this->view("product/product-details-view", [
                "selected" => $openedProduct,
                "categories" => $allCat,
                "error" => $error
            ]);
        } else {
            $this->index(["error" => "Impossible d'accéder au détail de ce produit."]);
        }

    }

    public function update()
    {
        $id = $_REQUEST["id"];
        $this->product->setProduct(
            $_REQUEST["name"],
            $_REQUEST["price"],
            $_REQUEST["stock"],
            $_REQUEST["access_level"],
            $_REQUEST["category"]
        );

        $res = $this->product->updateProduct($id);
        $error = $res["error"] ?? null;
        $this->index(["error" => $error]);
    }

    public function delete()
    {
        $id = $_REQUEST["id"];
        $res = $this->product->deleteProduct($id);
        $error = $res["error"];
        $this->index(["error" => $error]);
    }
}