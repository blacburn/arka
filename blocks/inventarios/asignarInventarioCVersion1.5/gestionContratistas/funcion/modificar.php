<?php
use inventarios\asignarInventarioC\gestionContratista\funcion\redireccion;

include_once ('redireccionar.php');

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class RegistradorOrden {
	var $miConfigurador;
	var $lenguaje;
	var $miFormulario;
	var $miFuncion;
	var $miSql;
	var $conexion;
	function __construct($lenguaje, $sql, $funcion) {
		$this->miConfigurador = \Configurador::singleton ();
		$this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );
		$this->lenguaje = $lenguaje;
		$this->miSql = $sql;
		$this->miFuncion = $funcion;
	}
	function procesarFormulario() {
		$conexion = "inventarios";
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		$datos = array (
				"vigencia" => $_REQUEST ['vigencia'],
				"numero" => $_REQUEST ['numero'],
				"identificacion" => $_REQUEST ['identificacion'],
				"nombre" => $_REQUEST ['nombre'],
				"fecha_inicio" => $_REQUEST ['fecha_inicio'],
				"fecha_final" => $_REQUEST ['fecha_final'],
				"identificador" => $_REQUEST ['identificador_contratista'] 
		);
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'modificarContrato', $datos );
		
		$Actualizacion = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "acceso" );
		
		 
		
		if ($Actualizacion != false) {
			$this->miConfigurador->setVariableConfiguracion ( "cache", true );
			
			redireccion::redireccionar ( "Actualizo" );
			exit ();
		} else {
			
			redireccion::redireccionar ( "NoActualizo" );
			exit ();
		}
	}
}

$miRegistrador = new RegistradorOrden ( $this->lenguaje, $this->sql, $this->funcion );

$resultado = $miRegistrador->procesarFormulario ();

?>