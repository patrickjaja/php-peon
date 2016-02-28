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
}