<?php
require_once("../app/models/Order.php");

class OrderController extends Controller
{

    private $order; # used for function db relate to order
    private $product; # used for function db relate to product
    private $provider; # used for function db relate to provider

    public function __construct()
    {
        $this->order = $this->model("Order");
        $this->product = $this->model("Product");
        $this->provider = $this->model("Provider");
    }

    /** method to display order view
     * @param []|null $extra
     */
    public function index($extra = null)
    {
        $res = $_SESSION["userRole"] < 2 ? $this->order->getOrder() : $this->order->getUserOrders($_SESSION["userId"]);

        $orders = $res["data"];
        $error = $res["error"] ?? null;

        if (isset($extra["error"])) {
            $error = $res["error"];
        }

        $data = ["all" => $orders, "error" => $error];

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
        $res = $this->order->getOrder();
        $allOrders = $res["data"];
        $error = $res["error"] ?? null;

        if (count($allOrders) != 0) {
            $todo = $this->order->getTodoOrders($allOrders);
            $done = $this->order->getDoneOrders($allOrders);
        } else {
            $todo = [];
            $done = [];
            $error = "Impossible de récupérer la liste des commandes.";
        }

        if (isset($extra["error"])) {
            $error = $extra["error"];
        }

        $data = ["todo" => $todo, "done" => $done, "error" => $error];
        if (isset($extra)) {
            $sendData = array_merge($data, $extra);
            $this->view("order/orders-control-view", $sendData);
        } else {
            $this->view("order/orders-control-view", $data);
        }
    }

    public function treatment()
    {
        $status = $_POST["status"];
        $reason = $_POST["reason"];
        $id = $_POST["id"];

        $res = $this->order->treatOrder($id, $status, $reason);
        $error = $res["error"] ?? null;
        $this->control(["error" => $error]);

    }

    /** method to display the page form-order-view
     * @param []|null $extra
     */
    public function form($extra = null)
    {
        $resProduct = $this->product->getProductAccessible();
        $resProvider = $this->provider->getProvider();

        $sendProduct = $resProduct["data"];
        $allProviders = $resProvider["data"];
        if (isset($extra["searchName"])) {
            $sendProduct = $this->product->filterProduct($extra["searchName"], $sendProduct);
        }

        $error = isset($resProduct["error"]) || isset($resProvider["error"]) ? "Des données n'ont pas pu être récupéré" : null;

        if (isset($extra["error"])) {
            $error = $extra["error"];
        }

        $cart = $_SESSION["cart"] ?? [];
        $this->view("order/form-order-view", ["allProducts" => $sendProduct, "cart" => $cart, "providers" => $allProviders, "error" => $error]);
    }

    public function addProduct()
    {
        $idProduct = $_POST["idProduct"];
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

        $this->form(["error" => $error]);
    }

    public function substract()
    {
        $idProduct = $_REQUEST["id"];
        $cart = $_SESSION["cart"];

        $allProducts = $this->product->getProducts();

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

        $allProducts = $this->product->getProducts();

        # search key and remove it 
        $key = array_search($idProduct, array_column($cart, "id"));
        unset($cart[$key]);

        $_SESSION["cart"] = $cart;

        $this->view("order/form-order-view", ["allProducts" => $allProducts, "cart" => $cart]);
    }

    public function reset()
    {
        unset($_SESSION["cart"]);
        $this->form();
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
        $type = $_POST["type"] ?? "incoming";
        $provider = $type == "outgoing" ? $_POST["provider"] : null ?? null;
        $this->form();

        $order = new Order();
        $order->setOrder($date, $totalPrice, $idUser, "En attente de validation", "", $provider);
        $res = $order->createOrder();
        $idOrder = $res["data"];

        if ($idOrder != 0) {
            foreach ($cart as $product) {
                $res = $order->addOrderDetails($idOrder, $product["id"], $product["quantity"], $type);
                if (isset($res["error"])) {
                    $error = $res["error"];
                    break;
                }
            }
            unset($_SESSION["cart"]);

        } else {
            $error = $res["error"];
        }

        $this->index(["error" => $error]);

    }
}