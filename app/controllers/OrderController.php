<?php

class OrderController extends Controller
{
    public function index()
    {
        $products = new Product();
        $allProducts = $products->getProducts();
        $cart = $_SESSION["cart"] ?? [];
        $this->view("order/form-order-view", ["allProducts" => $allProducts, "cart" => $cart]);
    }

    public function addProduct()
    {
        $idProduct = $_POST["idProduct"];
        # get product with its id 
        $products = new Product();
        $allProducts = $products->getProducts();
        $selectedProduct = array_column($allProducts, null, "id")[$idProduct];

        if (isset($_SESSION["cart"])) {
            $cart = $_SESSION["cart"];
        } else {
            $cart = array();
        }

        # change format in order to add in cart
        $productArray = array(
            "id" => $idProduct,
            "name" => $selectedProduct->name,
            "quantity" => $_REQUEST["quantity"],
            "price" => $selectedProduct->price,
            "category" => $selectedProduct->category,
            "totalPrice" => $selectedProduct->price * $_REQUEST["quantity"]
        );

        array_push($cart, $productArray);
        $_SESSION["cart"] = $cart;

        $this->view("order/form-order-view", ["allProducts" => $allProducts, "cart" => $cart]);
    }

    public function substract()
    {
        $idProduct = $_REQUEST["id"];
        $cart = $_SESSION["cart"];

        $products = new Product();
        $allProducts = $products->getProducts();

        # search key and remove it 
        $key = array_search($idProduct, array_column($cart, "id"));
        if ($cart[$key]["quantity"] - 1 == 0) {
            unset($cart[$key]);
        } else {
            $cart[$key]["quantity"] = $cart[$key]["quantity"] - 1;
            $cart[$key]["totalPrice"] = $cart[$key]["quantity"] * $cart[$key]["price"];
        }

        $_SESSION["cart"] = $cart;

        $this->view("order/form-order-view", ["allProducts" => $allProducts, "cart" => $cart]);

    }

    /** method in order to remove a product from cart
     * 
     */
    public function remove()
    {
        $idProduct = $_REQUEST["id"];
        $cart = $_SESSION["cart"];

        $products = new Product();
        $allProducts = $products->getProducts();

        # search key and remove it 
        $key = array_search($idProduct, array_column($cart, "id"));
        unset($cart[$key]);

        $_SESSION["cart"] = $cart;

        $this->view("order/form-order-view", ["allProducts" => $allProducts, "cart" => $cart]);
    }
}