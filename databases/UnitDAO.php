<?php
class UnitDAO {
    public $db;
    public function __construct()
    {
        $this->db = new DBHelper();
    }

    public function count() {
        try {
            $sql = "select count(*) as count from unit";
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
            $this->db->insert('unit', ['name' => $name, 'description' => $description]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }


    public function update($id, $name, $description) {
        try {
            $this->db->update('unit', ['name' => $name, 'description' => $description], "id = $id");
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function selectById($id) {
        $query = 'select * from unit where id = :id';
        return $this->db->select($query, [':id' => $id]);
    }

    public function delete($id) {
        try {
            $rowCount = $this->db->delete('unit', "id = $id");
            return $rowCount;
        } catch (Exception $e) {
            return 0;
        }
    }

    public function selectAll() {
        $query = 'select * from unit';
        return $this->db->select($query);
    }

    public function selectByPage($page, $perPage) {
        $offset = ($page - 1) * $perPage;
        $query = "select * from unit limit $perPage offset $offset";
        return $this->db->select($query);
    }
}