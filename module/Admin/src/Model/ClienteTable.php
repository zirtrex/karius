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
use Admin\Entity\Cliente;


class ClienteTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function obtenerTodo($paginado = false)
    {
        if ($paginado) {
            
            $select = new Select("ks_cliente");

            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Cliente());

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
        
        $select->from('ks_cliente')
            ->columns(array('*'))
            ->order('cod_cliente ASC');
        
        $resultSet = $this->tableGateway->selectWith($select);
        $resultSet->buffer();
        return $resultSet;
    }    

    public function obtenerCliente($cod_cliente)
    {
        $cod_cliente= (int) $cod_cliente;
        $rowset = $this->tableGateway->select(['cod_cliente' => $cod_cliente]);
        $row = $rowset->current();
        
        if (! $row) {
            throw new RuntimeException(sprintf(
                'No se puede encontrar algún registro con el identificador %d', $cod_cliente
            ));
        }

        return $row;
    }    

    public function guardar(Cliente $cliente)
    {
        $data = [
            'ruc'               => $cliente->ruc,
            'razon_social'      => $cliente->razon_social,
            'direccion_legal'   => $cliente->direccion_legal
            
        ];

        $cod_cliente = (int) $cliente->cod_cliente;

        if ($cod_cliente === 0) {
            $this->tableGateway->insert($data);
            return $this->tableGateway->lastInsertValue;
        }
        
        if (! $this->obtenerCliente($cod_cliente)) {
            throw new RuntimeException(sprintf(
                'No se puede actualizar la fila con el identidicador %d; no existe', $cod_cliente
            ));
        }
        
        $this->tableGateway->update($data, ['cod_cliente' => $cod_cliente]);
        
        return $cod_cliente;
    }

    public function eliminar($cod_cliente)
    {
        return $this->tableGateway->delete(['cod_cliente' => (int) $cod_cliente]);
    }
}

