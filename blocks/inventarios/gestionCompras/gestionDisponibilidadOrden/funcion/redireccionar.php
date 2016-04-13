<?

namespace inventarios\gestionCompras\gestionDisponibilidadOrden\funcion;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("index.php");
	exit ();
}
class redireccion {
	public static function redireccionar($opcion, $valor = "", $valor1 = "") {
		$miConfigurador = \Configurador::singleton ();
		$miPaginaActual = $miConfigurador->getVariableConfiguracion ( "pagina" );
		
		switch ($opcion) {
			
			case "NoRelacionRegistro" :
				
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=GestionRegistroPresupuestal";
				$variable .= "&usuario=" . $valor ['usuario'];
				$variable .= "&vigencia=" . $valor ['vigencia'];
				$variable .= "&unidad_ejecutora=" . $valor ['unidad_ejecutora'];
				$variable .= "&numero_disponibilidad=" . $valor ['numero_disponibilidad'];
				$variable .= "&id_disponibilidad=" . $valor ['id_disponibilidad'];
				$variable .= "&mensaje=NoRelacionRegistro";
				
				break;
			
			case "insertoRegistro" :
				
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=GestionRegistroPresupuestal";
				$variable .= "&usuario=" . $valor ['usuario'];
				$variable .= "&vigencia=" . $valor ['vigencia'];
				$variable .= "&unidad_ejecutora=" . $valor ['unidad_ejecutora'];
				$variable .= "&numero_disponibilidad=" . $valor ['numero_disponibilidad'];
				$variable .= "&id_disponibilidad=" . $valor ['id_disponibilidad'];
				$variable .= "&mensaje=registroPresupuestal";
				
				break;
			
			case "datosVacios" :
				
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=mensaje";
				$variable .= "&mensaje=datosVacios";
				$variable .= "&id_orden=" . $valor [1];
				$variable .= "&mensaje_titulo=" . $valor [0];
				$variable .= "&fecha_orden=" . $valor [2];
				
				if ($valor [3] == '') {
					$variable .= "&registroOrden=true";
				} else {
					
					$variable .= "&arreglo=" . $valor [3];
				}
				
				break;
			
			case "insertoDisponibilidad" :
				
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=cargarDisponibilidad";
				$variable .= "&id_orden=" . $valor [0];
				$variable .= "&mensaje_titulo=" . $valor [1];
				$variable .= "&usuario=" . $valor [2];
				$variable .= "&mensaje=registro";
				
				break;
			
			case "ModificarDisponibilidad" :
				
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=cargarDisponibilidad";
				$variable .= "&id_orden=" . $valor [0];
				$variable .= "&mensaje_titulo=" . $valor [1];
				$variable .= "&usuario=" . $valor [2];
				$variable .= "&mensaje=modifico";
				
				break;
			
			case "noInsertoDisponibilidad" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=cargarDisponibilidad";
				$variable .= "&id_orden=" . $valor [0];
				$variable .= "&mensaje_titulo=" . $valor [1];
				$variable .= "&usuario=" . $valor [2];
				$variable .= "&mensaje=Noregistro";
				break;
			
			case "noModificoDisponibilidad" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=cargarDisponibilidad";
				$variable .= "&id_orden=" . $valor [0];
				$variable .= "&mensaje_titulo=" . $valor [1];
				$variable .= "&usuario=" . $valor [2];
				$variable .= "&mensaje=NoModifico";
				break;
			
			case "ErrorValorAsignar" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=cargarDisponibilidad";
				$variable .= "&id_orden=" . $valor [0];
				$variable .= "&mensaje_titulo=" . $valor [1];
				$variable .= "&usuario=" . $valor [2];
				$variable .= "&mensaje=ErrorValorAsignar";
				break;
			
			case "ErrorValorAsignarModificar" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=cargarDisponibilidad";
				$variable .= "&id_orden=" . $valor [0];
				$variable .= "&mensaje_titulo=" . $valor [1];
				$variable .= "&usuario=" . $valor [2];
				$variable .= "&mensaje=ErrorValorAsignarModificar";
				break;
			
			case "insertoDisponibilidadCompleta" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=cargarDisponibilidad";
				$variable .= "&id_orden=" . $valor [0];
				$variable .= "&mensaje_titulo=" . $valor [1];
				$variable .= "&usuario=" . $valor [2];
				$variable .= "&mostrarFormularioRegistro=false";
				$variable .= "&mensaje=insertoDisponibilidadCompleta";
				break;
			
			case "ModificarDisponibilidadCompleta" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=cargarDisponibilidad";
				$variable .= "&id_orden=" . $valor [0];
				$variable .= "&mensaje_titulo=" . $valor [1];
				$variable .= "&usuario=" . $valor [2];
				$variable .= "&mostrarFormularioRegistro=false";
				$variable .= "&mensaje=ModificoDisponibilidadCompleta";
				break;
			
			case "noFormatoImagen" :
				
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=mensaje";
				$variable .= "&mensaje=noFormatoImagen";
				$variable .= "&usuario=" . $valor;
				break;
			
			case "noExtension" :
				
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=mensaje";
				$variable .= "&mensaje=noExtension";
				break;
			
			case "noArchivoCarga" :
				
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=mensaje";
				$variable .= "&mensaje=noArchivoCarga";
				break;
			
			case "noInsertoMasivo" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=mensaje";
				$variable .= "&mensaje=error";
				$variable .= "&id_orden=" . $valor [1];
				$variable .= "&mensaje_titulo=" . $valor [0];
				// $variable .= "&fecha_orden=" . $valor [2];
				
				if ($valor [3] == '') {
					$variable .= "&registroOrden=true";
				} else {
					
					$variable .= "&arreglo=" . $valor [3];
				}
				
				break;
			
			case "noInserto" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=cargarElemento";
				$variable .= "&mensaje=error";
				$variable .= "&mensaje_titulo=" . $valor [0];
				$variable .= "&id_orden=" . $valor [1];
				
				break;
			
			case "noCargarElemento" :
				
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=mensaje";
				$variable .= "&mensaje=noElemento";
				
				break;
			
			case "notextos" :
				$variable = "pagina=" . $miPaginaActual;
				$variable .= "&opcion=mensaje";
				$variable .= "&mensaje=otros";
				$variable .= "&errores=notextos";
				
				break;
			
			case "Salir" :
				
				$variable = "pagina=indexAlmacen";
				
				break;
			
			case "SalidaElemento" :
				
				$variable = "pagina=registrarSalidas";
				$variable .= "&opcion=Salida";
				$variable .= "&numero_entrada=" . $valor;
				$variable .= "&datosGenerales=" . $valor1;
				break;
			
			case "RegistrarActa" :
				
				$variable = "pagina=registrarActa";
				$variable .= "&opcion=asociarActa";
				$variable .= "&mensaje_titulo=" . $valor ['mensaje_titulo'];
				$variable .= "&numero_orden=" . $valor ['id_orden'];
				$variable .= "&fecha_orden=" . date ( 'Y-m-d' );
				break;
		}
		
		foreach ( $_REQUEST as $clave => $valor ) {
			unset ( $_REQUEST [$clave] );
		}
		
		$url = $miConfigurador->configuracion ["host"] . $miConfigurador->configuracion ["site"] . "/index.php?";
		$enlace = $miConfigurador->configuracion ['enlace'];
		$variable = $miConfigurador->fabricaConexiones->crypto->codificar ( $variable );
		$_REQUEST [$enlace] = $enlace . '=' . $variable;
		$redireccion = $url . $_REQUEST [$enlace];
		
		echo "<script>location.replace('" . $redireccion . "')</script>";
		
		// $enlace =$miConfigurador->getVariableConfiguracion("enlace");
		// $variable = $miConfigurador->fabricaConexiones->crypto->codificar($variable);
		// // echo $enlace;
		// // // echo $variable;
		// // exit;
		// $_REQUEST[$enlace] = $variable;
		// $_REQUEST["recargar"] = true;
		// return true;
	}
}

?>