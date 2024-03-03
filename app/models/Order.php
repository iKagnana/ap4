<?php
require_once("../app/core/Database.php");
class Order
{
    public $id;
    public $date;
    public $price;
    public $applicant;
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
    public function setOrderGet($date, $price, $applicant, $status, $reason, $products, $id)
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
        $this->products = $products;
        $this->id = $id;
    }

    /** function to get only order to check by admin
     * @param Order[] $orders
     * @return Order[]
     */
    public function getTodoOrders($orders)
    {
        return array_filter($orders, function ($order) {
            return $order["status"] == "En attente de validation";
        });
    }

    /** function to get only order done by admin
     * @param Order[] $orders
     * @return Order[]
     */
    public function getDoneOrders($orders)
    {
        return array_filter($orders, function ($order) {
            return $order["status"] != "En attente de validation";
        });
    }

    ################ Request to db

    ######### GET
    /** function to retreive order data 
     * @return Order[] 
     */
    public function getOrder()
    {
        # TODO
    }

    /** function to retreive only user's order
     * @param int $id user id
     * @return Order[]
     */
    public function getUserOrders($id)
    {
        # TODO
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
            echo "<br>";
            echo json_encode($this);
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
        echo "<br>";
        echo " id order" . $idOrder . ", id product" . $idProduct . ", quantity" . $quantity;
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

}