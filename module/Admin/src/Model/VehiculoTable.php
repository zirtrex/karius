<?php 
namespace Admin\Model;

use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;
use Laminas\Db\Sql\Expression;
use Admin\Entity\Vehiculo;


class VehiculoTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function obtenerTodo($paginado = false)
    {
        if ($paginado) {
            
            $select = new Select("ks_vehiculo");

            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Vehiculo());

            $paginatorAdapter = new DbSelect($select, $this->tableGateway->getAdapter(),$resultSetPrototype);
            
            $paginator = new Paginator($paginatorAdapter);
            
            return $paginator;
        }

        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    
    public function obtenerTodoPagination(Select $select = null)
    {
        if (null === $select)
        {
            $select = new Select();
        }
        
        $select->from('ks_vehiculo')
            ->columns(array('*'))
            ->order('cod_vehiculo ASC');
        
        $resultSet = $this->tableGateway->selectWith($select);
        $resultSet->buffer();
        return $resultSet;
    }

    public function obtenerVehiculo($cod_vehiculo)
    {
        $cod_vehiculo = (int) $cod_vehiculo;
        $rowset = $this->tableGateway->select(['cod_vehiculo' => $cod_vehiculo]);
        $row = $rowset->current();
        
        if (! $row) {
            throw new RuntimeException(sprintf(
                'No se puede encontrar algún registro con el identificador %d', $cod_vehiculo
            ));
        }

        return $row;
    }
    
    public function obtenerVehiculoPorPlaca($placa)
    {
        $placa = (String) $placa;
        $resulset = $this->tableGateway->select(['placa' => $placa]);
        
        if (! $resulset) {
            throw new RuntimeException(sprintf(
                'No se puede encontrar una columna con el identificador %d', $placa
                ));
        }
        
        return $resulset;
    }

    public function guardar(Vehiculo $vehiculo)
    {
        $data = [
            'marca'     => $vehiculo->marca,
            'placa'     => $vehiculo->placa,
            'modelo'    => $vehiculo->modelo,
            'color'     => $vehiculo->color,
            'soat'      => $vehiculo->soat
            
        ];

        $cod_vehiculo = (int) $vehiculo->cod_vehiculo;

        if ($cod_vehiculo === 0) {
            $this->tableGateway->insert($data);
            return $this->tableGateway->lastInsertValue;
        }
        
        if (! $this->obtenerVehiculo($cod_vehiculo)) {
            throw new RuntimeException(sprintf(
                'No se puede actualizar la fila con el identidicador %d; no existe', $cod_vehiculo
            ));
        }
        
        $this->tableGateway->update($data, ['cod_vehiculo' => $cod_vehiculo]);
        
        return $cod_vehiculo;
    }

    public function eliminar($cod_vehiculo)
    {
        return $this->tableGateway->delete(['cod_vehiculo' => (int) $cod_vehiculo]);
    }
}

