<?php


namespace component\GestorHTMLCRUD\Modelo;

include_once ('core/connection/DAL.class.php');


if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

  

class Base{
	
	

	protected $dao =  NULL;
	
	public function __construct($conexion = ''){

		if(!$this->setDataAccessObject($conexion)) return false;
		 
	}
	
	public function setDataAccessObject($conexion = ''){
		
		
		if($conexion == '') return false;
		
		if($this->dao instanceof \DAL);
		else $this->dao =  new \DAL();
		
		$this->dao->setConexion($conexion);
		
		if(is_object($this->dao)) return true;
		
		return false;
		
	}
	
	public function unsetDao(){
		unset($this->dao);
	}
	
	public function __call($method_name, $arguments){
		
		if(is_null($this->dao)) return false;

		if(method_exists ($this , $method_name )) 
			return call_user_func_array(array($this , $method_name), $arguments);
		
		return  call_user_func_array(array($this->dao , $method_name), $arguments);
		 
		
	}
	
	


}
