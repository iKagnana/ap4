<?php
require_once("../app/core/Database.php");
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
    private $listStatus = [0 => "En attente de validation", 1 => "Validée", 2 => "Refusée"];

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
     */
    public function setOrder($date, $price, $applicant, $status, $reason)
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
    public function setOrderGet($date, $price, $applicant, $validator, $status, $reason, $id)
    {

        $this->date = $date;
        $this->price = $price;
        $this->applicant = $applicant;
        $this->validator = $validator;
        $this->status = $status;
        $this->reason = $reason;
        $this->id = $id;
    }

    /** method to set products for an order
     * @param Product[] $products
     */
    public function setProducts($products)
    {
        $this->products = $products;
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

    ################ Request to db

    ######### GET
    /** function to retreive order data 
     * @return Order[] 
     */
    public function getOrder()
    {
        try {
            $this->db->query("SELECT * FROM orders JOIN users ON orders.id_u = users.id_u");
            $result = $this->db->fetchAll();

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
                    $result[$i]["id_o"]
                );

                $products = $order->getProductsByOrderId($result[$i]["id_o"]);
                $order->setProducts($products);
                array_push($allOrders, $order);
            }
            return $allOrders;

        } catch (PDOException $e) {
            echo "Couldn't get orders because of error :" . $e->getMessage();
            return [];
        }
    }

    /** function to retreive only user's order
     * @param int $id user id
     * @return Order[]
     */
    public function getUserOrders($id)
    {
        # TODO
    }

    /** function to retreive all the products for an order
     * @param int $idO
     */
    public function getProductsByOrderId($idO)
    {
        try {
            $this->db->query("SELECT products.name_p, products.price, categories.name_cat FROM orders_details 
            INNER JOIN orders ON orders.id_o = orders_details.id_o
            INNER JOIN products ON products.id_p = orders_details.id_p
            INNER JOIN categories ON categories.id_cat = products.id_cat
            WHERE orders.id_o = :idO");
            $this->db->bind("idO", $idO);
            $result = $this->db->fetchAll();
            return $result;

        } catch (PDOException $e) {
            echo "Could get the products for the order" . $e->getMessage();
            return [];
        }
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
            $this->db->query("INSERT INTO orders (date_o, price_o, id_u, status, reason) VALUES (:date, :price, :applicant, :status, :reason)");
            $this->db->bind("date", $this->date);
            $this->db->bind("price", $this->price);
            $this->db->bind("applicant", $this->applicant);
            $this->db->bind("status", $this->status);
            $this->db->bind("reason", $this->reason);
            $nb = $this->db->fetchLastId();
            return $nb;
        } catch (PDOException $e) {
            echo "Couldn't add order because of error :" . $e->getMessage();
            return 0;
        }
    }

    /** method to add order_details in db
     * @param int $idOrder order id 
     * @param int $idProduct product id
     * @param int $quantity quantity, may be negative or positive
     * @return 
     */
    public function addOrderDetails($idOrder, $idProduct, $quantity)
    {
        try {
            $this->db->query("INSERT INTO orders_details (id_o, id_p, quantity) VALUES (:idOrder, :idProduct, :quantity)");
            $this->db->bind("idOrder", $idOrder);
            $this->db->bind("idProduct", $idProduct);
            $this->db->bind("quantity", $quantity);
            $result = $this->db->fetch();
            return $result;
        } catch (PDOException $e) {
            echo "Couldn't add order details because of error :" . $e->getMessage();
            return false;
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
            $result = $this->db->fetch();
            return $result;
        } catch (PDOException $e) {
            echo "Could update orders" . $e->getMessage();
            return false;
        }
    }

}