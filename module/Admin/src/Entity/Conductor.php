<?php 
namespace Admin\Entity;


class Conductor
{
    public $cod_conductor;
    public $nombres;
    public $apellidos;
    public $numero_licencia;
    public $edad;
    public $sexo;
    public $foto;

    public function exchangeArray(array $data)
    {
        $this->cod_conductor    = !empty($data['cod_conductor']) ? $data['cod_conductor'] : null;
        $this->nombres          = !empty($data['nombres']) ? $data['nombres'] : null;
        $this->apellidos        = !empty($data['apellidos']) ? $data['apellidos'] : null;
        $this->numero_licencia  = !empty($data['numero_licencia']) ? $data['numero_licencia'] : null;
        $this->edad             = !empty($data['edad']) ? $data['edad'] : null;
        $this->sexo             = !empty($data['sexo']) ? $data['sexo'] : null;
        $this->foto             = !empty($data['foto']) ? $data['foto'] : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}
