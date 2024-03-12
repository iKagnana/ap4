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
        $orders = [];
        $orders = $_SESSION["userRole"] < 2 ? $this->order->getOrder() : $this->order->getUserOrders($_SESSION["userId"]);

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
        $allOrders = $this->order->getOrder();

        $todo = $this->order->getTodoOrders($allOrders);
        $done = $this->order->getDoneOrders($allOrders);

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
        $status = $_POST["status"];
        $reason = $_POST["reason"];
        $id = $_POST["id"];

        $this->order->treatOrder($id, $status, $reason);
        $this->control();

    }

    /** method to display the page form-order-view
     * @param []|null $extra
     */
    public function form($extra = null)
    {
        $allProducts = $this->product->getProductAccessible();
        $allProviders = $this->provider->getProvider();

        $sendProduct = $allProducts;
        if (isset($extra["searchName"])) {
            $sendProduct = $this->product->filterProduct($extra["searchName"], $allProducts);
        }
        $cart = $_SESSION["cart"] ?? [];
        $this->view("order/form-order-view", ["allProducts" => $sendProduct, "cart" => $cart, "providers" => $allProviders]);
    }

    public function addProduct()
    {
        $idProduct = $_POST["idProduct"];
        # get product with its id 
        $allProducts = $this->product->getProducts();
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

        $this->form();
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
        $idOrder = $order->createOrder();

        if ($idOrder != 0) {
            foreach ($cart as $product) {
                $order->addOrderDetails($idOrder, $product["id"], $product["quantity"], $type);
            }
            unset($_SESSION["cart"]);
            $this->index();
        } else {
            echo "Une erreur est survenue lors de la création de la commande";
        }

    }
}