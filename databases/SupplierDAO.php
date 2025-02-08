<?php
class SupplierDAO {
    public $db;
    public function __construct()
    {
        $this->db = new DBHelper();
    }

    public function count() {
        try {
            $sql = "select count(*) as count from supplier";
            $res = $this->db->select($sql);
            return $res[0]['count'];
        } catch(Exception $e)
        {
            return 0;
        }
    }

    public function insert($name, $phone, $address, $email = NULL)
    {
        try {
            if($email === NULL) {
                return $this->db->insert('supplier', ['name' => $name, 'address' => $address, 'phone' => $phone]);
            } else {
                return $this->db->insert('supplier', ['name' => $name, 'address' => $address, 'phone' => $phone, 'email' => $email]);
            }
        } catch (Exception $e) {
            return false;
        }
    }


    public function update($id, $name, $phone, $address, $email = NULL) {
        try {
            if($email === NULL) {
                return $this->db->update('supplier', ['name' => $name, 'phone' => $phone, 'address' => $address], "id = $id");
            } else {
                return $this->db->update('supplier', ['name' => $name, 'phone' => $phone, 'address' => $address, 'email' => $email], "id = $id");
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public function selectById($id) {
        $query = 'select * from supplier where id = :id';
        return $this->db->select($query, [':id' => $id]);
    }

    public function delete($id) {
        try {
            $rowCount = $this->db->delete('supplier', "id = $id");
            return $rowCount;
        } catch (Exception $e) {
            return 0;
        }
    }

    public function selectAll() {
        $query = 'select * from supplier';
        return $this->db->select($query);
    }

    public function selectByPage($page, $perPage) {
        $offset = ($page - 1) * $perPage;
        $query = "select * from supplier limit $perPage offset $offset";
        return $this->db->select($query);
    }
}