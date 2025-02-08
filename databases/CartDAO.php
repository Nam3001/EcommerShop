<?php
if(!class_exists('DBHelper')) {
    include 'databases/DBHelper.php';
}
class CartDAO {
    public $db;
    public function __construct()
    {
        $this->db = new DBHelper();
    }

    public function getCartItems($userId) {
        $query = "select product_id, user_id, quantity from cartitems where user_id = :userId";
        try {
            $res = $this->db->select($query, [':userId' => $userId]);
            return $res;
        } catch (Exception $e) {
            return false;
        }
    }

    public function addToCart($userId, $productId, $quantity) {
        if($quantity <= 0) return;
        try {
            // check tồn tại trong cart
            $checkQuery = "select * from cartItems where user_id = :userId and product_id = :productId";
            $checkResult = $this->db->select($checkQuery, [':userId' => $userId, ':productId' => $productId]);
            $isExist = false;
            if(!$checkResult || count($checkResult) === 0) {
                $isExist = false;
            } else {
                $isExist = true;
            }

            $tonkho = 0;
            $product = $this->db->select('select quantity as tonkho from product where id = :productId', [':productId' => $productId]);
            if($product && count($product) > 0) {
                $tonkho = $product[0]['tonkho'];
            }

            if(!$isExist) {
                if($quantity > $tonkho) $quantity = $tonkho;
                $res = $this->db->insert('cartItems', ['product_id' => $productId, 'user_id' => $userId, 'quantity' => $quantity]);
                return $res;
            } else {
                $prevQuantity = $checkResult[0]['quantity'];
                $newQuantity = $prevQuantity + $quantity;

                if($newQuantity > $tonkho) $newQuantity = $tonkho;

                $res = $this->db->update('cartItems', ['quantity' => $newQuantity], "user_id = $userId and product_id = $productId");
                return $res;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function updateCart($userId, $productId, $quantity) {
        if($quantity <= 0) return;
        // check tồn tại trong cart
        $checkQuery = "select * from cartItems where user_id = :userId and product_id = :productId";
        $checkResult = $this->db->select($checkQuery, [':userId' => $userId, ':productId' => $productId]);
        $isExist = false;
        if(!$checkResult || count($checkResult) === 0) {
            $isExist = false;
        } else {
            $isExist = true;
        }

        if($isExist) {
            $tonkho = 0;
            $product = $this->db->select('select quantity as tonkho from product where id = :productId', [':productId' => $productId]);
            if($product && count($product) > 0) {
                $tonkho = $product[0]['tonkho'];
            }

            if($quantity > $tonkho) $quantity = $tonkho;

            try {
                $res = $this->db->update('cartItems', ['quantity' => $quantity], "user_id = $userId and product_id = $productId");
                return $res;
            } catch (Exception $e) {
                return false;
            }
        }
    }

    public function deleteCartItem($userId, $productId) {
        try {
            return $this->db->delete('cartItems', "user_id = $userId and product_id = $productId");
        } catch(Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function clearCart($userId) {
        try {
            $this->db->delete('cartItems', "user_id = $userId");
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

}