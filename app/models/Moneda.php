<?php

namespace app\models;

class Moneda
{
    private $db;

    public function __construct()
    {
        $this->db = new \Database();
    }

    public function getAll()
    {
        $this->db->query('SELECT * FROM moneda');

        $result = $this->db->resultSet();

        return $result;
    }
}
