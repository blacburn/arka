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
		$datos = array (
				$_REQUEST ['id_orden'],
				$_REQUEST ['mensaje_titulo'],
				$_REQUEST ['usuario'] 
		);
		
		if ($_REQUEST ['valor_orden'] < ($_REQUEST ['total_solicitado'] + $_REQUEST ['valor_solicitud'])) {
			
			redireccion::redireccionar ( "ErrorValorAsignar", $datos );
			
			exit ();
		}
		
		$conexion = "inventarios";
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		$arregloDatos = array (
				"vigencia" => $_REQUEST ['vigencia_disponibilidad'],
				"unidad_ejecutora" => $_REQUEST ['unidad_ejecutora'],
				"diponibilidad" => $_REQUEST ['diponibilidad'],
				"fecha_diponibilidad" => $_REQUEST ['fecha_diponibilidad'],
				"valor_disponibilidad" => $_REQUEST ['valor_disponibilidad'],
				"valor_solicitud" => $_REQUEST ['valor_solicitud'],
				"valorLetras_disponibilidad" => $_REQUEST ['valorLetras_disponibilidad'],
				"id_orden" => $_REQUEST ['id_orden'],
				"id_rubro"=>$_REQUEST['rubro']
		);
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'registrarDisponibilidad', $arregloDatos );
		
		$Orden = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "acceso" );
		
		if ($Orden == true) {
			$this->miConfigurador->setVariableConfiguracion ( "cache", true );
			
			if ($_REQUEST ['valor_orden'] == ($_REQUEST ['total_solicitado'] + $_REQUEST ['valor_solicitud'])) {
				redireccion::redireccionar("insertoDisponibilidadCompleta",$datos);
				exit ();
			} else {
				redireccion::redireccionar ( "insertoDisponibilidad", $datos );
				exit ();
			}
		} else {
			
			redireccion::redireccionar ( "noInsertoDisponibilidad", $datos );
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