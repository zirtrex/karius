<?php 
namespace Admin\Entity;


class Cliente
{
    public $cod_cliente;
    public $ruc;
    public $razon_social;
    public $direccion_legal;
    

    public function exchangeArray(array $data)
    {
        $this->cod_cliente      = isset($data['cod_cliente']) ? $data['cod_cliente'] : null;
        $this->ruc              = isset($data['ruc']) ? $data['ruc'] : null;
        $this->razon_social     = isset($data['razon_social']) ? $data['razon_social'] : null;
        $this->direccion_legal  = isset($data['direccion_legal']) ? $data['direccion_legal'] : null;        
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}
