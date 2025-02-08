<?php
class OrderDAO {
    public $db;
    public function __construct()
    {
        $this->db = new DBHelper();
    }

    public function insertOrder($firstname, $lastname, $phone, $address, $email) {
        try {
            if($email === NULL) {
                return $this->db->insert('`order`', ['firstname' => $firstname, 'lastname' => $lastname, 'phone' => $phone, 'address' => $address]);
            } else {
                return $this->db->insert('`order`', ['firstname' => $firstname, 'lastname' => $lastname, 'phone' => $phone, 'address' => $address, 'email' => $email]);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function insertOrderDetail($orderId, $productId, $quantity, $price) {
        try {
            return $this->db->insert('order_details', ['order_id' => $orderId, 'product_id' => $productId, 'quantity' => $quantity, 'price' => $price]);
        } catch(Exception $e) {
            echo $e;
            return false;
        }
    }

    public function selectOrderItems($orderId) {
        $sql = "select od.*, product.name productName from 
(
	select * from `order` where id = :orderId
) o join order_details od
on o.id = od.order_id
join product
on od.product_id = product.id";

        try {
            return $this->db->select($sql, [':orderId' => $orderId]);
        } catch (Exception $e) {
            return array();
        }
    }

    public function selectOrderById($id) {
        $sql = "select * from `order` where id = :id";

        try {
            return $this->db->select($sql, [':id' =>$id]);
        } catch(Exception $e) {
            return array();
        }
    }

    public function selectOrderByPage($page, $perPage = 10, $status = null) {
        $offset = ($page - 1) * $perPage;
        $sqlWithStatus = "select o.id order_id, o.createdAt, o.updatedAt, o.firstname, o.lastname, o.phone, o.address, o.email, o.status, o.discount from `order` o where o.status = :status order by o.createdAt desc limit $perPage offset $offset";

        $sqlWithoutStatus = "select o.id order_id, o.createdAt, o.updatedAt, o.firstname, o.lastname, o.phone, o.address, o.email, o.status, o.discount from `order` o order by o.createdAt desc limit $perPage offset $offset";

        try {
            if($status === null) {
                return $this->db->select($sqlWithoutStatus);
            } else {
                return $this->db->select($sqlWithStatus, [':status' => $status]);
            }
        } catch(Exception $e) {
            return array();
        }
    }

    public function countOrder() {
        $sql = "select count(*) as count from `order` o join order_details od
on o.id = od.order_id";
        try {
            $res = $this->db->select($sql);
            return intval($res[0]['count']);
        } catch(Exception $e) {
            return 0;
        }
    }


    public function updateOrderStatus($orderId, $status) {
        try {
            return $this->db->update('`order`', ['status' => $status] , "id = $orderId");
        } catch (Exception $e) {
            return false;
        }
    }

    public function updateStatus($status) {

    }
}