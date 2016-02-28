<?php

class PublicPeon
{
    public $server="";
    public $table="";
    public $query="";
    public $dbinstance="";
    public $primaryKey="";
    public $out="";
    public $tableSchema=array();

    public function __construct($server) {
        $this->dbinstance=$server->dbinstance;
        $this->out=$server->output;
        $this->query="SELECT * FROM $this->table";
        $this->getPrimary();
    }

    public function getPrimary() {
        $query="SHOW FIELDS FROM $this->table";
        $row = $this->dbinstance->query($query);
        $this->tableSchema=$row;

        $hasPK=false;
        foreach($row as $key=>$val) {
          if ($val["Key"]=="PRI") {
            $this->primaryKey=$val["Field"];
            $hasPK=true;
          }
        }
        if(!$hasPK) {
          $this->out->error("No primary found in table $this->table");
        }
    }

    public function load($params) {
        if (isset($params[$this->primaryKey])) {
            $query="SELECT * FROM $this->table WHERE $this->primaryKey=%i_id";
            $row = $this->dbinstance->query($query,array("id"=>$params[$this->primaryKey]));
            $this->applyVars($row[0]);
        } else {
            $this->out->error("please set $this->primaryKey.");
        }
        return $row;
    }

    public function applyVars($result) {
      foreach($result as $key=>$val) {
        $this->$key=$val;
      }
    }
    public function read($vars) {
      $searchFor = [];
      $searchForStr = "";

      //Check field exist in Schema
      foreach($vars as $key=>$val) {
        foreach($this->tableSchema as $field) {
          if ($key==$field["Field"]) {
            $searchFor[$key] = $val;
            $searchForStr.="$key = %s_$key AND ";
          }
        }
      }
      if (count($searchFor) > 0) {
        $searchForStr = substr($searchForStr, 0, -4);
        $query="SELECT * FROM $this->table WHERE $searchForStr";
        $row = $this->dbinstance->query($query,$searchFor);
        return $row;
      } else {
        $this->out->error(array("Tell me what you want to read. ",$this->tableSchema));
      }
    }

    public function loadAll() {
        $this->query="SELECT * FROM ".$this->table."";
        $row = $this->dbinstance->query();
        return $row;
    }
    public function update($vars) {
      $id=0;
      $updateVars=array();

      //Check field exist in Schema
      foreach($vars as $key=>$val) {
        foreach($this->tableSchema as $field) {
          if ($key==$field["Field"] && $field["Key"]!="PRI") {
            $updateVars[$key]=$val;
          }
        }
      }

      //Set PK
      if (isset($vars[$this->primaryKey])) {
        $id=$vars[$this->primaryKey];
        //do not update the PK
        unset($vars[$this->primaryKey]);
      } else {
        if (!isset($this[$this->primaryKey])) {
          $this->out->error("Set ID in update");
        } else {
          $id=$this[$this->primaryKey];
        }
      }
      $this->dbinstance->update($this->table
                              , $updateVars
                              , "$this->primaryKey=%i"
                              , $id);
    }
    public function insert($vars) {
      $insertVars=array();

      //Check field exist in Schema
      foreach($vars as $key=>$val) {
        foreach($this->tableSchema as $field) {
          if ($key==$field["Field"] && $field["Key"]!="PRI") {
            $insertVars[$key]=$val;
          }
        }
      }
      $this->dbinstance->insert($this->table, $insertVars);
      return $this->dbinstance->insertId();
    }
}
