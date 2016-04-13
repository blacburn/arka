<?php


namespace component\GestorHTMLCRUD\Modelo;


include_once ('component/GestorHTMLCRUD/Modelo/Base.class.php');

use component\GestorHTMLCRUD\Modelo\Base as Base;


if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}


class Modelo{
	
	CONST conexion = 'estructura';
	
	private $nameBase  ;
	
	private $objeto =  null;
	private $nombre;
	private $consultas;
	private $conexion = null;


	public function __construct($nombre = 'Base'){
		
		$this->nameBase = __NAMESPACE__.'\\';
		
		
		if(!$this->seleccionarClase($nombre)) return false;
		 
	}
	
	public function getConsultas(){
		return $this->consultas;
	}
	
	public function setConexion($conexion){
		$this->conexion =  $conexion;
	}
	
	private function seleccionarClase($nombre){
		if($nombre =='') return false;
		
		$nombre = ucfirst(strtolower($nombre));
		$this->nombre =  $nombre;
		$conexion = is_null($this->conexion)?self::conexion:$this->conexion;
		if(!is_a($this->objeto, $this->nameBase.$nombre)){
		  $class = new \ReflectionClass($this->nameBase.$nombre);
		  $this->objeto = $class->newInstanceArgs(array($conexion));
			
		}
		$this->objeto->setDataAccessObject($conexion) ;
		
		if(is_object($this->objeto)) return true;
		return false;
		
	}	
	
	
	public function __call($method_name, $arguments){
		
		
		if(!isset($this->objeto)||is_null($this->objeto)) return false;
		
		$conexion = is_null($this->conexion)?self::conexion:$this->conexion;
		
		$this->objeto->setDataAccessObject($conexion) ;
		$ejecucion = call_user_func_array(array($this->objeto , $method_name), $arguments);
		$this->consulta[] = $this->objeto->getQuery();
		return $ejecucion;
		
	}


}
