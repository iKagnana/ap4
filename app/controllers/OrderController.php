<?php
require_once ("../app/models/Order.php");

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

        $filter = null;
        $error = null;

        if (isset ($extra["filtered"])) {
            $orders = $extra["filtered"];
            $filter = $extra["filter"];
        } else {
            $orders = $res["data"];
            $error = $res["error"] ?? null;
        }

        if (isset ($extra["error"])) {
            $error = $res["error"];
        }

        if (isset ($extra["searchName"])) {
            $orders = $this->order->getByUser($orders, $extra["searchName"]);
        }

        $data = ["all" => $orders, "error" => $error, "filter" => $filter];

        if (isset ($extra)) {
            $sendData = array_merge($data, $extra);
            $this->view("order/orders-view", $sendData);
        } else {
            $this->view("order/orders-view", $data);
        }
    }

    public function detail()
    {
        if (isset ($_POST["item"])) {
            $selected = $_POST["item"];
            $this->index(["openedItem" => $selected]);
        }

        if (isset ($_POST["selectedTodo"])) {
            $selectedTodo = $_POST["selectedTodo"];
            $this->control(["selectedTodo" => $selectedTodo]);
        }

        if (isset ($_POST["selectedDone"])) {
            $selectedDone = $_POST["selectedDone"];
            $this->control(["selectedDone" => $selectedDone]);
        }

        if (isset ($_POST["onDoingItem"])) {
            $selectOnDoingItem = $_POST["onDoingItem"];
            $this->control(["onDoingItem" => $selectOnDoingItem]);
        }
    }

    /** function to filter orders
     * used in orders-view.php
     */
    public function filter()
    {
        $filter = $_GET["filter"] ?? "all";
        $searchName = $_GET["search"] ?? "";
        $res = $this->order->getOrder();
        $allOrders = $res["data"];
        $error = $res["error"] ?? null;

        if ($filter == "waiting") {
            $allOrders = $this->order->getTodoOrders($allOrders);
        } else if ($filter == "valid") {
            $allOrders = $this->order->getValidedOrders($allOrders);
        } else if ($filter == "refused") {
            $allOrders = $this->order->getRefusedOrders($allOrders);
        }

        $this->index(["filtered" => $allOrders, "filter" => $filter, "searchName" => $searchName, "error" => $error]);
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
        if (!$this->checkAccess("admin")) {
            $this->view("error");
            return; # force exit
        }

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

        if (isset ($extra["error"])) {
            $error = $extra["error"];
        }

        $data = ["todo" => $todo, "done" => $done, "error" => $error];
        if (isset ($extra)) {
            $sendData = array_merge($data, $extra);
            $this->view("order/orders-control-view", $sendData);
        } else {
            $this->view("order/orders-control-view", $data);
        }
    }

    public function treatment()
    {
        if (!$this->checkAccess("admin")) {
            $this->view("error/prohibited-view");
            return; # force exit
        }

        $status = $_POST["status"];
        $reason = $_POST["reason"];
        $id = $_POST["id"];

        # check if user can validate 
        $res = $this->order->getProductsByOrderId($id);
        $ordersProducts = $res["data"];
        $error = $res["error"] ?? null;

        foreach ($ordersProducts as $product) {
            $errors = [];
            if (!$this->order->checkStock($product)) {
                array_push($errors, $product["name_p"]);
            }
        }

        if (count($errors) == 1) {
            $error = "Attention, vous n'avez pas assez de stock pour ce produit : " . $errors[0];
        } else if (count($errors) > 1) {
            $error = "Attention, vous n'avez pas assez de stocks pour ces produits : " . implode(", ", $errors);
        } else {
            $res = $this->order->treatOrder($id, $status, $reason);
            $error = $res["error"] ?? null;
        }

        $this->control(["error" => $error]);

    }

    /** method to display the page form-order-view
     * @param []|null $extra
     */
    public function form($extra = null)
    {
        $resProduct = $this->product->getProductAccessible($extra["searchName"] ?? null);
        $resProvider = $this->provider->getProvider();

        $sendProduct = $resProduct["data"];
        $allProviders = $resProvider["data"];

        $error = isset ($resProduct["error"]) || isset ($resProvider["error"]) ? "Des données n'ont pas pu être récupéré" : null;

        if (isset ($extra["error"])) {
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

        if (isset ($_SESSION["cart"])) {
            $cart = $_SESSION["cart"];
        } else {
            $cart = array();
        }

        # check if product already in cart
        $indNew = array_search($idProduct, array_column($cart, "id"));
        $newProduct = $cart[$indNew] ?? null;

        if (isset ($newProduct) && $indNew !== false) {
            $cart[$indNew]["quantity"] = $newProduct["quantity"] + $_REQUEST["quantity"];
            $cart[$indNew]["totalPrice"] = $newProduct["totalPrice"] + $newProduct["price"] * $_REQUEST["quantity"];
        } else {
            # get the selected product
            $index = array_search($idProduct, array_column($allProducts, "id"));
            $selectedProduct = $allProducts[$index] ?? null;

            if (isset ($selectedProduct)) {
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
            } else {
                $error = "Nous n'avons pas pu ajouter ce produit au panier";
            }
        }

        $_SESSION["cart"] = $cart;

        $this->form(["error" => $error]);
    }

    public function substract()
    {
        $idProduct = $_REQUEST["id"];
        $cart = $_SESSION["cart"];

        # search key and remove it 
        $index = array_search($idProduct, array_column($cart, "id"));
        if ($cart[$index]["quantity"] - 1 == 0) {
            unset($cart[$index]);
        } else {
            $cart[$index]["quantity"] = $cart[$index]["quantity"] - 1;
            $cart[$index]["totalPrice"] = $cart[$index]["quantity"] * $cart[$index]["price"];
        }

        $_SESSION["cart"] = $cart;

        $this->form();
    }

    /** method in order to remove a product from cart
     * 
     */
    public function remove()
    {
        $idProduct = $_REQUEST["id"];
        $cart = $_SESSION["cart"];

        # search key and remove it 
        $index = array_search($idProduct, array_column($cart, "id"));
        unset($cart[$index]);

        $_SESSION["cart"] = $cart;

        $this->form();
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

        if ($totalPrice == 0) {
            $this->index(["error" => "Vous n'avez aucun article dans votre panier."]);
            return;
        }

        # variable
        $date = new DateTime();
        $idUser = $_SESSION["userId"];
        $type = $_POST["type"] ?? "incoming";
        $provider = $type == "outgoing" ? $_POST["provider"] : null ?? null;

        if ($type == "outgoing" && !isset ($provider)) {
            $this->index(["error" => "Veuillez choisir un fournisseur."]);
            return;
        }

        $order = new Order();
        $order->setOrder($date, $totalPrice, $idUser, "En attente de validation", "", $provider);
        $res = $order->createOrder();
        $idOrder = $res["data"];

        if ($idOrder != 0) {
            foreach ($cart as $product) {
                $res = $order->addOrderDetails($idOrder, $product["id"], $product["quantity"], $type);
                if (isset ($res["error"])) {
                    $error = $res["error"];
                    break;
                }
            }
            unset($_SESSION["cart"]);

        } else {
            $error = $res["error"];
        }

        $this->index(["error" => $error ?? null]);

    }
}