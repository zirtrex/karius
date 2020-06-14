<?php 
namespace Admin\Model;

use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\Db\Sql\Select;
use Laminas\Db\ResultSet\ResultSet;
use Admin\Entity\Destinatario;


class DestinatarioTable
{
    private $tableGateway;
    private $container;

    public function __construct(TableGatewayInterface $tableGateway, $container)
    {
        $this->tableGateway = $tableGateway;
        $this->container = $container;
    }

    public function obtenerTodo()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    

    public function obtenerDestinatario($cod_destinatario)
    {
        $rowset = $this->tableGateway->select(['cod_destinatario' => (int) $cod_destinatario]);
        $row = $rowset->current();
        
        if (! $row) {
            throw new RuntimeException(sprintf(
                'No se puede encontrar una fila con el identificador %d', $cod_destinatario
            ));
        }

        return $row;
    }
    
    public function obtenerDestinatariosPorCodTraslado($cod_traslado)
    {        
        $resultset = $this->tableGateway->select(['cod_traslado' => (int) $cod_traslado]);
        return $resultset;
    }
    
    public function guardar(Destinatario $destinatario)
    {
        $data = [
            'nombre'                    => $destinatario->nombre,
            'distrito'                  => $destinatario->distrito,
            'numero_guia'               => $destinatario->numero_guia,
            'hora_llegada'              => $destinatario->hora_llegada,
            'temperatura_llegada'       => $destinatario->temperatura_llegada,
            'humedad_relativa_llegada'  => $destinatario->humedad_relativa_llegada,
            'hora_entrega'              => $destinatario->hora_entrega,
            'temperatura_entrega'       => $destinatario->temperatura_entrega,
            'humedad_relativa_entrega'  => $destinatario->humedad_relativa_entrega,
            'hora_salida'               => $destinatario->hora_salida,
            'cod_traslado'              => (int) $destinatario->cod_traslado,
        ];
        
        $cod_destinatario = (int) $destinatario->cod_destinatario;
        
        if ($cod_destinatario === 0) {
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
        
        $trasladoTable = $this->container->get(TrasladoTable::class);
        
        if (! $trasladoTable->obtenerTraslado((int) $destinatario->cod_traslado)) {
            throw new RuntimeException(sprintf(
                'No se puede encontrar algun registro con el identidicador %d', (int) $destinatario->cod_traslado
                ));
        }
        
        $this->tableGateway->update($data, ['cod_destinatario' => $cod_destinatario]);
        
        return $cod_destinatario;
    }
    
    public function eliminar($cod_destinatario)
    {
        return $this->tableGateway->delete(['cod_destinatario' => (int) $cod_destinatario]);
    }
}

