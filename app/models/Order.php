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

    /** method to set value into order
     * @param DateTime|string $date
     * @param double $price
     * @param int|string $applicant can be its id or its name
     * @param string $status
     * @param string $reason
     * @param Product[] $products
     * @param int $id
     */
    public function setOrder($date, $price, $applicant, $status, $reason, $products, $id = null)
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
            $this->db->bind("date_o", $this->date);
            $this->db->bind("price_o", $this->price);
            $this->db->bind("id_u", $this->applicant);
            $this->db->bind("status", $this->status);
            $this->db->bind("reason", $this->reason);
            $this->db->fetch();
            return true;
        } catch (PDOException $e) {
            echo "Couldn't add order because of error :" . $e->getMessage();
            return false;
        }
    }

    /** function to get order id to add details
     * @param int $applicantId the user that make the order
     * @param DateTime $date when did the order was made
     * @return int result, 0 if none
     */
    public function getLastOrderId($applicantId, $date)
    {
        try {
            $this->db->query("SELECT id_o FROM orders WHERE id_o = :applicantId and date_o = :date");
            $this->db->bind("date", $this->date);
            $this->db->bind("applicantId", $this->applicant);
            $result = $this->db->fetch();
            return $result;
        } catch (PDOException $e) {
            echo "Couldn't get id because of error :" . $e->getMessage();
            return 0;
        }
    }

    /** method to add order_details in db
     * @param int $idOrder order id 
     * @param int $idProduct product id
     * @param int $quantity quantity, may be negative or positive
     */
    public function addOrderDetails($idOrder, $idProduct, $quantity)
    {
        try {
            $this->db->query("INSERT INTO orders_details (id_o, id_p, quantity) VALUES (:idOrder, :idProduct, :quantity");
            $this->db->bind("idOrder", $this->date);
            $this->db->bind("idProduct", $this->price);
            $this->db->bind("quantity", $this->applicant);
        } catch (PDOException $e) {
            echo "Couldn't add order details because of error :" . $e->getMessage();
        }
    }

}