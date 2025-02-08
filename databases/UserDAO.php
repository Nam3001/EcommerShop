<?php
class UserDAO {
    public $db;
    public function __construct()
    {
        $this->db = new DBHelper();
    }

    public function count() {
        try {
            $sql = "select count(*) as count from user";
            $res = $this->db->select($sql);
            return $res[0]['count'];
        } catch(Exception $e)
        {
            return 0;
        }
    }

    public function loginAdmin($username, $password) {
        try {
            $sql = "select user.*, user_role.role_id as roleId, role.name as roleName from (select * from user where username = :username and password = :password) as user join user_role on user.id = user_role.user_id
join role
on user_role.role_id = role.id
where role.name = 'admin' and user_role.role_id = 1";
            $stm = $this->db->prepare($sql);
            $stm->bindParam(':username', $username);
            $stm->bindParam(':password', $password);
            $stm->execute();

            $res = $stm->fetchAll();

            if(count($res) === 0) return false;
            else {
                if($res[0]['roleId'] === 1 && $res[0]['roleName'] === 'admin')
                    return $res[0];
                else return false;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function loginNormalUser($username, $password) {
        try {
            $sql = "select user.*, user_role.role_id as roleId, role.name as roleName from (select * from user where username = :username and password = :password) as user join user_role on user.id = user_role.user_id
join role
on user_role.role_id = role.id
where role.name = 'customer' and user_role.role_id = 2";
            $stm = $this->db->prepare($sql);
            $stm->bindParam(':username', $username);
            $stm->bindParam(':password', $password);
            $stm->execute();

            $res = $stm->fetchAll();

            if(count($res) === 0) return false;
            else {
                if($res[0]['roleId'] === 2 && $res[0]['roleName'] === 'customer')
                    return $res[0];
                else return false;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            return  false;
        }
    }

    public function selectAllUsers() {
        try {
            return $this->db->select('user');
        } catch (Exception $e) {
            echo $e;
            return array();
        }
    }

    public function deleteUser($username) {
        try {
            $rowcount = $this->db->delete('user', "username = $username");
            return $rowcount;
        } catch (Exception $e) {
            return 0;
        }
    }

    public function insertUser() {

    }

    public function updateUser() {

    }
}
