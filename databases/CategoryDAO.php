<?php
class CategoryDAO {
    public $db;
    public function __construct()
    {
        $this->db = new DBHelper();
    }

    public function count() {
        try {
            $sql = "select count(*) as count from category";
            $res = $this->db->select($sql);
            return $res[0]['count'];
        } catch(Exception $e)
        {
            return 0;
        }
    }

    public function insert($name, $description)
    {
        try {
            $this->db->insert('category', ['name' => $name, 'description' => $description]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }


    public function update($id, $name, $description) {
        try {
            $this->db->update('category', ['name' => $name, 'description' => $description], "id = $id");
            return true;
        } catch (Exception $e) {
            echo $e;
            return false;
        }
    }

    public function selectById($id) {
        $query = 'select * from category where id = :id';
        return $this->db->select($query, [':id' => $id]);
    }

    public function delete($id) {
        try {
            $rowcount = $this->db->delete('category', "id = $id");
            return $rowcount;
        } catch (Exception $e) {
            return 0;
        }
    }

    public function selectAll() {
        $query = 'select * from category';
        return $this->db->select($query);
    }

    public function selectByPage($page, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        $query = "select * from category limit $perPage offset $offset";
        return $this->db->select($query);
    }

    public function getAmountProduct($cateId) {
        $query = 'select count(*) as amount, category_id from product
where category_id = :categoryId';
        $stm = $this->db->prepare($query);
        $stm->bindParam(':categoryId', $cateId);
        $stm->execute();
        $tbl = $stm->fetchAll();
        return $tbl[0]['amount'];
    }
}