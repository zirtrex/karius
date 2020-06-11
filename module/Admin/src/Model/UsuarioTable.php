<?php 
namespace Admin\Model;

use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;
use Admin\Entity\Usuario;

class UsuarioTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function obtenerTodo($paginado = false)
    {
        if ($paginado) {
            
            $select = new Select("ks_usuario");

            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Usuario());

            $paginatorAdapter = new DbSelect($select, $this->tableGateway->getAdapter(),$resultSetPrototype);
            
            $paginator = new Paginator($paginatorAdapter);
            
            return $paginator;
        }
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function obtenerUsuario($cod_usuario)
    {
        $rowset = $this->tableGateway->select(["cod_usuario" => (int) $cod_usuario]);
        $row = $rowset->current();
        
        if (! $row) {
            throw new RuntimeException(sprintf(
                'No se puede encontrar algún registro con el identificador %d', $cod_usuario
            ));
        }

        return $row;
    }
    
    public function obtenerUsuarioPorCorreo($correo)
    {
        $rowset = $this->tableGateway->select(['correo' => $correo]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'No se puede encontrar algún registro con el identificador %d', $correo
                ));
        }
        
        return $row;
    }
    
    public function obtenerUsuarioPorToken($token)
    {
        $rowset = $this->tableGateway->select(['token_registro' => $token]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'No se puede encontrar algún registro con el identificador %d', $token
                ));
        }
    
        return $row;
    }
    
    public function registrarUsuario(Usuario $usuario)
    {
        $data = [
            'rol'               => $usuario->rol,
            'usuario'           => $usuario->usuario,
            'clave'             => $usuario->clave,
            'nombres'           => $usuario->nombres,
            'apellidos'         => $usuario->apellidos,
            'correo'            => $usuario->correo,
            'telefono'          => $usuario->telefono,
            'fecha_registro'    => $usuario->fecha_registro,
            'token_registro'    => $usuario->token_registro,
            'correo_confirmado' => $usuario->correo_confirmado,
        ];
        
        $cod_usuario = (int) $usuario->cod_usuario;
        
        $sql = new Sql($this->tableGateway->getAdapter());
        
        if ($cod_usuario === 0) {
            $this->tableGateway->insert($data);
            return $this->tableGateway->lastInsertValue;
        }
        
        return $cod_usuario;
    }

    public function guardarUsuario(Usuario $usuario)
    {
        $data = [
            'rol'               => $usuario->rol,            
            'nombres'           => $usuario->nombres,
            'apellidos'         => $usuario->apellidos,
            'correo'            => $usuario->correo,            
            'telefono'          => $usuario->telefono,
            'fecha_registro'    => $usuario->fecha_registro,
            'token_registro'    => $usuario->token_registro,
            'correo_confirmado' => $usuario->correo_confirmado,
        ];

        $cod_usuario = (int) $usuario->cod_usuario;
        
        $sql = new Sql($this->tableGateway->getAdapter());
        
        if ($cod_usuario === 0) {            
            $this->tableGateway->insert($data);
            return $this->tableGateway->lastInsertValue;
        }

        if (! $this->obtenerUsuario($cod_usuario)) {
            throw new RuntimeException(sprintf(
                'No se ha podido guardar el id %d; no existe', $cod_usuario
            ));
        }
        $update = $sql->update('usuario');
        $update->set($data)->where(['cod_usuario' => $cod_usuario]);
        $statement = $sql->prepareStatementForSqlObject($update);        
        $row = $statement->execute();
        
        return $cod_usuario;
    }
    
    public function guardarClave(Usuario $usuario)
    {
        $data = [
            'clave' => $usuario->clave,
        ];
        
        $cod_usuario = (int) $usuario->cod_usuario;
        
        $sql = new Sql($this->tableGateway->getAdapter());
        
        if (! $this->obtenerUsuario($cod_usuario)) {
            throw new RuntimeException(sprintf(
                'No se ha podido guardar el id %d; no existe', $cod_usuario
                ));
        }
        $update = $sql->update('usuario');
        $update->set($data)->where(['cod_usuario' => $cod_usuario]);
        $statement = $sql->prepareStatementForSqlObject($update);
        $row = $statement->execute();
        
        return $cod_usuario;
    }
    
    public function guardarUsuarioImagen(Usuario $usuario)
    {
        $data = [            
            'imagen_perfil' => $usuario->imagen_perfil,
        ];
    
        $cod_usuario = (int) $usuario->cod_usuario;
    
        if (! $this->obtenerUsuario($cod_usuario)) {
            throw new RuntimeException(sprintf(
                'No se ha podido guardar el id %d; no existe', $cod_usuario
            ));
        }
        
        $sql = new Sql($this->tableGateway->getAdapter());
        $update = $sql->update('usuario');
        $update->set($data)->where(['cod_usuario' => $cod_usuario]);
        $statement = $sql->prepareStatementForSqlObject($update);
        return $row = $statement->execute();
    }

    public function eliminarUsuario($codUsuario)
    {
        $sql = new Sql($this->tableGateway->getAdapter());
        $delete = $sql->delete('usuario');
        $delete->where(['codUsuario' => (int) $codUsuario]);
        $statement = $sql->prepareStatementForSqlObject($insert);
        return $row = $statement->execute();
    }
}

