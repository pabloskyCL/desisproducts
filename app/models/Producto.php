<?php

namespace app\models;

class Producto
{
    private $db;

    public function __construct()
    {
        $this->db = new \Database();
    }

    public function nuevoProducto($data)
    {
        $this->db->query('INSERT INTO producto (codigo,nombre,moneda_id,precio,descripcion,materiales)
             VALUES (:codigo, :nombre,:moneda_id,:precio,:descripcion,:materiales)');
        $this->db->bind(':codigo', $data['codigo']);
        $this->db->bind(':nombre', $data['nombre']);
        $this->db->bind(':moneda_id', $data['moneda']);
        $this->db->bind(':precio', $data['precio']);
        $this->db->bind(':descripcion', $data['descripcion']);
        $this->db->bind(':materiales', $data['materiales']);
        $this->db->execute();

        $this->db->query('INSERT INTO producto_sucursal (producto_id,sucursal_id)
             VALUES (:producto_id, :sucursal_id)');
        $this->db->bind(':producto_id', $data['codigo']);
        $this->db->bind(':sucursal_id', $data['sucursal']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
