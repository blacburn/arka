<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class registrarForm {
	var $miConfigurador;
	var $lenguaje;
	var $miFormulario;
	var $miSql;
	function __construct($lenguaje, $formulario, $sql) {
		$this->miConfigurador = \Configurador::singleton ();
		
		$this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );
		
		$this->lenguaje = $lenguaje;
		
		$this->miFormulario = $formulario;
		
		$this->miSql = $sql;
	}
	function miForm() {
		
		// Rescatar los datos de este bloque
		$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
		
		// ---------------- SECCION: Parámetros Globales del Formulario ----------------------------------
		/**
		 * Atributos que deben ser aplicados a todos los controles de este formulario.
		 * Se utiliza un arreglo
		 * independiente debido a que los atributos individuales se reinician cada vez que se declara un campo.
		 *
		 * Si se utiliza esta técnica es necesario realizar un mezcla entre este arreglo y el específico en cada control:
		 * $atributos= array_merge($atributos,$atributosGlobales);
		 */
		
		$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "host" );
		$rutaBloque .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/";
		$rutaBloque .= $esteBloque ['grupo'] . "/" . $esteBloque ['nombre'];
		
		$host = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/inventarios/gestionCompras/" . $esteBloque ['nombre'] . "/plantilla/archivo_elementos.xlsx";
		
		$atributosGlobales ['campoSeguro'] = 'true';
		
		$_REQUEST ['tiempo'] = time ();
		$tiempo = $_REQUEST ['tiempo'];
		
		// lineas para conectar base de d atos-------------------------------------------------------------------------------------------------
		$conexion = "inventarios";
		
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		$seccion ['tiempo'] = $tiempo;
		
		$conexion = "inventarios";
		
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		// ---------------- SECCION: Parámetros Generales del Formulario ----------------------------------
		$esteCampo = $esteBloque ['nombre'];
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		// Si no se coloca, entonces toma el valor predeterminado 'application/x-www-form-urlencoded'
		$atributos ['tipoFormulario'] = 'multipart/form-data';
		// Si no se coloca, entonces toma el valor predeterminado 'POST'
		$atributos ['metodo'] = 'POST';
		// Si no se coloca, entonces toma el valor predeterminado 'index.php' (Recomendado)
		$atributos ['action'] = 'index.php';
		// $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo );
		// Si no se coloca, entonces toma el valor predeterminado.
		$atributos ['estilo'] = '';
		$atributos ['marco'] = false;
		$tab = 1;
		// ---------------- FIN SECCION: de Parámetros Generales del Formulario ----------------------------
		
		// ----------------INICIAR EL FORMULARIO ------------------------------------------------------------
		$atributos ['tipoEtiqueta'] = 'inicio';
		echo $this->miFormulario->formulario ( $atributos );
		{
			
			if (! isset ( $_REQUEST ['mostrarFormularioRegistro'] )) {
				$_REQUEST ['mostrarFormularioRegistro'] = true;
			} else {
				$_REQUEST ['mostrarFormularioRegistro'] = false;
			}
			
			$cadenaSql = $this->miSql->getCadenaSql ( 'consultarValorElementos', $_REQUEST ['id_orden'] );
			
			$valores_orden = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
			$valores_orden = $valores_orden [0];
			
			$cadenaSql = $this->miSql->getCadenaSql ( 'consultarDisponibilidadModificar', $_REQUEST ['id_disponibilidad'] );
			
			$disponibilidad_modificar = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
			$disponibilidad_modificar = $disponibilidad_modificar [0];
			
			$arreglo = array (
					"vigencia_disponibilidad" => $disponibilidad_modificar ['vigencia'],
					"unidad_ejecutora" => $disponibilidad_modificar ['unidad_ejecutora'],
					"diponibilidad" => $disponibilidad_modificar ['numero_diponibilidad'],
					"fecha_diponibilidad" => $disponibilidad_modificar ['fecha_disponibilidad'],
					"rubro" => $disponibilidad_modificar ['id_rubro'],
					"valor_disponibilidad" => $disponibilidad_modificar ['valor_diponibilidad'],
					"valor_solicitud" => $disponibilidad_modificar ['valor_solicitado'],
					"valorLetras_disponibilidad" => $disponibilidad_modificar ['valor_letras_solicitud'] 
			);
			
			$_REQUEST = array_merge ( $_REQUEST, $arreglo );
			
			$cadenaSql = $this->miSql->getCadenaSql ( 'consultarDisponibilidades', $_REQUEST ['id_orden'] );
			
			$disponibilidades = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
			
			$cadenaSql = $this->miSql->getCadenaSql ( 'consultarValorDisponibilidades', $_REQUEST ['id_orden'] );
			
			$disponibilidadesvalor = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
			
			$total = $disponibilidadesvalor [0] [0];
			
			$miPaginaActual = $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
			
			$directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
			$directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
			$directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );
			
			$variable = "pagina=" . $miPaginaActual;
			$variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
			
			// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
			$esteCampo = 'botonRegresar';
			$atributos ['id'] = $esteCampo;
			$atributos ['enlace'] = $variable;
			$atributos ['tabIndex'] = 1;
			$atributos ['estilo'] = 'textoSubtitulo';
			$atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['ancho'] = '10%';
			$atributos ['alto'] = '10%';
			$atributos ['redirLugar'] = true;
			echo $this->miFormulario->enlace ( $atributos );
			
			$esteCampo = "marcoDatosBasicos";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = $_REQUEST ['mensaje_titulo'];
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			unset ( $atributos );
			
			{
				
				if (isset ( $_REQUEST ['mensaje'] )) {
					switch ($_REQUEST ['mensaje']) {
						case 'registro' :
							$atributos ['mensaje'] = "<center>SE REGISTRO DISPONIBILIDAD PRESUPUESTAL</center>";
							$atributos ["estilo"] = 'success';
							break;
						
						case 'Noregistro' :
							$atributos ['mensaje'] = "<center>ERROR AL REGISTRAR DISPONIBILIDAD PRESUPUESTAL<BR>Verifique los Datos</center>";
							$atributos ["estilo"] = 'error';
							break;
						
						case 'ErrorValorAsignar' :
							$atributos ['mensaje'] = "<center>EL VALOR TOTAL SOLICITADO ES MAYOR AL VALOR DE LA ORDEN<br>Verifique los Datos</center>";
							$atributos ["estilo"] = 'error';
							break;
						
						case 'insertoDisponibilidadCompleta' :
							$atributos ['mensaje'] = "<center>SE REGISTRO EL TOTAL DEL VALOR DE LA ORDEN<BR>CON LAS DISPONIBILIDADES PRESUPUESTALES</center>";
							$atributos ["estilo"] = 'success';
							break;
					}
					
					// -------------Control texto-----------------------
					$esteCampo = 'divMensaje';
					$atributos ['id'] = $esteCampo;
					$atributos ["tamanno"] = '';
					$atributos ["etiqueta"] = '';
					$atributos ["columnas"] = ''; // El control ocupa 47% del tamaño del formulario
					echo $this->miFormulario->campoMensaje ( $atributos );
					unset ( $atributos );
				}
				
				$esteCampo = 'Valor_Orden';
				$atributos ["id"] = $esteCampo;
				$atributos ["estilo"] = $esteCampo;
				$atributos ['columnas'] = 1;
				$atributos ["estilo"] = $esteCampo;
				$atributos ['texto'] = "Valor Orden  : $ " . number_format ( $valores_orden ['valor'], 2, ",", "." );
				$tab ++;
				echo $this->miFormulario->campoTexto ( $atributos );
				unset ( $atributos );
				
				$atributos ["id"] = "Valor_Orden";
				echo $this->miFormulario->division ( "inicio", $atributos );
				unset ( $atributos );
				{
					$esteCampo = 'ValorResidual';
					$atributos ["id"] = $esteCampo;
					$atributos ["estilo"] = $esteCampo;
					$atributos ['columnas'] = 1;
					$atributos ["estilo"] = $esteCampo;
					$atributos ['texto'] = "Valor Total Solicitado : $ " . number_format ( $total, 2, ",", "." ) . "";
					$tab ++;
					echo $this->miFormulario->campoTexto ( $atributos );
					unset ( $atributos );
					
					$valor_faltante = ($valores_orden ['valor'] - $total);
					
					$esteCampo = 'ValorFaltante';
					$atributos ["id"] = $esteCampo;
					$atributos ["estilo"] = $esteCampo;
					$atributos ['columnas'] = 1;
					$atributos ["estilo"] = $esteCampo;
					$atributos ['texto'] = "Valor Faltante : $ " . number_format ( $valor_faltante, 2, ",", "." ) . "";
					$tab ++;
					echo $this->miFormulario->campoTexto ( $atributos );
					unset ( $atributos );
				}
				
				echo $this->miFormulario->division ( 'fin' );
				
				if ($valor_faltante == 0) {
					
					$_REQUEST ['mostrarFormularioRegistro'] = false;
				} else {
					
					$_REQUEST ['mostrarFormularioRegistro'] = true;
				}
				
				// if ($_REQUEST ['mostrarFormularioRegistro'] == true) {
				$esteCampo = "AgrupacionDisponibilidad";
				$atributos ['id'] = $esteCampo;
				$atributos ['leyenda'] = "Modificación de Certificado Disponibilidad Presupuestal";
				echo $this->miFormulario->agrupacion ( 'inicio', $atributos );
				{
					
					$esteCampo = "vigencia_disponibilidad";
					$atributos ['nombre'] = $esteCampo;
					$atributos ['id'] = $esteCampo;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ['tab'] = $tab ++;
					$atributos ['anchoEtiqueta'] = 180;
					$atributos ['evento'] = '';
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['seleccion'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['seleccion'] = - 1;
					}
					$atributos ['deshabilitado'] = false;
					$atributos ['columnas'] = 2;
					$atributos ['tamanno'] = 1;
					$atributos ['ajax_function'] = "";
					$atributos ['ajax_control'] = $esteCampo;
					$atributos ['estilo'] = "jqueryui";
					$atributos ['validar'] = "required";
					$atributos ['limitar'] = 1;
					$atributos ['anchoCaja'] = 27;
					$atributos ['miEvento'] = '';
					$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "vigencia_disponibilidad" );
					$matrizItems = array (
							array (
									0,
									'' 
							) 
					);
					$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
					$atributos ['matrizItems'] = $matrizItems;
					// $atributos['miniRegistro']=;
					$atributos ['baseDatos'] = "sicapital";
					// $atributos ['baseDatos'] = "inventarios";
					
					// $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "clase_entrada" );
					
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					unset ( $atributos );
					
					$esteCampo = "unidad_ejecutora";
					$atributos ['nombre'] = $esteCampo;
					$atributos ['id'] = $esteCampo;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ['tab'] = $tab ++;
					$atributos ['seleccion'] = 1;
					$atributos ['anchoEtiqueta'] = 180;
					$atributos ['evento'] = '';
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$atributos ['deshabilitado'] = false;
					$atributos ['columnas'] = 2;
					$atributos ['tamanno'] = 1;
					$atributos ['ajax_function'] = "";
					$atributos ['ajax_control'] = $esteCampo;
					$atributos ['estilo'] = "jqueryui";
					$atributos ['validar'] = "required";
					$atributos ['limitar'] = 1;
					$atributos ['anchoCaja'] = 27;
					$atributos ['miEvento'] = '';
					$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "Unidad_Ejecutoria" );
					$matrizItems = array (
							array (
									0,
									' ' 
							) 
					);
					$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
					
					$atributos ['matrizItems'] = $matrizItems;
					// $atributos['miniRegistro']=;
					$atributos ['baseDatos'] = "sicapital";
					// $atributos ['baseDatos'] = "inventarios";
					
					// $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "clase_entrada" );
					
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'diponibilidad';
					$atributos ['nombre'] = $esteCampo;
					$atributos ['id'] = $esteCampo;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ['tab'] = $tab ++;
					
					$atributos ['anchoEtiqueta'] = 180;
					$atributos ['evento'] = '';
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['seleccion'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['seleccion'] = - 1;
					}
					$atributos ['deshabilitado'] = false;
					$atributos ['columnas'] = 2;
					$atributos ['tamanno'] = 1;
					$atributos ['ajax_function'] = "";
					$atributos ['ajax_control'] = $esteCampo;
					$atributos ['estilo'] = "jqueryui";
					$atributos ['validar'] = "required";
					$atributos ['limitar'] = 1;
					$atributos ['anchoCaja'] = 50;
					$atributos ['miEvento'] = '';
					$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "buscar_disponibilidad", array (
							$_REQUEST ['vigencia_disponibilidad'],
							$_REQUEST ['unidad_ejecutora'] 
					) );
					
					$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
					$atributos ['matrizItems'] = $matrizItems;
					// $atributos['miniRegistro']=;
					$atributos ['baseDatos'] = "sicapital";
					// $atributos ['baseDatos'] = "inventarios";
					
					// $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "clase_entrada" );
					
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					unset ( $atributos );
					
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'fecha_diponibilidad';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'fecha';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['estiloMarco'] = '';
					$atributos ["etiquetaObligatorio"] = false;
					$atributos ['columnas'] = 2;
					$atributos ['dobleLinea'] = 0;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['validar'] = '';
					
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = true;
					$atributos ['tamanno'] = 8;
					$atributos ['maximoTamanno'] = '';
					$atributos ['anchoEtiqueta'] = 180;
					$tab ++;
					
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
					
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'rubro';
					$atributos ['nombre'] = $esteCampo;
					$atributos ['id'] = $esteCampo;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ['tab'] = $tab ++;
					
					$atributos ['anchoEtiqueta'] = 180;
					$atributos ['evento'] = '';
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['seleccion'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['seleccion'] = - 1;
					}
					$atributos ['deshabilitado'] = false;
					$atributos ['columnas'] = 1;
					$atributos ['tamanno'] = 1;
					$atributos ['ajax_function'] = "";
					$atributos ['ajax_control'] = $esteCampo;
					$atributos ['estilo'] = "jqueryui";
					$atributos ['validar'] = "required";
					$atributos ['limitar'] = 1;
					$atributos ['anchoCaja'] = 80;
					$atributos ['miEvento'] = '';
					$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "consultarRubro", array (
							$disponibilidad_modificar ['vigencia'],
							$disponibilidad_modificar ['numero_diponibilidad'],
							$disponibilidad_modificar ['unidad_ejecutora'],
							
							
					) );
					
					$matrizItems = $esteRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
					$atributos ['matrizItems'] = $matrizItems;
					// $atributos['miniRegistro']=;
					$atributos ['baseDatos'] = "sicapital";
					// $atributos ['baseDatos'] = "inventarios";
					
					// $atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( "clase_entrada" );
					
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoCuadroLista ( $atributos );
					unset ( $atributos );
					
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'valor_disponibilidad';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'fecha';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['estiloMarco'] = '';
					$atributos ["etiquetaObligatorio"] = false;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = 0;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['validar'] = '';
					
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = true;
					$atributos ['tamanno'] = 30;
					$atributos ['maximoTamanno'] = '';
					$atributos ['anchoEtiqueta'] = 180;
					$tab ++;
					
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
					
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'valor_solicitud';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'fecha';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['estiloMarco'] = '';
					$atributos ["etiquetaObligatorio"] = false;
					$atributos ['columnas'] = 1;
					$atributos ['dobleLinea'] = 0;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					$atributos ['validar'] = 'required,minSize[1],custom[number]';
					
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
					$atributos ['deshabilitado'] = false;
					$atributos ['tamanno'] = 30;
					$atributos ['maximoTamanno'] = '';
					$atributos ['anchoEtiqueta'] = 180;
					$tab ++;
					
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					
					echo $this->miFormulario->campoCuadroTexto ( $atributos );
					unset ( $atributos );
					
					// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
					$esteCampo = 'valorLetras_disponibilidad';
					$atributos ['id'] = $esteCampo;
					$atributos ['nombre'] = $esteCampo;
					$atributos ['tipo'] = 'text';
					$atributos ['estilo'] = 'jqueryui';
					$atributos ['marco'] = true;
					$atributos ['estiloMarco'] = '';
					$atributos ["etiquetaObligatorio"] = true;
					$atributos ['columnas'] = 115;
					$atributos ['filas'] = 3;
					$atributos ['dobleLinea'] = 0;
					$atributos ['tabIndex'] = $tab;
					$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
					// $atributos ['validar'] = 'required, minSize[1]';
					$atributos ['deshabilitado'] = true;
					$atributos ['tamanno'] = 20;
					$atributos ['maximoTamanno'] = '';
					$atributos ['anchoEtiqueta'] = 220;
					if (isset ( $_REQUEST [$esteCampo] )) {
						$atributos ['valor'] = $_REQUEST [$esteCampo];
					} else {
						$atributos ['valor'] = '';
					}
					$tab ++;
					
					// Aplica atributos globales al control
					$atributos = array_merge ( $atributos, $atributosGlobales );
					echo $this->miFormulario->campoTextArea ( $atributos );
					unset ( $atributos );
				}
				
				echo $this->miFormulario->agrupacion ( 'fin' );
				
				// ------------------Division para los botones-------------------------
				$atributos ["id"] = "botones";
				$atributos ["estilo"] = "marcoBotones";
				echo $this->miFormulario->division ( "inicio", $atributos );
				
				// -----------------CONTROL: Botón ----------------------------------------------------------------
				$esteCampo = 'botonAceptar';
				$atributos ["id"] = $esteCampo;
				$atributos ["tabIndex"] = $tab;
				$atributos ["tipo"] = 'boton';
				// submit: no se coloca si se desea un tipo button genérico
				$atributos ['submit'] = 'true';
				$atributos ["estiloMarco"] = '';
				$atributos ["estiloBoton"] = 'jqueryui';
				// verificar: true para verificar el formulario antes de pasarlo al servidor.
				$atributos ["verificar"] = '';
				$atributos ["tipoSubmit"] = 'jquery'; // Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
				$atributos ["valor"] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ['nombreFormulario'] = $esteBloque ['nombre'];
				$tab ++;
				
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoBoton ( $atributos );
				// -----------------FIN CONTROL: Botón -----------------------------------------------------------
				
				echo $this->miFormulario->division ( 'fin' );
				
				echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				
				// ---------------- FIN SECCION: Controles del Formulario -------------------------------------------
				
				// ----------------FINALIZAR EL FORMULARIO ----------------------------------------------------------
				// Se debe declarar el mismo atributo de marco con que se inició el formulario.
			}
			
			// -----------------FIN CONTROL: Botón -----------------------------------------------------------
			
			// ------------------Fin Division para los botones-------------------------
			echo $this->miFormulario->division ( "fin" );
			
			// ------------------- SECCION: Paso de variables ------------------------------------------------
			
			/**
			 * En algunas ocasiones es útil pasar variables entre las diferentes páginas.
			 * SARA permite realizar esto a través de tres
			 * mecanismos:
			 * (a). Registrando las variables como variables de sesión. Estarán disponibles durante toda la sesión de usuario. Requiere acceso a
			 * la base de datos.
			 * (b). Incluirlas de manera codificada como campos de los formularios. Para ello se utiliza un campo especial denominado
			 * formsara, cuyo valor será una cadena codificada que contiene las variables.
			 * (c) a través de campos ocultos en los formularios. (deprecated)
			 */
			
			// En este formulario se utiliza el mecanismo (b) para pasar las siguientes variables:
			
			$valorCodificado = "action=" . $esteBloque ["nombre"];
			$valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
			$valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
			$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
			$valorCodificado .= "&opcion=modificar";
			$valorCodificado .= "&id_orden=" . $_REQUEST ['id_orden'];
			$valorCodificado .= "&id_disponibilidad=" . $_REQUEST ['id_disponibilidad'];
			$valorCodificado .= "&mensaje_titulo=" . $_REQUEST ['mensaje_titulo'];
			$valorCodificado .= "&total_solicitado=" . $total;
			$valorCodificado .= "&solicitado_anterior=" . $_REQUEST ['solicitado_anterior'];
			$valorCodificado .= "&valor_orden=" . $valores_orden ['valor'];
			$valorCodificado .= "&usuario=" . $_REQUEST ['usuario'];
			
			/**
			 * SARA permite que los nombres de los campos sean dinámicos.
			 * Para ello utiliza la hora en que es creado el formulario para
			 * codificar el nombre de cada campo. Si se utiliza esta técnica es necesario pasar dicho tiempo como una variable:
			 * (a) invocando a la variable $_REQUEST ['tiempo'] que se ha declarado en ready.php o
			 * (b) asociando el tiempo en que se está creando el formulario
			 */
			$valorCodificado .= "&campoSeguro=" . $_REQUEST ['tiempo'];
			$valorCodificado .= "&tiempo=" . time ();
			// Paso 2: codificar la cadena resultante
			$valorCodificado = $this->miConfigurador->fabricaConexiones->crypto->codificar ( $valorCodificado );
			
			$atributos ["id"] = "formSaraData"; // No cambiar este nombre
			$atributos ["tipo"] = "hidden";
			$atributos ['estilo'] = '';
			$atributos ["obligatorio"] = false;
			$atributos ['marco'] = true;
			$atributos ["etiqueta"] = "";
			$atributos ["valor"] = $valorCodificado;
			echo $this->miFormulario->campoCuadroTexto ( $atributos );
			unset ( $atributos );
			
			$atributos ['marco'] = true;
			$atributos ['tipoEtiqueta'] = 'fin';
			echo $this->miFormulario->formulario ( $atributos );
			
			unset ( $atributos );
			return true;
		}
	}
}
$miSeleccionador = new registrarForm ( $this->lenguaje, $this->miFormulario, $this->sql );
$miSeleccionador->miForm ();

?>
