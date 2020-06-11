<?php

namespace Users\Storage;

use Laminas\Authentication\Storage\Session;
use Laminas\Authentication\Storage\StorageInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Session\SaveHandler\DbTableGatewayOptions;
use Laminas\Session\SaveHandler\DbTableGateway;
use Laminas\Session\Config\SessionConfig;


class AuthStorage extends Session implements StorageInterface
{	
    const NAME = 'karius_session';
    
    private $storage;
    private $resolvedIdentity;
    
    public function setDbHandler(Adapter $adapter)
    {
        $tableGateway = new TableGateway("ks_session", $adapter);
        
        $saveHandler = new DbTableGateway($tableGateway, new DbTableGatewayOptions());
        
        $sessionConfig = new SessionConfig();
        $saveHandler->open($sessionConfig->getOption('save_path'), self::NAME);
        
        $this->session->getManager()->setSaveHandler($saveHandler);
    }
	
	public function isEmpty()
	{
	    if ($this->getStorage()->isEmpty()) {
	        return true;
	    }
	    $identity = $this->getStorage()->read();
	    if ($identity === null) {
	        $this->clear();
	        return true;
	    }
	    return false;
	}
	
	public function read()
	{
	    if ($this->resolvedIdentity !== null) {
	        return $this->resolvedIdentity;
	    }
	    $identity = $this->getStorage()->read();
	    if ($identity) {
	        $this->resolvedIdentity = $identity;
	    } else {
	        $this->resolvedIdentity = null;
	    }
	    return $this->resolvedIdentity;
	}
	
	public function write($contents)
	{
	    /**
	     * when $this->authService->authenticate(); is valid, the session
	     * automatically called write('username')
	     * in this case, i want to save data like
	     * ["storage"] => array(4) {
	     * ["id"] => string(1) "1"
	     * ["username"] => string(5) "admin"
	     * ["ip_address"] => string(9) "127.0.0.1"
	     * ["user_agent"] => string(81) "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7;
	     * rv:18.0)
	     * Gecko/20100101 Firefox/18.0"
	     * }
	     */
	    $this->resolvedIdentity = null;
	    $this->getStorage()->write($contents);
	    
	    if (is_array($contents) && !empty($contents)){
	        $this->session->getManager()->getSaveHandler()->write($this->session->getManager()->getId(), \Zend\Json\Json::encode($contents));
	    }
	}
	
    public function clear()
    {
        $this->resolvedIdentity = null;
        $this->getStorage()->clear();
    }
    
    public function setStorage(StorageInterface $storage)
    {
        $this->storage = $storage;
        return $this;
    }
    
    public function getStorage()
    {
        if ($this->storage === null) {
            $this->setStorage(new Session(self::NAME));
        }
        return $this->storage;
    }
}









