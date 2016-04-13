<?php 
namespace component\GestorHTMLCRUD\Vista;


include_once 'component/GestorHTMLCRUD/Modelo/Modelo.class.php';
use component\GestorHTMLCRUD\Modelo\Modelo as Modelo;

include_once 'component/GestorHTMLCRUD/Vista/Script.class.php';
use component\GestorHTMLCRUD\Vista\Script as Script;

include_once ('core/builder/Mensaje.class.php');



if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}


class Principal {

    var $miConfigurador;
    var $miFormulario;
    private $cliente;
    private $objetos;
	private $lenguaje;
	private $operaciones =  null;
	private $listaIdsObjetos;
	private $mensaje;
	private $funcionInicio;
	private $script;
	

    private $bloqueNombre;
	private $bloqueGrupo;
	
	private $queryStringConsulta =  "funcion=consultarForm";
	private $queryStringAutocompletar = "funcion=autocompletar";

    function __construct($lenguaje = '') {

        $this->miConfigurador = \Configurador::singleton ();

        $this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );
        
		$this->mensaje =  \Mensaje::singleton();
        $this->lenguaje = $lenguaje;

          $this->cliente  = new Modelo();

    }
		
	public function setBloque($nombre , $grupo = ''){
	    $this->bloqueNombre =  $nombre;
	    $this->bloqueGrupo =  $grupo;
			
			
	}
	
	public function setQueryStringConsulta($queryString = "funcion=consultarForm"){
		$this->queryStringConsulta = $queryString;
	}
	
	public function setQueryStringAutocompletar($queryString = "funcion=autocompletar"){
		$this->queryStringAutocompletar = $queryString;
	}
	
	public function setOperaciones($operaciones){
		$this->operaciones =  $operaciones;
	}
	
	public function setObjetos($listaIdsObjetos){
		$this->listaIdsObjetos = explode(",",$listaIdsObjetos);
		$this->objetos = $this->cliente->objetosVisibles($listaIdsObjetos);
		
	}
	
	private function filtrarObjetos(){
		$final =  array();
		if(is_array($this->objetos)){
		   
		   foreach ($this->objetos as &$elemento) {
			if(in_array($elemento['id'], $this->listaIdsObjetos)) $final[] = $elemento;
		    }
		
		$this->objetos =  $final;
		   	
		}
		
		
		
		
	}
	
	public function setFuncionInicio($funcionInicio)	{
		$this->funcionInicio = $funcionInicio;
	}
	
	public function script(){
		$this->script =  new Script();	
		$this->script->setOperaciones($this->operaciones);
		$this->script->setFuncionInicio($this->funcionInicio);
		$this->script->setBloque($this->bloqueNombre , $this->bloqueGrupo );
		
		$this->script->rangoFecha();
		$this->script->cambiarListaObjeto();
		$this->script->tablaConsulta();
		$this->script->ayudasFormulario();
		$this->script->extensiones();
		
		
	    foreach ($this->operaciones as $operacion){
	    	
			switch ($operacion['nombre']){
				case 'consultar':
					$this->script->formularioConsulta($operacion['query_string']);
					break;
				case 'crear':
					$listaQueries = explode(",",$operacion['query_string']) ;
					$this->script->crear($listaQueries[0]);
					$this->script->guardarDatos($listaQueries[1]);
					break;
				case 'editar':
					$listaQueries = explode(",",$operacion['query_string']) ;
					$this->script->editar($listaQueries[0]);
					$this->script->guardarDatos($listaQueries[1]);
					break;
				case 'ver':
					$this->script->ver($operacion['query_string']);
					break;
				case 'autocompletar':
					$this->script->autocompletar($operacion['query_string']);
					break;
			    case 'duplicar':
			    	$this->script->duplicar($operacion['query_string']);
					break;
			    case 'eliminar':
			    	$this->script->eliminar($operacion['query_string']);
			    	break;
				case 'activarInactivar':
					$this->script->cambiarEstado($operacion['query_string']);
					break;
				case 'evaluar':
					
					break;
				default:
					break;
			}
			
			
		}
		
		
		
		$this->script->ready();
		
		
		unset($this->script);
	}
	
    function formulario() {
    		if(!is_array($this->operaciones)
		   ||count($this->operaciones)===0
		   ||is_null($this->operaciones)) ;//return false;
		   
		   if(!is_array($this->objetos)
		   ||count($this->objetos)===0
		   ||is_null($this->objetos)) ;//return false;
		
		$this->script();
    	//$this->ready();
		
    	/*if(!isset($_REQUEST['username'])) {
    		$this->formularioUsuario();
    		return 0;
    	}*/
    	//Esqueleto interfaz
    	echo '<br><br><br><div id ="cabeza"  >';
    	
    	//barra de herramientas
    	echo  '<div id ="herramientas" class="ui-widget-header ui-corner-all" >';
    			
    	
		
		/**
		 * operaciones array
		 * nombre, cadena, text , icono, click
		 */
		if(is_array($this->operaciones)){
			
			foreach ($this->operaciones as $operacion) {
				if($operacion['cadena']==''&&$operacion['icono']=='') continue;
				
				echo '<button id="'.$operacion["nombre"].'">'.utf8_encode($this->lenguaje->getCadena($operacion['cadena'])).'</button>';	
			}
			
		}
		
    	echo "</div>";
    	
		
    	
    	//barra de menu para acceder a reglas, funciones, variables, parametros
    	if(is_array($this->objetos)&&count($this->objetos)>1){
    	    
			echo  '<div id ="menu" class="ui-widget-header ui-corner-all">';
		     echo '<div class="posiscion">';
		      echo '<div>';
		      echo '<button  id="objetoSeleccionado">'.$this->objetos[0]['alias'].'</button>';
		      echo '<button  id="seleccionar">'.utf8_encode($this->lenguaje->getCadena('principalSeleccionarAccion')).'</button>';
			  echo '</div>';
			  echo '<ul id="menuLista">';
			  //Itera array objetos y los muestra
			  $conteoVisibles = 0;
			  $cadenaTemp = '';
			  foreach ($this->objetos as $objeto){
			  	if($objeto['visible']=='t'){
			  		$conteoVisibles++;
					$cadenaTemp.= '<li onclick="setObjeto('.$objeto['id'].',\''.$objeto['alias'].'\')">'.$objeto['alias'].'</li>';
			  	}
			  	
			  }
			  //if($conteoVisibles>1) 
			  echo $cadenaTemp;
			  echo '</ul>';
			  echo '</div>';
		 echo "</div>";
			
    	}
		
		//formulario de objeto
		echo '<form id="objetosFormulario">';
		echo '<input type="hidden" id ="objetoId" name="objetoId" value ="'.$this->objetos[0]['id'].'">';
		echo '</form>';
		
		//formulario de usuario
		if(!isset($_REQUEST['username'])) $username = '-1';
        else $username = $_REQUEST['username']; 
		echo '<form id="identificacionFormulario">';
		echo '<input type="hidden" id ="username" name="username" value ="'.$username.'">';
		echo '</form>';
		
		//formulario de seleccion
		echo '<form id="seleccionFormulario">';		
		echo '<input type="hidden" id ="selectedItems" name="selectedItems" value ="">';
		echo '</form>';
		
		
		//fin encabezado
		echo "</div>";
        echo '<br><br><hr>';
		
		//cuerpo
    	echo '<div class="container-fluid" id="cuerpo">';
    	
    	
    	//define espacios para interacciones
    	
    	//mensaje
    	echo '<div id="espacioMensaje">';
    	echo '</div>';
    	
    	//espacio Trabajo
    	echo '<div  id="espacioTrabajo">';
    	
    	
    	
    	//fin espacio Trabajo
    	echo '</div>';
    	
    	//fun cuerpo
        echo "</div>";
    	
        
    	echo '<div id ="pies">';
    	
    	echo "</div>";
    	
      

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

//$miFormulario = new Formulario ( $this->lenguaje );


//$miFormulario->formulario ();
//$miFormulario->mensaje ();

?>