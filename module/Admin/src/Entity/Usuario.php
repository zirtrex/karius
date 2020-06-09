<?php 
namespace Admin\Entity;

class Usuario
{
    public $cod_usuario;
    public $rol;
    public $usuario;
    public $clave;
    public $nombres;
    public $apellidos;
    public $correo;
    public $telefono;
    public $imagen_perfil;
    public $fecha_registro;
    public $token_registro;
    public $correo_confirmado;

    public function exchangeArray(array $data)
    {
        $this->cod_usuario      = !empty($data['cod_usuario']) ? $data['cod_usuario'] : null;
        $this->rol              = !empty($data['rol']) ? $data['rol'] : null;
        $this->usuario          = !empty($data['usuario']) ? $data['usuario'] : null;
        $this->clave            = !empty($data['clave']) ? $data['clave'] : null;
        $this->nombres          = !empty($data['nombres']) ? $data['nombres'] : null;
        $this->apellidos        = !empty($data['apellidos']) ? $data['apellidos'] : null;
        $this->correo           = !empty($data['correo']) ? $data['correo'] : null;
        $this->telefono         = !empty($data['telefono']) ? $data['telefono'] : null;
        $this->imagen_perfil    = !empty($data['imagen_perfil']) ? $data['imagen_perfil'] : null;
        $this->fecha_registro   = !empty($data['fecha_registro']) ? $data['fecha_registro'] : null;
        $this->token_registro   = !empty($data['token_registro']) ? $data['token_registro'] : null;
        $this->correo_confirmado= !empty($data['correo_confirmado']) ? $data['correo_confirmado'] : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}

