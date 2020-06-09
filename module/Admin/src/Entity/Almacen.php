<?php 
namespace Admin\Entity;

class Almacen
{
    public $cod_almacen;
    public $nombre_almacen;
    public $direccion_almacen;
    public $cod_cliente;

    public function exchangeArray(array $data)
    {
        $this->cod_almacen          = !empty($data['cod_almacen']) ? $data['cod_almacen'] : null;
        $this->nombre_almacen       = !empty($data['nombre_almacen']) ? $data['nombre_almacen'] : null;
        $this->direccion_almacen    = !empty($data['direccion_almacen']) ? $data['direccion_almacen'] : null;
        $this->cod_cliente          = !empty($data['cod_cliente']) ? $data['cod_cliente'] : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}
