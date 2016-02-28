<?php

class Test extends PublicPeon {

    public function __construct($peon) {
        $this->dbinstance=$peon->dbinstance;
        $this->out=$peon->output;
        $this->table=strtolower(static::class);
        parent::__construct();
    }
    public function load($params) {
        $response=parent::load($params);
        $this->out->ok($response);
    }
    public function read($params) {
        $response=parent::read($params);
        $this->out->ok($response);
    }
    public function insert($params) {
        $lastID=parent::insert($params);
        $response=parent::load(array($this->primaryKey=>$lastID));
        $this->out->ok($response);
    }
    public function update($params) {
      parent::update($params);
      $response=parent::load($params);
      $this->out->ok($response);
    }
}