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
		$atributosGlobales ['campoSeguro'] = 'true';
		$_REQUEST ['tiempo'] = time ();
		$tiempo = $_REQUEST ['tiempo'];
		$seccion ['tiempo'] = $tiempo;
		
		$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "host" );
		$rutaBloque .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/";
		$rutaBloque .= $esteBloque ['grupo'] . "/" . $esteBloque ['nombre'];
		
		$conexion = "inventarios";
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		$datos = array (
				$_REQUEST ['vigencia'],
				$_REQUEST ['numero_disponibilidad'],
				$_REQUEST ['unidad_ejecutora'] 
		);
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'ConsultarRegistrosPresupuestales', $datos );
		
		$registro_presupuestales_exitentes = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		// // $cadenaSql = $this->miSql->getCadenaSql ( 'clase_entrada_descrip', $entrada [0] [2] );
		// // $Clase = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		// // $cadenaSql = $this->miSql->getCadenaSql ( 'consulta_proveedor', $entrada [0] [7] );
		// // $proveedor = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		// $cadenaSql = $this->miSql->getCadenaSql ( 'consulta_elementos', $_REQUEST ['numero_entrada'] );
		// $elementos = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
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
			$miPaginaActual = $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
			
			$directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
			$directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
			$directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );
			
			$variable = "pagina=" . $miPaginaActual;
			$variable .= "&usuario=" . $_REQUEST ['usuario'];
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
			
			unset ( $atributos );
			
			if (isset ( $_REQUEST ['mensaje'] )) {
				switch ($_REQUEST ['mensaje']) {
					case 'registroPresupuestal' :
						$atributos ['mensaje'] = "<center>SE RELACIONO CERTIFICADO DE REGISTRO PRESUPUESTAL</center>";
						$atributos ["estilo"] = 'success';
						
						break;
					
					case 'noinsertoRegistro' :
						$atributos ['mensaje'] = "<center>ERROR AL RELACIONAR CERTIFICADO DE REGISTRO PRESUPUESTAL<BR>Verifique los Datos</center>";
						$atributos ["estilo"] = 'error';
						break;
					
					case 'ErrorValorAsignar' :
						$atributos ['mensaje'] = "<center>EL VALOR TOTAL SOLICITADO ES MAYOR AL VALOR DE LA ORDEN<br>Verifique los Datos</center>";
						$atributos ["estilo"] = 'error';
						break;
					
					case 'NoRelacionRegistro' :
						$atributos ['mensaje'] = "<center>SE ELIMINO RELACIÓN CON CERTIFICADO DE REGISTRO PRESUPUESTAL</center>";
						$atributos ["estilo"] = 'success';
						break;
					
					case 'modifico' :
						$atributos ['mensaje'] = "<center>SE MODIFICO CON EXITO LA DISPONIBILIDAD PRESUPUESTAL</center>";
						$atributos ["estilo"] = 'success';
						break;
					
					case 'ModificoDisponibilidadCompleta' :
						$atributos ['mensaje'] = "<center>SE MODIFICO LA DISPONIBILIDAD PRESUPUESTAL <BR> Y SE REGISTRO EL TOTAL DEL VALOR DE LA ORDEN<BR>CON LAS DISPONIBILIDADES PRESUPUESTALES</center>";
						$atributos ["estilo"] = 'success';
						break;
					
					case 'NoModifico' :
						$atributos ['mensaje'] = "<center>ERROR AL MODIFICAR DISPONIBILIDAD PRESUPUESTAL<BR>Verifique los Datos</center>";
						$atributos ["estilo"] = 'error';
						break;
					
					case 'ErrorValorAsignarModificar' :
						$atributos ['mensaje'] = "<center>ERROR AL MODIFICAR DISPONIBILIDAD PRESUPUESTAL<BR>EL VALOR TOTAL SOLICITADO ES MAYOR AL VALOR DE LA ORDEN<br>Verifique los Datos</center>";
						$atributos ["estilo"] = 'error';
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
			
			// ---------------- SECCION: Controles del Formulario -----------------------------------------------
			
			$esteCampo = "marcoDatosBasicos";
			$atributos ['id'] = $esteCampo;
			$atributos ["estilo"] = "jqueryui";
			$atributos ['tipoEtiqueta'] = 'inicio';
			$atributos ["leyenda"] = "Asociar Certificado de Registro Presupuestal";
			echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			
			if ($registro_presupuestales_exitentes) {
				
				echo "<table id='tablaRegistros'>";
				
				echo "<thead>
				         <tr>
                              <th>Vigencia</th>
                              <th>Número Registro<br>Presupuestal</th>
                              <th>Fecha Registro<br>Presupuestal</th>			
                              <th>Unidad Ejecutora</th>
                              <th>Valor($)</th>
						      <th>Asociar Registro<br>Presupuestal</th>
							  <th>Desasociar Registro<br>Presupuestal</th>
					     </tr>
                      </thead>
					  <tbody>";
				
				foreach ( $registro_presupuestales_exitentes as $valor ) {
					
					$informacion = array (
							"vigencia" => $valor ['REP_VIGENCIA'],
							"numero_registro" => $valor ['REP_IDENTIFICADOR'],
							"fecha_registro" => $valor ['REP_FECHA_REGISTRO'],
							"unidad_ejecutora" => $valor ['REP_UNIDAD_EJECUTORA'],
							"valor_registro" => $valor ['REP_VALOR'],
							"numero_disponibilidad" => $_REQUEST ['numero_disponibilidad'] 
					);
					
					$informacion = serialize ( $informacion );
					
					$variable = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
					$variable .= "&action=" . $esteBloque ["nombre"];
					$variable .= "&bloque=" . $esteBloque ['nombre'];
					$variable .= "&bloqueGrupo=" . $esteBloque ["grupo"];
					$variable .= "&opcion=asignarRegistro";
					$variable .= "&id_disponibilidad=" . $_REQUEST ['id_disponibilidad'];
					$variable .= "&usuario=" . $_REQUEST ['usuario'];
					$variable .= "&informacion=" . $informacion;
					$variableAsignar = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
					// echo $variableAsignar;
					
					$variable = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
					$variable .= "&action=" . $esteBloque ["nombre"];
					$variable .= "&bloque=" . $esteBloque ['nombre'];
					$variable .= "&bloqueGrupo=" . $esteBloque ["grupo"];
					$variable .= "&opcion=DesasignarRegistro";
					$variable .= "&id_disponibilidad=" . $_REQUEST ['id_disponibilidad'];
					$variable .= "&usuario=" . $_REQUEST ['usuario'];
					$variable .= "&informacion=" . $informacion;
					$variableDesAsignar = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
					// echo $variableDesAsignar;
					
					$mostrarHtml = "<tr>
						               <td><center> " . $valor ['REP_VIGENCIA'] . "</center></td>
						               <td><center> " . $valor ['REP_IDENTIFICADOR'] . "</center></td>
									   <td><center> " . $valor ['REP_FECHA_REGISTRO'] . "</center></td>
									   <td><center> " . $valor ['REP_UNIDAD_EJECUTORA'] . "</center></td>
									   <td><center>$ " . number_format ( $valor ['REP_VALOR'], 2, ",", "." ) . "</center></td>";
					$mostrarHtml .= (is_null ( $valor ['id_registro_presupuestal_orden'] ) == true) ? "<td><center>
							            <a href='" . $variableAsignar . "'>
							            <img src='" . $rutaBloque . "/css/images/validar.png' width='12px'>
							            </a>
						            	</center> </td>" : "<td> </td>";
					$mostrarHtml .= (is_null ( $valor ['id_registro_presupuestal_orden'] ) == false) ? "<td><center>
							            <a href='" . $variableDesAsignar . "'>
							            <img src='" . $rutaBloque . "/css/images/eliminar.png' width='12px'>
							            </a>
							            </center> </td>" : "<td> </td>";
					$mostrarHtml .= "</tr>";
					echo $mostrarHtml;
					unset ( $mostrarHtml );
					unset ( $variable );
					// }
				}
				
				echo "</tbody>";
				
				echo "</table>";
				
				echo $this->miFormulario->agrupacion ( 'fin' );
				
				echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				
				// ---------------- FIN SECCION: Controles del Formulario -------------------------------------------
				// ----------------FINALIZAR EL FORMULARIO ----------------------------------------------------------
				// Se debe declarar el mismo atributo de marco con que se inició el formulario.
			} else {
				
				
				
				// -------------Control texto-----------------------
				$esteCampo = 'divMensajeNoRegistros';
				$atributos ['id'] = $esteCampo;
				$atributos ["tamanno"] = '';
				$atributos ["etiqueta"] = '';
				$atributos ["columnas"] = ''; // El control ocupa 47% del tamaño del formulario
				$atributos ['mensaje'] = "<center>NO EXISTEN REGISTROS PRESUPUESTALES ASOCIADOS A<BR> LA DISPONIBILIDAD PRESUPUESTAL </center>";
				$atributos ["estilo"] = 'error';
				echo $this->miFormulario->campoMensaje ( $atributos );
				unset ( $atributos );
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
			
			/**
			 * SARA permite que los nombres de los campos sean dinámicos.
			 * Para ello utiliza la hora en que es creado el formulario para
			 * codificar el nombre de cada campo. Si se utiliza esta técnica es necesario pasar dicho tiempo como una variable:
			 * (a) invocando a la variable $_REQUEST ['tiempo'] que se ha declarado en ready.php o
			 * (b) asociando el tiempo en que se está creando el formulario
			 */
			$valorCodificado = "actionBloque=" . $esteBloque ["nombre"];
			$valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
			$valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
			$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
			$valorCodificado .= "&opcion=Registrar";
			// $valorCodificado .= "&usuario=" . $_REQUEST ['usuario'];
			// $valorCodificado .= "&numero_entrada=" . $_REQUEST ['numero_entrada'];
			// $valorCodificado .= "&datosGenerales=" . $_REQUEST ['datosGenerales'];
			// $valorCodificado .= "&cantidadItems=" . $cantidaditems;
			// if (isset ( $arreglo_nombreItems )) {
			// $valorCodificado .= "&nombreItems=" . serialize ( $arreglo_nombreItems );
			// }
			
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
			
			return true;
		}
	}
}

$miSeleccionador = new registrarForm ( $this->lenguaje, $this->miFormulario, $this->sql );

$miSeleccionador->miForm ();
?>

