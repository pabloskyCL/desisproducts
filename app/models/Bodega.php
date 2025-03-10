<?php

namespace app\models;

class Bodega
{
    private $db;

    public function __construct()
    {
        $this->db = new \Database();
    }

    public function getAll()
    {
        $this->db->query('SELECT * FROM bodega');

        return $this->db->resultSet();
    }
}
