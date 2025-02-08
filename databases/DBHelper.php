<?php

class DBHelper extends PDO {
    function __construct()
    {
        $connectionString = "mysql:host=" . HOST . ";dbname=" . DB_NAME . ";";
        parent::__construct($connectionString, USER, PASSWORD);
    }

    public function select($sql, $params = []) {
        try {
            $statement = $this->prepare($sql);
            if(!empty($params)) {
                foreach ($params as $key => &$value) {
                    $statement->bindParam($key, $value);
                }
            }
            $statement->execute();
            return $statement->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    public function update($tblName, $data = [], $cond = "") {
        // set value
        $values = "";
        foreach ($data as $key => $value) {
            $values .= "$key = :$key, ";
        }

        $values = rtrim($values, ', ');

        $sql = "UPDATE $tblName SET $values WHERE $cond";
        $statement = $this->prepare($sql);

        foreach ($data as $key => &$value) {
            $statement->bindParam(":$key", $value);
        }
        return $statement->execute();
    }
    public function insert($tblName, $data = []) {
        // chuan bi danh sach column va danh sach values
        $columns = implode(', ', array_keys($data));
        $values = ":" . implode(', :', array_keys($data));


        $sql = "INSERT INTO $tblName($columns) VALUES($values)";
        $statement = $this->prepare($sql);

        // set value
        foreach ($data as $key => &$value) {
            $statement->bindParam(":$key", $value);
        }

        $res =  $statement->execute();
        return $res;
    }
    public function delete($tblName, $cond) {
        try {
            $sql = "DELETE FROM $tblName WHERE $cond";
            return $this->exec($sql);
        } catch(Exception $e) {
            return false;
        }
    }

    public function affectedRow($sql, $data = []) {
        $statement = $this->prepare($sql);
        $statement->execute($data);
        return $statement->rowCount();
    }
}