<?php 
namespace Admin\Entity;


class Vehiculo
{
    public $cod_vehiculo;
    public $marca;
    public $placa;
    public $modelo;
    public $color;
    public $soat;


    public function exchangeArray(array $data)
    {
        $this->cod_vehiculo = !empty($data['cod_vehiculo']) ? $data['cod_vehiculo'] : null;
        $this->marca        = !empty($data['marca']) ? $data['marca'] : null;
        $this->placa        = !empty($data['placa']) ? $data['placa'] : null;
        $this->modelo       = !empty($data['modelo']) ? $data['modelo'] : null;
        $this->color        = !empty($data['color']) ? $data['color'] : null;
        $this->soat         = !empty($data['soat']) ? $data['soat'] : null;        
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}
