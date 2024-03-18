<?php
require_once ("../app/core/Database.php");
class Order
{
    public $id;
    public $date;
    public $price;
    public $applicant;
    public $validator;
    public $status;
    public $reason;
    public $products = [];
    public $provider;
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /** method to set value into order to create
     * @param DateTime|string $date
     * @param double $price
     * @param int|string $applicant can be its id or its name
     * @param string $status
     * @param string $reason
     * @param int|null $provider optionnal
     */
    public function setOrder($date, $price, $applicant, $status, $reason, $provider = null)
    {
        if (is_a($date, "DateTime")) {
            $this->date = date("Y-m-d H:i:s");
        } else {
            $this->date = $date;
        }

        $this->price = $price;
        $this->applicant = $applicant;
        $this->status = $status;
        $this->reason = $reason;
        $this->provider = $provider;
    }

    /** method to set value into order to get
     * @param DateTime|string $date
     * @param double $price
     * @param int|string $applicant can be its id or its name
     * @param string $status
     * @param string $reason
     * @param Product[] $products
     * @param int $id
     */
    public function setOrderGet($date, $price, $applicant, $validator, $status, $reason, $id, $provider)
    {

        $this->date = $date;
        $this->price = $price;
        $this->applicant = $applicant;
        $this->validator = $validator;
        $this->status = $status;
        $this->reason = $reason;
        $this->id = $id;
        $this->provider = $provider;
    }

    /** method to set products for an order
     * @param Product[] $products
     */
    public function setProducts($products)
    {
        $this->products = $products;
    }

    /** function to set provider name for display 
     * @param string $provider
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
    }

    /** function to get only order to check by admin
     * @param Order[] $orders
     * @return Order[]
     */
    public function getTodoOrders($orders)
    {
        return array_filter($orders, function ($order) {
            return $order->status == "En attente de validation";
        });
    }

    /** function to get only order done by admin
     * @param Order[] $orders
     * @return Order[]
     */
    public function getDoneOrders($orders)
    {
        return array_filter($orders, function ($order) {
            return $order->status != "En attente de validation";
        });
    }

    /** function to get only order valided by admin
     * @param Order[] $orders
     * @return Order[]
     */
    public function getValidedOrders($orders)
    {
        return array_filter($orders, function ($order) {
            return $order->status == "Validé";
        });
    }

    /** function to get only refused done by admin
     * @param Order[] $orders
     * @return Order[]
     */
    public function getRefusedOrders($orders)
    {
        return array_filter($orders, function ($order) {
            return $order->status == "Refusé";
        });
    }

    /** function to get all orders done by searched applicant
     * @param Order[] $orders
     * @param string $searchName applicant name
     * @return Order[]
     */
    public function getByUser($orders, $searchName)
    {
        return array_filter($orders, function ($order) use ($searchName) {
            return str_contains(strtolower($order->applicant), strtolower($searchName));
        });
    }


    /** function to check stock before validate the order to avoid having
     * negative stocks
     * @param $product
     */
    public function checkStock($product)
    {
        if ($product["quantity"] < 0 && abs($product["quantity"]) > $product["stock"]) {
            return false;
        } else {
            return true;
        }
    }

    ################ Request to db

    ######### GET
    /** function to retreive order data 
     * @return Order[] 
     */
    public function getOrder()
    {
        try {
            $this->db->query("SELECT orders.*, users.lastname_u, users.firstname_u, users.id_u, provider.name_pro FROM orders JOIN users ON orders.id_u = users.id_u LEFT JOIN provider ON provider.id_pro = orders.id_pro");
            $result = $this->db->fetchAll();
        } catch (PDOException $e) {
            return ["data" => [], "error" => "Impossible de récupérer les commandes."];
        }

        $allOrders = [];
        for ($i = 0; $i < count($result); $i++) {
            $order = new Order();
            $order->setOrderGet(
                $result[$i]["date_o"],
                $result[$i]["price_o"],
                $result[$i]["lastname_u"] . " " . $result[$i]["firstname_u"],
                $result[$i]["id_u_User"],
                $result[$i]["status"],
                $result[$i]["reason"],
                $result[$i]["id_o"],
                $result[$i]["name_pro"],
            );

            $products = $order->getProductsByOrderId($result[$i]["id_o"]);
            $order->setProducts($products);
            array_push($allOrders, $order);
        }

        return ["data" => $allOrders];


    }

    /** function to retreive only user's order
     * @param int $id user id
     * @return Order[]
     */
    public function getUserOrders($id)
    {
        try {
            $this->db->query("SELECT orders.*, users.lastname_u, users.firstname_u, users.id_u FROM orders JOIN users ON orders.id_u = users.id_u WHERE orders.id_u = :id");
            $this->db->bind("id", $id);
            $result = $this->db->fetchAll();
        } catch (PDOException $e) {
            return ["data" => [], "error" => "Impossible de récupérer vos commandes"];
        }

        $allOrders = [];
        for ($i = 0; $i < count($result); $i++) {
            $order = new Order();
            $order->setOrderGet(
                $result[$i]["date_o"],
                $result[$i]["price_o"],
                $result[$i]["lastname_u"] . " " . $result[$i]["firstname_u"],
                $result[$i]["id_u_User"],
                $result[$i]["status"],
                $result[$i]["reason"],
                $result[$i]["id_o"],
                $result[$i]["id_pro"],
            );

            $products = $order->getProductsByOrderId($result[$i]["id_o"]);
            $order->setProducts($products);
            array_push($allOrders, $order);
        }
        return ["data" => $allOrders];


    }

    /** function to retreive all the products for an order
     * @param int $idO
     */
    public function getProductsByOrderId($idO)
    {
        try {
            $this->db->query("SELECT products.name_p, products.price, products.stock, categories.name_cat, quantity FROM orders_details 
            INNER JOIN orders ON orders.id_o = orders_details.id_o
            INNER JOIN products ON products.id_p = orders_details.id_p
            INNER JOIN categories ON categories.id_cat = products.id_cat
            WHERE orders.id_o = :idO");
            $this->db->bind("idO", $idO);
            $result = $this->db->fetchAll();
        } catch (PDOException $e) {
            echo "Could get the products for the order" . $e->getMessage();
            return ["data" => [], "error" => "Impossible de récupérer les produits associés à la commande"];
        }

        return ["data" => $result];
    }

    ######### POST

    /** method to add order in db 
     * @param DateTime date 
     * @param int price
     * @param int applicant id 
     * @param string status, "En attente de validation" by default
     * @param string reason, "" by default
     */
    public function createOrder()
    {
        try {
            $this->db->query("INSERT INTO orders (date_o, price_o, id_u, status, reason, id_pro) VALUES (:date, :price, :applicant, :status, :reason, :provider)");
            $this->db->bind("date", $this->date);
            $this->db->bind("price", $this->price);
            $this->db->bind("applicant", $this->applicant);
            $this->db->bind("status", $this->status);
            $this->db->bind("reason", $this->reason);
            $this->db->bind("provider", $this->provider);
            $nb = $this->db->fetchLastId();
        } catch (PDOException $e) {
            return ["data" => 0, "error" => "Impossible de savegarder la commande"];
        }

        return ["data" => $nb];

    }

    /** method to add order_details in db
     * @param string $type type of order
     * @param int $idOrder order id 
     * @param int $idProduct product id
     * @param int $quantity quantity, may be negative or positive
     */
    public function addOrderDetails($idOrder, $idProduct, $quantity, $type)
    {
        try {
            $this->db->query("INSERT INTO orders_details (id_o, id_p, quantity) VALUES (:idOrder, :idProduct, :quantity)");
            $this->db->bind("idOrder", $idOrder);
            $this->db->bind("idProduct", $idProduct);
            if ($type == "incoming") {
                $this->db->bind("quantity", 0 - $quantity);
            } else {
                $this->db->bind("quantity", $quantity);
            }

            $this->db->fetch();
        } catch (PDOException $e) {
            return ["error" => "Certains produits de votre panier n'ont pas pu être enregistré, contacter l'administrateur."];
        }

    }

    ######### PUT

    /** function to validate by admin an order
     * @param int $id
     * @param string $status
     * @param string $reason
     */
    public function treatOrder($id, $status, $reason)
    {
        try {
            $this->db->query("UPDATE orders SET status = :status, reason = :reason, id_u_User = :userId WHERE id_o = :id");
            $this->db->bind("id", $id);
            $this->db->bind("status", $status);
            $this->db->bind("reason", $reason);
            $this->db->bind("userId", $_SESSION["userId"]);
            $this->db->fetch();
        } catch (PDOException $e) {
            return ["error" => "Les changements n'ont pas pu être sauvegarder"];
        }

    }

}