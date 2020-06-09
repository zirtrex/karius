<?php
namespace Admin\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use Admin\Model\UsuarioTable;

class UsuarioHelper extends AbstractHelper
{
	protected $container;
	protected $cod_usuario;
	
	public function __construct($container)
	{
		if (!$this->container){
			$this->container = $container;
		}
		return $this->container;
	}
	
	public function __invoke($cod_usuario)
	{		
		$this->cod_usuario = $cod_usuario;
		
		return $this->getDatosUsuario();
	}
	
	private function getDatosUsuario()
	{
        $usuarioTable = $this->container->get(UsuarioTable::class);
        
        return $usuarioTable->obtenerUsuario($this->cod_usuario);
		
	}
}
