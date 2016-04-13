<?php

namespace inventarios\gestionCompras\gestionDisponibilidadOrden\funcion;

use inventarios\gestionCompras\gestionDisponibilidadOrden\funcion\redireccion;

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
		
		
		
		$datos = unserialize ( $_REQUEST ['informacion'] );
		
		
		

		$conexion = "inventarios";
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		

		$cadenaSql = $this->miSql->getCadenaSql ( 'noRelacionRegistrar', $_REQUEST['id_disponibilidad'] );
		
		$registro = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "acceso" );
		
		
		
		
		$datos = array (
				"vigencia" => $datos ['vigencia'],
				"numero_disponibilidad" => $datos ['numero_disponibilidad'],
				"unidad_ejecutora" => $datos ['unidad_ejecutora'],
				"usuario" => $_REQUEST ['usuario'],
				"id_disponibilidad" => $_REQUEST ['id_disponibilidad'],
		);
		
		if ($registro == true) {
			$this->miConfigurador->setVariableConfiguracion ( "cache", true );
			
			redireccion::redireccionar ( "NoRelacionRegistro", $datos );
			exit ();
		} else {
			
			redireccion::redireccionar ( "relacionRegistro", $datos );
			exit ();
		}
	}
	function resetForm() {
		foreach ( $_REQUEST as $clave => $valor ) {
			
			if ($clave != 'pagina' && $clave != 'development' && $clave != 'jquery' && $clave != 'tiempo') {
				unset ( $_REQUEST [$clave] );
			}
		}
	}
}

$miRegistrador = new RegistradorOrden ( $this->lenguaje, $this->sql, $this->funcion );

$resultado = $miRegistrador->procesarFormulario ();

?>