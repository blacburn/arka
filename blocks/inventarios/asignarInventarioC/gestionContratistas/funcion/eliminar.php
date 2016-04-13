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
		
		/*
		 * Datos Contrato
		 */
		$datosContratista = unserialize ( $_REQUEST ['datos'] );
		
		$datosContratista = array_merge ( $datosContratista, array (
				"identificador_contratista" => $_REQUEST ['identificador_contratista'] 
		) );
		
		/*
		 * Registrar Contrato Eliminado
		 */
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'insertar_contratistas_eliminado', $datosContratista );
		
		$registro = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "acceso", $datosContratista, 'insertar_contratistas_eliminado' );
		
		/*
		 * Eliminar Contratista
		 */
		
		if ($registro != false) {
			
			$cadenaSql = $this->miSql->getCadenaSql ( 'eliminar_contratista', $_REQUEST ['identificador_contratista'] );
			$elimino = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "acceso", $_REQUEST ['identificador_contratista'], 'eliminar_contratista' );
		} elseif ($registro == false) {
			
			redireccion::redireccionar ( "NoElimino" );
			exit ();
		}
		
		if ($elimino != false) {
			$this->miConfigurador->setVariableConfiguracion ( "cache", true );
			
			redireccion::redireccionar ( "Elimino" );
			exit ();
		} else {
			
			redireccion::redireccionar ( "NoElimino" );
			exit ();
		}
	}
}

$miRegistrador = new RegistradorOrden ( $this->lenguaje, $this->sql, $this->funcion );

$resultado = $miRegistrador->procesarFormulario ();

?>