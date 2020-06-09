<?php 
namespace Admin\Entity;


class Destinatario
{
    public $cod_destinatario;
    public $nombre;
    public $distrito;
    public $numero_guia;
    public $hora_llegada;
    public $temperatura_llegada;
    public $humedad_relativa_llegada;
    public $hora_entrega;
    public $temperatura_entrega;
    public $humedad_relativa_entrega;
    public $hora_salida;
    public $cod_traslado;

    public function exchangeArray(array $data)
    {
        $this->cod_destinatario     = !empty($data['cod_destinatario']) ? $data['cod_destinatario'] : null;
        $this->nombre               = !empty($data['nombre']) ? $data['nombre'] : null;
        $this->distrito             = !empty($data['distrito']) ? $data['distrito'] : null;
        $this->numero_guia          = !empty($data['numero_guia']) ? $data['numero_guia'] : null;
        $this->hora_llegada         = !empty($data['hora_llegada']) ? $data['hora_llegada'] : null;
        $this->temperatura_llegada  = !empty($data['temperatura_llegada']) ? $data['temperatura_llegada'] : null;
        $this->humedad_relativa_llegada = !empty($data['humedad_relativa_llegada']) ? $data['humedad_relativa_llegada'] : null;
        $this->hora_entrega         = !empty($data['hora_entrega']) ? $data['hora_entrega'] : null;
        $this->temperatura_entrega  = !empty($data['temperatura_entrega']) ? $data['temperatura_entrega'] : null;
        $this->humedad_relativa_entrega = !empty($data['humedad_relativa_entrega']) ? $data['humedad_relativa_entrega'] : null;
        $this->hora_salida          = !empty($data['hora_salida']) ? $data['hora_salida'] : null;
        $this->cod_traslado         = !empty($data['cod_traslado']) ? $data['cod_traslado'] : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}
