<?php 
namespace Admin\Model;

use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\Db\Sql\Select;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;
use Admin\Entity\Almacen;


class AlmacenTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function obtenerTodo()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    

    public function obtenerPorCodAlmacen($cod_almacen)
    {
        $cod_almacen= (int) $cod_almacen;
        $rowset = $this->tableGateway->select(['cod_almacen' => $cod_almacen]);
        $row = $rowset->current();
        
        if (! $row) {
            throw new RuntimeException(sprintf(
                'No se puede encontrar una fila con el identificador %d', $cod_almacen
            ));
        }

        return $row;
    }
    
    public function obtenerPorCodCliente($cod_cliente)
    {        
        $resulset = $this->tableGateway->select(['cod_cliente' => (int)$cod_cliente]);
        
        if (! $resulset) {
            throw new RuntimeException(sprintf(
                'No se puede encontrar alguna columna con el identificador %d', $cod_cliente
                ));
        }
        
        return $resulset;
    }
    
    public function guardar(Almacen $almacen)
    {
        $data = [
            'nombre_almacen'        => $almacen->nombre_almacen,
            'direccion_almacen'     => $almacen->direccion_almacen,
            'cod_cliente'           => $almacen->cod_cliente
        ];
        
        $cod_almacen = (int) $almacen->cod_almacen;
        
        if ($cod_almacen === 0) {
            $this->tableGateway->insert($data);
            return $this->tableGateway->lastInsertValue;
        }
        
        if (! $this->obtenerPorCodAlmacen($cod_almacen)) {
            throw new RuntimeException(sprintf(
                'No se puede actualizar la fila con el identidicador %d; no existe', $cod_almacen
                ));
        }
        
        $this->tableGateway->update($data, ['cod_almacen' => $cod_almacen]);
        
        return $cod_almacen;
    }
    
    public function eliminar($cod_almacen)
    {
        return $this->tableGateway->delete(['cod_almacen' => (int) $cod_almacen]);
    }
    
}

