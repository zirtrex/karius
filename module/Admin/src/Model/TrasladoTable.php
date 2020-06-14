<?php 
namespace Admin\Model;

use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Where;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Expression;
use Laminas\Db\Sql\Predicate\PredicateSet;
use Admin\Entity\Traslado;
use Laminas\Db\Sql\Ddl\Column\Date;


class TrasladoTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }   

    public function obtenerTodo($paginado = false)
    {
        if ($paginado) {
            
            $select = new Select();
            
            $select->from('ks_vw_traslado')
                ->order('cod_traslado ASC');

            //$resultSetPrototype = new ResultSet();
            //$resultSetPrototype->setArrayObjectPrototype(new Pagos());

            $paginatorAdapter = new DbSelect($select, $this->tableGateway->getAdapter(), null/*$resultSetPrototype*/);
            
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
        
        $select->from('ks_vw_traslado')
            ->columns(array('*'))
            ->order('cod_traslado ASC');
        
        $resultSet = $this->tableGateway->selectWith($select);
        $resultSet->buffer();
        return $resultSet;
    }
    
    public function obtenerTraslado($cod_traslado)
    {
        $cod_traslado = (int) $cod_traslado;
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        
        $select->from('ks_vw_traslado')->columns(array('*'));
        
        $select->where(['cod_traslado' => $cod_traslado]);
        
        $resultset = $this->tableGateway->selectWith($select);
        $row = $resultset->current();
        
        if (! $row) {
            throw new RuntimeException(sprintf(
                'No se puede encontrar algún registro con el identificador %d', $cod_traslado
            ));
        }
        
        return $row;
    }   
    
    public function obtenerTrasladosPorFecha($fechaInicial = null, $fechaFinal = null, $orderBy = ['fecha_traslado' => 'ASC'])
    {
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        
        $select->from('ks_vw_traslado')->columns(array('*'));
        
        if($fechaInicial !== null){ 
            $fechaInicial = new Date();
        }
        
        if($fechaFinal !== null){
            $fechaFinal = new Date();
        }
        
        $select->where->between('fecha_traslado', $fechaInicial, $fechaFinal);
        
        if($orderBy !== null){ $select->order($orderBy); }
        
        $resultset = $this->tableGateway->selectWith($select);
        $resultset->buffer();
        
        return $resultset;
    }
    
    public function obtenerUsuariosConRequerimientos($semestre = null)
    {
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        
        $select->from('vw_requerimiento')->columns(array('*'));
        
        if($semestre !== null){
            $spec = function (Where $where) use ($semestre) {
                $where->like('semestre', $semestre . '%');
            };
            $select->where($spec);
        }        
        
        $select->group("codUsuario");        
        
        $resultset = $this->tableGateway->selectWith($select);
        $resultset->buffer();
        
        return $resultset;
    }
    
    public function obtenerCursosByCodUsuario($codUsuario = null)
    {
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        
        $select->from('vw_requerimiento')->columns(array('*'));
        
        if($codUsuario!== null){ $select->where(array('codUsuario' => $codUsuario)); }
        
        $select->group(array("semestre", "nombreCurso"));
        
        $resultset = $this->tableGateway->selectWith($select);
        $resultset->buffer();
        
        return $resultset;
    }
    
    public function obtenerCursosByCodUsuarioAndSemestre($codUsuario = null, $semestre = null)
    {
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        
        $select->from('vw_requerimiento')->columns(array('*'));
        
        
        if($codUsuario!== null){ $select->where(array('codUsuario' => $codUsuario)); }
        if($semestre !== null){
            $spec = function (Where $where) use ($semestre) {
                $where->like('semestre', $semestre . '%');
            };
            $select->where($spec);
        }
        
        $select->group(array("semestre", "nombreCurso"));
        
        $resultset = $this->tableGateway->selectWith($select);
        $resultset->buffer();
        
        return $resultset;
    }
    
    public function obtenerSumaPedidos(Array $where = null, $group = null, $semestre = null)
    {
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = new Select();
        
        $select->from('vw_requerimiento')
            ->columns(array('*', 'totalPedido' => New Expression('SUM(cantidad)')));        
        
        
        //if($semestre !== null){ $select->where(array('semestre' => $semestre)); }
        
        if($semestre !== null){
            $spec = function (Where $where) use ($semestre) {
                $where->like('semestre', $semestre . '%');
            };
            $select->where($spec);
        }
        
        if($group !== null){ $select->group($group); }
        
        $select->order('semestre');
        
        //var_dump($select->getSqlString()); return ;
        
        $statement = $sql->prepareStatementForSqlObject($select);
        $resultSet = $statement->execute();
        
        return $resultSet;
    }

    public function guardar(Traslado $traslado)
    {
        $data = [
            'fecha_traslado'            => $traslado->fecha_traslado,
            'punto_partida'             => $traslado->punto_partida,
            'punto_llegada'             => $traslado->punto_llegada,
            'hora_llegada'              => $traslado->hora_llegada,
            'temperatura_llegada'       => $traslado->temperatura_llegada,
            'humedad_relativa_llegada'  => $traslado->humedad_relativa_llegada,
            'hora_salida'               => $traslado->hora_salida,
            'temperatura_salida'        => $traslado->temperatura_salida,
            'humedad_relativa_salida'   => $traslado->humedad_relativa_salida,
            'total'                     => $traslado->total,
            'cod_usuario'               => $traslado->Usuario->cod_usuario,
            'cod_cliente'               => $traslado->Cliente->cod_cliente,
            'cod_vehiculo'              => $traslado->Vehiculo->cod_vehiculo,
            'cod_conductor'             => $traslado->Conductor->cod_conductor
        ];
        
        $cod_traslado = (int) $traslado->cod_traslado;
        
        if ($cod_traslado === 0) {
            try
            {
                $this->tableGateway->insert($data);
                return $this->tableGateway->lastInsertValue;
            }
            catch (\Exception $e)
            {
                throw new \Exception($e->getPrevious()->getMessage(),$e->getPrevious()->getCode(), $e->getPrevious()->getPrevious());
                return false;
            } 
        }

        if (! $this->obtenerTraslado($cod_traslado)) {
            throw new RuntimeException(sprintf(
                'No se puede encontrar algun registro con el identidicador %d', $cod_traslado
                ));
        }
        
        $this->tableGateway->update($data, ['cod_traslado' => $cod_traslado]);
        
        return $cod_traslado;
    }    

    public function eliminar($cod_traslado)
    {
        return $this->tableGateway->delete(['cod_traslado' => $cod_traslado]);        
        
    }
}

