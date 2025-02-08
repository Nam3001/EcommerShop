<?php
class ReceiptDAO {
    public $db;
    public function __construct()
    {
        $this->db = new DBHelper();
    }

    public function insertReceipt($supplierId) {
        if($supplierId === null) return false;
        try {
            return $this->db->insert('receipt', ['supplier_id' => $supplierId]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function selectReceiptByPage($page, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        $sql = "select * from receipt order by datetime desc limit $perPage offset $offset";
        try {
            return $this->db->select($sql);
        } catch (Exception $e) {
            return array();
        }
    }

    public function insertReceiptDetails($receiptId, $productId, $price, $quantity) {
        if($receiptId === null || $productId === null || $price === null || $quantity === null) return false;
        try {
            return $this->db->insert('receipt_details', ['receipt_id' => $receiptId, 'product_id' =>$productId, 'price' => $price, 'quantity' =>$quantity]);
        } catch(Exception $e) {
            return false;
        }
    }

    public function countReceipt() {
        $sql = "select count(*) as count from receipt r join receipt_details rd
on r.id = rd.receipt_id";
        try {
            $res = $this->db->select($sql);
            return intval($res[0]['count']);
        } catch(Exception $e) {
            return 0;
        }
    }

    public function selectReceiptDetails($id) {
        $sql = "select * from receipt_details where receipt_id = :id";
        try {
            return $this->db->select($sql, [':id' =>$id]);
        } catch(Exception $e) {
            return array();
        }
    }

    public function selectReceiptById($id) {
        $sql = "select * from receipt where id = :id";
        try {
            return $this->db->select($sql, [':id' =>$id]);
            echo "lkdjfdf ";
        } catch (Exception $e) {
            return array();
        }
    }


}