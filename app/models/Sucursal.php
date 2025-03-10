<?php

namespace app\models;

class Sucursal
{
    private $db;

    public function __construct()
    {
        $this->db = new \Database();
    }

    public function getSucursalesPorBodega(int $bodegaId)
    {
        $this->db->query('SELECT * FROM sucursal WHERE bodega_id = :bodega_id');

        $this->db->bind(':bodega_id', $bodegaId);

        $result = $this->db->resultSet();

        return $result;
    }
}
