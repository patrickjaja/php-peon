<?php

class PublicPeon
{
    public $table="";
    public $query="";
    public $dbinstance="";
    public $primaryKey="";
    public $out="";

    public function __construct() {
        $this->query="SELECT * FROM $this->table";
        $this->getPrimary();
    }

    public function getPrimary() {
        $query="SHOW KEYS FROM $this->table WHERE Key_name = 'PRIMARY'";
        $row = $this->dbinstance->query($query);
        if (isset($row[0]["Column_name"])) {
            $this->primaryKey=$row[0]["Column_name"];
        } else {
            die("No primary found in table $this->table");
        }
    }

    public function load($params) {
        if (isset($params[$this->primaryKey])) {
            $query="SELECT * FROM $this->table WHERE $this->primaryKey=%i_id";
            $row = $this->dbinstance->query($query,array("id"=>$params[$this->primaryKey]));
        } else {
            die("please set $this->primaryKey.");
        }
        return $row;
    }

    public function loadAll() {
        $this->query="SELECT * FROM ".$this->table."";
        $row = $this->dbinstance->query();
        return $row;
    }

    public function insert($vars) {
        $this->dbinstance->insert($this->table, $vars);
    }
}