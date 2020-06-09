<?php 
namespace Admin\Entity;

class Traslado
{
    public $cod_traslado;
    public $fecha_traslado;
    public $punto_partida;
    public $punto_llegada;
    public $hora_llegada;
    public $temperatura_llegada;
    public $humedad_relativa_llegada;
    public $hora_salida;
    public $temperatura_salida;
    public $humedad_relativa_salida;
    public $total;    
    public $Cliente;
    public $Conductor;
    public $Vehiculo;
    public $Usuario;
    public $destinatarios = array();
    
    function __construct() {
        $this->Cliente = new Cliente();
        $this->Conductor = new Conductor();
        $this->Vehiculo = new Vehiculo();
    }

    public function exchangeArray(array $data)
    {
        $this->cod_traslado     = !empty($data['cod_traslado']) ? $data['cod_traslado'] : null;
        $this->fecha_traslado   = !empty($data['fecha_traslado']) ? $data['fecha_traslado'] : null;
        $this->punto_partida    = !empty($data['punto_partida']) ? $data['punto_partida'] : null;
        $this->punto_llegada    = !empty($data['punto_llegada']) ? $data['punto_llegada'] : null;
        $this->hora_llegada     = !empty($data['hora_llegada']) ? $data['hora_llegada'] : null;
        $this->temperatura_llegada      = !empty($data['temperatura_llegada']) ? $data['temperatura_llegada'] : null;
        $this->humedad_relativa_llegada = !empty($data['humedad_relativa_llegada']) ? $data['humedad_relativa_llegada'] : null;
        $this->hora_salida      = !empty($data['hora_salida']) ? $data['hora_salida'] : null;
        $this->temperatura_salida       = !empty($data['temperatura_salida']) ? $data['temperatura_salida'] : null;
        $this->humedad_relativa_salida  = !empty($data['humedad_relativa_salida']) ? $data['humedad_relativa_salida'] : null;
        $this->total            = !empty($data['total']) ? $data['total'] : null;       
        
        $this->Cliente = new Cliente();        
        $this->Cliente->cod_cliente         = !empty($data['cod_cliente']) ? $data['cod_cliente'] : null;
        $this->Cliente->razon_social        = !empty($data['razon_social']) ? $data['razon_social'] : null;
        
        $this->Conductor = new Conductor();        
        $this->Conductor->cod_conductor     = !empty($data['cod_conductor']) ? $data['cod_conductor'] : null;
        $this->Conductor->nombres           = !empty($data['nombres']) ? $data['nombres'] : null;
        $this->Conductor->apellidos         = !empty($data['apellidos']) ? $data['apellidos'] : null;
        $this->Conductor->numero_licencia   = !empty($data['numero_licencia']) ? $data['numero_licencia'] : null;
        
        $this->Vehiculo = new Vehiculo();        
        $this->Vehiculo->cod_vehiculo       = !empty($data['cod_vehiculo']) ? $data['cod_vehiculo'] : null;
        $this->Vehiculo->placa              = !empty($data['placa']) ? $data['placa'] : null;        
        
        
        $this->Usuario = new Usuario();        
        $this->Usuario->cod_usuario         = !empty($data['cod_usuario']) ? $data['cod_usuario'] : null;
        $this->Usuario->nombres             = !empty($data['u_nombres']) ? $data['u_nombres'] : null;
        $this->Usuario->apellidos           = !empty($data['u_apellidos']) ? $data['u_apellidos'] : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}

