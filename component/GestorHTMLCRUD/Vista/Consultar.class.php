<?php 

namespace component\GestoHTMLCRUD\Vista;

include_once ('core/builder/Mensaje.class.php');

include_once 'component/GestorHTMLCRUD/Modelo/Modelo.class.php';
use component\GestorHTMLCRUD\Modelo\Modelo as Modelo;

include_once 'component/GestorHTMLCRUD/Vista/Tabla.class.php';
use component\GestorHTMLCRUD\Vista\Tabla as Tabla;

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}


class Consultar {

    var $miConfigurador;
    
    private $cliente;
    private $objeto;
    private $atributosObjeto;
    private $objetoId;
    private $objetoNombre;
    private $objetoAlias;
    private $mensaje;
    private $tipo;
    private $estado;
    private $permiso;
    private $categoria;
    private $objetoVisble;
    private $objetoCrear;
    private $objetoConsultar;
    private $objetoActualizar;
    private $objetoCambiarEstado;
    private $Objetoduplicar;
    private $objetoEliminar;
    private $columnas;
    private $listaParametros;
    private $listaAtributosParametros;
    private $proceso;
	private $lenguaje;
	private $tabla;
    
    function __construct($lenguaje = '',$objetoId = '') {

    	
        $this->miConfigurador = \Configurador::singleton ();
        $this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );

        $this->mensaje = \Mensaje::singleton();
		$this->cliente  = new Modelo();
		
		
		
		if($lenguaje!='')$this->lenguaje = $lenguaje;
		$this->tabla =  new \component\GestoHTMLCRUD\Vista\Tabla($lenguaje);
        if($objetoId!=''){
        	$this->objetoId = $objetoId;
			$this->objeto = $this->cliente->getListaObjetos();
           $this->columnas = $this->cliente->getListaColumnas();
        
        }else return false;

    }
    
    public function setLenguaje($lenguaje){
    	if(is_object($lenguaje)) $this->lenguaje = $lenguaje;
    }
    
    public function setObjetoId($objetoId){
    	$this->objetoId = $objetoId;
		
		$this->objeto = $this->cliente->getListaObjetos();
           $this->columnas = $this->cliente->getListaColumnas();
    }
	
    
    private function getColumnaAliasPorNombre($nombre = ''){
    	foreach ($this->columnas as $columna){
    		
    		if($columna['nombre'] ==$nombre) return $columna['alias']; 
    		
    	}
    	return "no definido";
    }
    
    private function determinarListaParametros(){
    	
    	$resultado  = array();
    	$this->listaParametros = array();
    	$this->listaAtributosParametro = array();
		
    	foreach ($this->atributosObjeto as $nombreObjeto){
    		foreach ($this->columnas as $datosColumna){
    			if($datosColumna['nombre']==$nombreObjeto&&$datosColumna['consultar']=='t'){
    				
    				if($nombreObjeto == 'usuario'){
    					if(isset($_REQUEST[$nombreObjeto."Definitivo"])) $this->listaParametros[$nombreObjeto] = $_REQUEST[$nombreObjeto."Definitivo"];
    					else $this->listaParametros[$nombreObjeto] = '';
    					
    					$this->listaAtributosParametros[] = $datosColumna;
    					
    					continue;
    				}
    				
    				if(isset($_REQUEST[$nombreObjeto])&&$datosColumna['codificada']!='t') $this->listaParametros[$nombreObjeto] = $_REQUEST[$nombreObjeto];
    				elseif (isset($_REQUEST[$nombreObjeto])&&$datosColumna['codificada']=='t') $this->listaParametros[$nombreObjeto] = $_REQUEST[$nombreObjeto."Codificada"];
    				else $this->listaParametros[$nombreObjeto] = '';
    				$this->listaAtributosParametros[] = $datosColumna;
					if(end($this->listaParametros)=='') unset($this->listaParametros[$nombreObjeto]);
    			}
    		}
    	}
    	
    }
    
    
    
    private function setBool($valor = ''){
    	if($valor=='t') return true;
    	return false;
    }
    
    private function seleccionarObjeto(){
    	
    		

    			$this->objetoNombre = $this->cliente->getObjeto($this->objetoId, 'id','nombre');;
				if(!$this->objetoNombre) return false;
    			$this->objetoAlias = $this->cliente->getObjeto($this->objetoId, 'id','alias'); 	;
    			$this->objetoAliasSingular = $this->cliente->getObjeto($this->objetoId, 'id','ejecutar');;
    			
    			$this->objetoVisble = $this->setBool($this->cliente->getObjeto($this->objetoId, 'id','visible'));
    			
    			$this->objetoConsultar = $this->setBool($this->cliente->getObjeto($this->objetoId, 'id','consultar'));
    			
    			if($this->objetoConsultar==false) {
    				$this->mensaje->addMensaje("4000","errorOperacionNoPermitida: ".ucfirst('consultar'),'information');
    				return false;
    			}
    			
    			return true;
    	
    }
    
    
    
    private function getListaElementos(){
    	
    	//determinar si en la variable request hay algo de aqui
    	//$this->atributosObjeto
    	//
    	$this->determinarListaParametros();
    	
    	$metodo = "consultar".ucfirst($this->objetoAliasSingular);
    	$argumentos =  $this->listaParametros;
		
		$this->cliente->columnasConsulta('_tabla_');
    	   
		
    	   $this->listaElementos =  $this->cliente->$metodo($argumentos);
    	   
    	if(!is_array($this->listaElementos)) {
    		
    		$this->mensaje->addMensaje("4000","errorLectura: ".ucfirst($this->objetoAlias),'information');
    		return false;
    	}
		
		if(count($this->listaElementos)==0) {
			
    		$this->mensaje->addMensaje("4001","errorLectura: ".ucfirst($this->objetoAlias),'information');
    		
    	}
    	
    	return true;
    }
    
    private function getAtributosObjeto($idObjeto = ''){
    	 
    	$metodo = 'getAtributosObjeto';
    	
    	 
    	
    		$this->atributosObjeto =  $this->cliente->$metodo($idObjeto);
    	
    
    	if(!is_array($this->atributosObjeto)) return false;
    	return true;
    }
    
    
    
    private function dateRangeElemento($elemento='elemento', $requerido = false, $codificada =  false){
    	$cadena= '';
    	$cadenaHidden= '';
    	$valor = '';
    	 
    	$textos = array();
    	 
    	$textos[0] = utf8_encode($this->lenguaje->getCadena ($elemento));
    	$textos[1] = utf8_encode($this->lenguaje->getCadena ($elemento."Titulo"));
    	$texto[2] = utf8_encode($this->lenguaje->getCadena ('min'.$elemento));
    	$texto[3] = utf8_encode($this->lenguaje->getCadena ('max'.$elemento));
    	 
    	$cadena .='<div class="form-group" >';
    	
    	$cadena .= '<label for="'.$textos[0].'">';
    	$cadena .= ucfirst(strtolower($textos[0]));
    	$cadena .= '</label>';
    	$cadena .= '<span style="white-space:pre;"> </span>';
    	
    	if($requerido) $requeridoTexto = ' validate[required] form-control';
    	else $requeridoTexto = ' form-control ';
    	 
    	//input  minimo
    	$valorMinimo = '';
    	$valorMaximo = '';
    	$muestraBi = true;
    	$valorMinimo = '';
    	$valorMaximo = '';
    	$muestraBi = array('inline','none');
    	$lista = isset($_REQUEST[$elemento])?  explode( ',', $_REQUEST[$elemento] ): false;;
    	if(isset($_REQUEST[$elemento])&&is_array($lista)){
    		 
    
    		if(count($lista)==2) {
    			$_REQUEST['min'.ucfirst($elemento)] = $lista[0];
    			$_REQUEST['max'.ucfirst($elemento)] = $lista[1];
    			$muestraBi = array('inline','none');
    		}else $muestraBi = $muestraBi = array('none','inline');;
    		 
    	}
    	if(isset($_REQUEST['min'.ucfirst($elemento)])) $valorMinimo .= $_REQUEST['min'.ucfirst($elemento)];
    	if(isset($_REQUEST['max'.ucfirst($elemento)])) $valorMaximo .= $_REQUEST['max'.ucfirst($elemento)];
    	$cadena .= '<input style="float:left;display:'.$muestraBi[0].';" class="'.$requeridoTexto.'" onchange="setRango(\''.$elemento.'\')" id ="min'.ucfirst($elemento).'" placeholder="'.$texto[2].'"  type="text"';
    	$cadena .= 'value ="'.$valorMinimo.'" ></input>';
    
    	//input  maximo
    	$cadena .= '<input style="float:left;display:'.$muestraBi[0].';" class="'.$requeridoTexto.'" onchange="setRango(\''.$elemento.'\')" id ="max'.ucfirst($elemento).'" placeholder="'.$texto[3].'"  type="text"';
    	$cadena .= 'value ="'.$valorMaximo.'" ></input>';
    	 
    	$cadena .= '</div>';
    	 
    	$cadena .= '<div style="position:absolute;display:inline;">';
    	$cadena.= '<input class="'.$requeridoTexto.'" title="'.$textos[1].'" type="text" style="float:left;display:'.$muestraBi[1].';"  id="'.$elemento.'" name="'.$elemento.'" ';
    	if(isset($_REQUEST[$elemento])) $cadena .=' value="'.$_REQUEST[$elemento].'" ';
    	else $cadena .=' value="" ';
    	$cadena .=' >';
    
    	
    	 
    	$cadena .= '</div>';
    
    	 
    
    	 
    	//input hidden
    	 
    	 
    	return $cadena;
    }
    
    
    
    
    private function selectElemento($elemento='elemento', $blanco = false){
    	
    	$cadena= '';
    	$textos = array();
    	$textos[0] = utf8_encode($this->lenguaje->getCadena ($elemento));
    	$textos[1] = utf8_encode($this->lenguaje->getCadena ($elemento."Titulo"));
    	$cadena .= '<div  class="form-group" >';
    	
    	$cadena .= '<label for="'.$textos[0].'">';
    	$cadena .= ucfirst($textos[0]);
    	$cadena .= '</label>';
    	$cadena .= '<span style="white-space:pre;"> </span>';
    	
    	$cadena .= '<select title="'.$textos[1].'" name="'.$elemento.'" id="'.$elemento.'" class="form-control">';
    	if(!$blanco) $cadena .= '<option ></option>';
    	
    	
		$idObjeto = $this->cliente->getColumnas($elemento,'nombre','objetos_id');
		if($idObjeto===false) return false;
		$nombreEjecucionObjeto =  $this->cliente->getObjeto($idObjeto,'id','ejecutar');
		//todos los elementos a usar aqui deben estar registrados como listas
		$metodo =  "getLista".ucfirst($nombreEjecucionObjeto);
		$listaElemento = $this->cliente->$metodo();
		if(!is_array($listaElemento)) return false; 
	    
		
    	foreach ($listaElemento as $fila){
    		$cadena .= '<option ';
    		$cadena .= ' value = "'.$fila['id'].'" ';
    		if(isset($_REQUEST[$elemento]) && $_REQUEST[$elemento]==$fila['id'])$cadena .= '  selected ';
    		$cadena .= '>'.ucfirst(strtolower($fila['alias'])).'</option>';
    	}
    	$cadena .= '</select>';
    	$cadena .= '</div>';
    	return $cadena;
    	 
    }
    
    private function textElemento($elemento='elemento', $requerido = false, $codificada =  false, $autocompletar =  false){
    	$cadena= '';
    	$cadenaHidden= '';
    	$valor = ''; 
    	$textos = array();
    	$textos[0] = utf8_encode($this->lenguaje->getCadena ($elemento));
    	$textos[1] = utf8_encode($this->lenguaje->getCadena ($elemento."Titulo"));
    	$cadena .='<div class="form-group" >';
    	
    	$cadena .= '<label for="'.$textos[0].'">';
    	$cadena .= ucfirst(strtolower($textos[0]));
    	$cadena .= '</label>';
    	$cadena .= '<span style="white-space:pre;"> </span>';
    	
    	if($autocompletar){
    	
    		$cadena .= '<input type="text" class="form-control ';
    		if($requerido) $cadena .= ' validate[required,custom[valorLista]] ';
    	
    		$cadena .='" title="'.$textos[1].'" onkeyup="autocompletar(\''.$elemento.'\')"  name="'.$elemento.'Nombre" id="'.$elemento.'Nombre"  placeholder="'.ucfirst($textos[0]).'" ';
    		 
    		if(isset($_REQUEST[$elemento])&&!$codificada&&$elemento=='proceso') $valor =' value="'.$this->getObjetoNombrePorId($elemento,$_REQUEST[$elemento]).'" ';
    		if(isset($_REQUEST[$elemento])&&!$codificada&&$elemento!='proceso') $valor =' value="'.$_REQUEST[$elemento].'" ';
    		elseif(isset($_REQUEST[$elemento])&&$codificada&&$elemento=='proceso') $valor =' value="'.$this->getObjetoNombrePorId($elemento,base64_decode($_REQUEST[$elemento])).'" ';
    		else $valor .=' value="" ';
    		 
    		$cadena .=$valor;
    		 
    		$cadena .= '></input>';
    	}
    	 
    	if($autocompletar){
    		$cadena .= '<input type="hidden" class=" form-control ';
    	}else $cadena .= '<input type="text" class="form-control ';
    	 
    	
    	 
    	if($requerido) $cadena .= ' validate[required] '; 
    	$cadena .='" title="'.$textos[1].'" name="'.$elemento.'" id="'.$elemento.'"  placeholder="'.ucfirst($textos[0]).'" ';
    	
    	if(isset($_REQUEST[$elemento])&&!$codificada) $valor =' value="'.$_REQUEST[$elemento].'" ';
    	elseif(!isset($_REQUEST[$elemento])&&$codificada) $valor =' onchange="codificarValor(\''.$elemento.'\')" value="" ';
    	elseif(isset($_REQUEST[$elemento])&&$codificada) $valor =' onchange="codificarValor(\''.$elemento.'\')" value="'.base64_decode($_REQUEST[$elemento]).'" ';
    	else $valor .=' value="" ';
    	 
    	$cadena .=$valor;
    	
    	$cadena .= '></input>';
    	$cadena .= '</div>';
    	
    	
    	//input hidden codificado
    	if($codificada) {
    		$cadena.= '<input type="hidden" id="'.$elemento.'Codificado" name="'.$elemento.'Codificado" ';
    		if(isset($_REQUEST[$elemento])) $cadena =' value="'.$_REQUEST[$elemento].'" ';
    		else $cadena .=' value="" ';
    		$cadena .=' >';
    	}
    	return $cadena; 
    }
    
    
    private  function formularioConsulta(){
    	$textos = array();
    	
    	//inicio
    	$cadena = '<form role="form" class="form-inline"  name="formularioConsulta" id="formularioConsulta">';
    	
    	$textos[1] = $this->lenguaje->getCadena ('buscar'). " ".$this->objetoAlias;
    	$cadena .='<fieldset >';
    	
    	$cadena .='<legend title="'.$this->lenguaje->getCadena ('buscar').'" onclick="cambiarVisibilidadBusqueda()" class="expandible">'.$textos[1].'</legend>';
    	$cadena .='<div style="display:none" id="contenedorBuscador">';
    	
    	$nombre = 'nombre';
    	$requerido = 'requerido_consultar';
    	$codificado = 'codificada';
    	$autocompletar = 'autocompletar_consultar';
    	
    	
    	
    	//crea formularios
    	foreach ($this->listaAtributosParametros as $elemento){
    		switch($elemento['input']){
    			case 'text':
    				$cadena .= $this->textElemento($elemento[$nombre],$this->setBool($elemento[$requerido]),$this->setBool($elemento[$codificado]),$this->setBool($elemento[$autocompletar])); 
    				break;
    			case 'select':
    				$cadena .= $this->selectElemento($elemento[$nombre],$this->setBool($elemento[$requerido]));
    				break;
    			case 'date':
    				$cadena .= $this->dateRangeElemento($elemento[$nombre],$this->setBool($elemento[$requerido]));
    				break;
    			case 'textarea':
    				break;
    			default:
    				break;
    		}
    	}
    	
    	$cadena .= '</fieldset>';
    	
    	//Botones
    	$textos[0] = $this->lenguaje->getCadena ('buscar');
    	$textos[1] = $this->lenguaje->getCadena ('reiniciar');
    	$textos[2] = $this->lenguaje->getCadena ('limpiar');
    	$cadena .= '<div id="botones" style="display:none"  class="marcoBotones">';
    	$cadena .= '<div class="campoBoton">';
    	$cadena .= '<button  onclick="getFormularioConsulta(false)" type="button" tabindex="2" id="botonConsultar" value="'.$textos[0].'" class="ui-button-text ui-state-default ui-corner-all ui-button-text-only">'.ucfirst($textos[0]).'</button>';
    	$cadena .= '</div>';
    	$cadena .= '<div class="campoBoton">';
    	$cadena .= '<button type="reset" onclick="formularioReset(\'formularioConsulta\')" tabindex="2" id="botonReiniciar" value="'.$textos[1].'" class="ui-button-text ui-state-default ui-corner-all ui-button-text-only">'.ucfirst($textos[1]).'</button>';
    	$cadena .= '</div>';
    	$cadena .= '<div class="campoBoton">';
    	$cadena .= '<button type="button" onclick="formularioClean(\'formularioConsulta\')" tabindex="2" id="botonLimpiar" value="'.$textos[1].'" class="ui-button-text ui-state-default ui-corner-all ui-button-text-only">'.ucfirst($textos[2]).'</button>';
    	$cadena .= '</div>';
    	$cadena .= '</div>';

    	//fin contenedor busqueda
    	$cadena .='<div>';
    	
    	//fin
    	$cadena .= '</form>';
    	$cadena .='<br>';
    	$cadena .='<br>';
    	

        return $cadena;
    }

    function formulario() {
    	
    	//1. Captura  variables de la operacion del $_REQUEST
    	
    	
    	$verifica =  true;
    	//2. Seleccionar objeto, popular datos
    	
    	if(!$this->seleccionarObjeto()||!$this->getAtributosObjeto($this->objetoId)||!$this->getListaElementos()){    	
    		
    		$verifica =  false;
    			
    	}
    	
    	
       if($this->objetoConsultar==false) {
    		$this->mensaje->addMensaje("4000","errorOperacionNoPermitida: ".ucfirst('consultar'),'information');
    		echo $this->mensaje->getLastMensaje();
    		return false;
    	}
    	
		//muestra el formulario
    	echo '<div class="container-fluid" id="contenedorFormularioConsulta">';
    	echo $this->formularioConsulta();
    	echo '</div>';	
    	
    	
    	
    	//Muestra la tbla
    	echo '<div id="resultado">'; 

    	
    	
    	if(!$verifica||!$this->objetoConsultar){
    		echo $this->mensaje->getLastMensaje();
    		return false;
    	}
    	
    	echo $this->tabla->dibujarTabla($this->listaElementos);
    	echo '</div>';
    	
    	
    	
    	
    	
    }

    function mensaje() {

        // Si existe algun tipo de error en el login aparece el siguiente mensaje
        $mensaje = $this->miConfigurador->getVariableConfiguracion ( 'mostrarMensaje' );
        $this->miConfigurador->setVariableConfiguracion ( 'mostrarMensaje', null );

        if ($mensaje) {

            $tipoMensaje = $this->miConfigurador->getVariableConfiguracion ( 'tipoMensaje' );

            if ($tipoMensaje == 'json') {

                $atributos ['mensaje'] = $mensaje;
                $atributos ['json'] = true;
            } else {
                $atributos ['mensaje'] = $this->lenguaje->getCadena ( $mensaje );
            }
            // -------------Control texto-----------------------
            $esteCampo = 'divMensaje';
            $atributos ['id'] = $esteCampo;
            $atributos ["tamanno"] = '';
            $atributos ["estilo"] = 'information';
            $atributos ["etiqueta"] = '';
            $atributos ["columnas"] = ''; // El control ocupa 47% del tamaño del formulario
            echo $this->miFormulario->campoMensaje ( $atributos );
            unset ( $atributos );

             
        }

        return true;

    }

}

//$miFormulario = new Consultar ( $this->lenguaje,$objetoId );


//$miFormulario->formulario ();
//$miFormulario->mensaje ();

?>