<?php

class db_con{
    private $configs;
    public $db_connect;

    public function __construct()
    {

        $this->configs = include ('config.inc.php');
        $this->db_connect = new mysqli($this->configs['database']['dbserver'],$this->configs['database']['dbuser'],$this->configs['database']['dbpass'],$this->configs['database']['dbname']);
    }


}