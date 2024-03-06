<?php
require_once("../app/models/Order.php");

class OrderController extends Controller
{
    /** method to display order view
     * @param []|null $extra
     */
    public function index($extra = null)
    {
        $order = new Order();
        $orders = $order->getOrder();
        $data = ["all" => $orders];
        if (isset($extra)) {
            $sendData = array_merge($data, $extra);
            $this->view("order/orders-view", $sendData);
        } else {
            $this->view("order/orders-view", $data);
        }
    }

    public function detail()
    {
        if (isset($_POST["item"])) {
            $selected = $_POST["item"];
            $this->index(["openedItem" => $selected]);
        }

        if (isset($_POST["selectedTodo"])) {
            $selectedTodo = $_POST["selectedTodo"];
            $this->control(["selectedTodo" => $selectedTodo]);
        }

        if (isset($_POST["selectedDone"])) {
            $selectedDone = $_POST["selectedDone"];
            $this->control(["selectedDone" => $selectedDone]);
        }

        if (isset($_POST["onDoingItem"])) {
            $selectOnDoingItem = $_POST["onDoingItem"];
            $this->control(["onDoingItem" => $selectOnDoingItem]);
        }
    }

    /** method to search for product
     */
    public function search()
    {
        $searchName = $_POST["search"];
        $this->form(["searchName" => $searchName]);
    }

    /** method to display the page orders-control-view
     * @param []|null $extra
     */
    public function control($extra = null)
    {

        $order = new Order();
        $allOrders = $order->getOrder();

        $todo = $order->getTodoOrders($allOrders);
        $done = $order->getDoneOrders($allOrders);

        $data = ["todo" => $todo, "done" => $done];
        if (isset($extra)) {
            $sendData = array_merge($data, $extra);
            $this->view("order/orders-control-view", $sendData);
        } else {
            $this->view("order/orders-control-view", $data);
        }
    }

    public function treatment()
    {
        $order = new Order();
        $status = $_POST["status"];
        $reason = $_POST["reason"];
        $id = $_POST["id"];

        $order->treatOrder($id, $status, $reason);
        $this->control();

    }

    /** method to display the page form-order-view
     * @param []|null $extra
     */
    public function form($extra = null)
    {
        $products = new Product();
        $allProducts = $products->getProducts();
        $sendData = $allProducts;
        if (isset($extra["searchName"])) {
            $sendData = $products->filterProduct($extra["searchName"], $allProducts);
        }
        $cart = $_SESSION["cart"] ?? [];
        $this->view("order/form-order-view", ["allProducts" => $sendData, "cart" => $cart]);
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


    public function create()
    {
        $totalPrice = 0;
        $cart = $_SESSION["cart"];
        foreach ($cart as $product) {
            $totalPrice = $totalPrice + $product["totalPrice"];
        }

        # variable
        $date = new DateTime();
        $idUser = $_SESSION["userId"];

        $order = new Order();
        $order->setOrder($date, $totalPrice, $idUser, "En attente de validation", "");
        $idOrder = $order->createOrder();

        if ($idOrder != 0) {
            foreach ($cart as $product) {
                $order->addOrderDetails($idOrder, $product["id"], $product["quantity"]);
            }
            unset($_SESSION["cart"]);
            $this->index();
        } else {
            echo "Une erreur est survenue lors de la création de la commande";
        }

    }
}