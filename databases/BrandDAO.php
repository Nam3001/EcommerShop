<?php
class BrandDAO {
    public $db;
    public function __construct()
    {
        $this->db = new DBHelper();
    }

    public function insert($name, $description)
    {
        try {
            $this->db->insert('brand', ['name' => $name, 'description' => $description]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function count() {
        try {
            $sql = "select count(*) as count from brand";
            $res = $this->db->select($sql);
            return $res[0]['count'];
        } catch(Exception $e)
        {
            return 0;
        }
    }


    public function update($id, $name, $description) {
        try {
            $this->db->update('brand', ['name' => $name, 'description' => $description], "id = $id");
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function selectById($id) {
        $query = 'select * from brand where id = :id';
        return $this->db->select($query, [':id' => $id]);
    }

    public function delete($id) {
        try {
            $rowCount = $this->db->delete('brand', "id = $id");
            return $rowCount;
        } catch (Exception $e) {
            return 0;
        }
    }

    public function selectAll() {
        $query = 'select * from brand';
        return $this->db->select($query);
    }

    public function selectByPage($page, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        $query = "select * from brand limit $perPage offset $offset";
        return $this->db->select($query);
    }

    public function getAmountProduct($brandId) {
        $query = 'select count(*) as amount, brand_id from product
where brand_id = :brandId';
        $stm = $this->db->prepare($query);
        $stm->bindParam(':brandId', $brandId);
        $stm->execute();
        $tbl = $stm->fetchAll();
        return $tbl[0]['amount'];
    }
}