<?php

namespace inventarios\gestionCompras\consultaOrdenServicios\funcion;

use inventarios\gestionCompras\consultaOrdenServicios\funcion\redireccion;

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
		
		if ($_REQUEST ['objeto_contrato'] == '') {
			
			redireccion::redireccionar ( 'notextos' );
			exit ();
		}
		
		if ($_REQUEST ['forma_pago'] == '') {
			
			redireccion::redireccionar ( 'notextos' );
			exit ();
		}
		
		$datosSupervisor = array (
				$_REQUEST ['nombre_supervisor'],
				$_REQUEST ['cargo_supervisor'],
				$_REQUEST ['dependencia_supervisor'],
				$_REQUEST ['sede_super'],
				$_REQUEST ['supervisor'] 
		);
		
		// Actualizar Supervisor
		$cadenaSql = $this->miSql->getCadenaSql ( 'actualizarSupervisor', $datosSupervisor );
		$id_supervisor = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda", $datosSupervisor, 'actualizarSupervisor' );
		
		$datosProveedor = array (
				$_REQUEST ['nombre_razon_proveedor'],
				$_REQUEST ['identifcacion_proveedor'],
				$_REQUEST ['direccion_proveedor'],
				$_REQUEST ['telefono_proveedor'],
				$_REQUEST ['proveedor'] 
		);
		
		// Actualizar Contratista
		$cadenaSql = $this->miSql->getCadenaSql ( 'actualizarProveedor', $datosProveedor );
		
		$Proveedor = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda", $datosProveedor, 'actualizarProveedor' );
		
		$datosContratista = array (
				$_REQUEST ['nombre_contratista'],
				$_REQUEST ['identifcacion_contratista'],
				$_REQUEST ['cargo_contratista'],
				$_REQUEST ['contratista'] 
		)
		;
		
		// Actualizar Contratista
		$cadenaSql = $this->miSql->getCadenaSql ( 'actualizarContratista', $datosContratista );
		
		$Contratista = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda", $datosContratista, 'actualizarContratista' );
		
		// Actualizar Orden
		
		$datosOrden = array (
				$_REQUEST ['dependencia_solicitante'],
				$_REQUEST ['sede'],
				$_REQUEST ['objeto_contrato'],
				isset ( $_REQUEST ['poliza1'] ),
				isset ( $_REQUEST ['poliza2'] ),
				isset ( $_REQUEST ['poliza3'] ),
				isset ( $_REQUEST ['poliza4'] ),
				$_REQUEST ['duracion'],
				$_REQUEST ['fecha_inicio_pago'],
				$_REQUEST ['fecha_final_pago'],
				$_REQUEST ['forma_pago'],
				$_REQUEST ['id_ordenador'],
				$_REQUEST ['tipo_ordenador'],
				$_REQUEST ['id_orden'] 
		);
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'actualizarOrden', $datosOrden );
		
		$id_orden = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "acceso", $datosOrden, 'actualizarOrden' );
		
		$datos = array (
				$_REQUEST ['id_orden'],
				$_REQUEST ['mensaje_titulo'],
				$_REQUEST ['arreglo'] 
		);
		
		if ($id_orden == true) {
			$this->miConfigurador->setVariableConfiguracion ( "cache", true );
			redireccion::redireccionar ( 'inserto', $datos );
		} else {
			
			redireccion::redireccionar ( 'noInserto', $datos );
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