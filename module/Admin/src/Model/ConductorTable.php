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
use Admin\Entity\Conductor;


class ConductorTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function obtenerTodo($paginado = false)
    {
        if ($paginado) {
            
            $select = new Select("ks_conductor");

            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Conductor());

            $paginatorAdapter = new DbSelect($select, $this->tableGateway->getAdapter(), $resultSetPrototype);
            
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
        
        $select->from('ks_conductor')
            ->columns(array('*'))
            ->order('cod_conductor ASC');
        
        $resultSet = $this->tableGateway->selectWith($select);
        $resultSet->buffer();
        return $resultSet;
    }
    
    public function obtenerConductor($cod_conductor)
    {
        $rowset = $this->tableGateway->select(['cod_conductor' => (int) $cod_conductor]);
        $row = $rowset->current();
        
        if (! $row) {
            throw new RuntimeException(sprintf(
                'No se puede encontrar algún registro con el identificador %d', $cod_conductor
            ));
        }
        return $row;
    }
    
    public function guardar(Conductor $conductor)
    {
        $data = [
            'nombres'           => $conductor->nombres,
            'apellidos'         => $conductor->apellidos,
            'numero_licencia'   => $conductor->numero_licencia,
            'edad'              => $conductor->edad,
            'sexo'              => $conductor->sexo,
            'foto'              => $conductor->foto            
        ];

        $cod_conductor = (int) $conductor->cod_conductor;

        if ($cod_conductor === 0) {
            $this->tableGateway->insert($data);
            return $this->tableGateway->lastInsertValue;
        }
        
        if (! $this->obtenerConductor($cod_conductor)) {
            throw new RuntimeException(sprintf(
                'No se puede actualizar la fila con el identidicador %d; no existe', $cod_conductor
                ));
        }
        
        $this->tableGateway->update($data, ['cod_conductor' => $cod_conductor]);
        
        return $cod_conductor;
    }

    public function eliminar($cod_conductor)
    {
        return $this->tableGateway->delete(['cod_conductor' => (int) $cod_conductor]);
    }
}

