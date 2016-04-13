<?php 

namespace component\GestoHTMLCRUD\Clase;


include_once ('core/builder/Mensaje.class.php');

include_once ("core/manager/Configurador.class.php");

include_once 'component/GestorHTMLCRUD/Modelo/Modelo.class.php';
use component\GestorHTMLCRUD\Modelo\Modelo as Modelo;

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}


class GuardarDatos {

    var $miConfigurador;
    private $metodoAccion;
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
    private $tablasPaso;
    private $listaObjetosPaso;
    private $listaElementosPaso;
    
	    
    function __construct($lenguaje,$objetoId = '') {

    	$this->objetoId = $objetoId;
        $this->miConfigurador = \Configurador::singleton ();

        $this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );
        
        if(isset($_REQUEST['usuario'])) $_REQUEST['usuarioDefinitivo'] = $_REQUEST['usuario'];
        
        $this->tablasPaso =  array();

        $this->lenguaje = $lenguaje;
        $this->mensaje =  \Mensaje::singleton();
        $this->cliente  = new Modelo();
        $this->objeto = $this->cliente->getListaObjetos();
        $this->columnas = $this->cliente->getDatosColumnas();
    }
    
    public function addTablasPaso($valor){
    	if(is_array($valor)) $this->tablasPaso[] = $valor;
    }

    public function setLenguaje($lenguaje){
    	if(is_object($lenguaje)) $this->lenguaje = $lenguaje;
    }
    
    public function setObjetoId($objetoId){
    	$this->objetoId = $objetoId;
    
    	$this->objeto = $this->cliente->getListaObjetos();
    	$this->columnas = $this->cliente->getListaColumnas();
    }
    
    
    private function seleccionarObjeto(){
    	foreach ($this->objeto as $objeto){
    		if($objeto['id']==$this->objetoId){
    
    			$this->objetoNombre = $objeto['nombre'];
    			$this->objetoAlias = $objeto['alias'] 	;
    			$this->objetoAliasSingular = $objeto['ejecutar'];
    			 
    			$this->objetoVisble = $this->setBool($objeto['visible']);
    			$this->objetoCrear = $this->setBool($objeto['crear']);
    			$this->objetoConsultar = $this->setBool($objeto['consultar']);
    			$this->objetoActualizar = $this->setBool($objeto['actualizar']);
    			$this->objetoCambiarEstado = $this->setBool($objeto['cambiarestado']);
    			$this->objetoDuplicar = $this->setBool($objeto['duplicar']);
    			$this->objetoEliminar = $this->setBool($objeto['eliminar']);
    			 
    			return true;
    		}
    	}
    	return false;
    }
    
    private function determinarListaParametros(){
    	
    	$nombreObjeto = 'selectedItems';
    	$lista =  array();
    	$this->listaParametros = array();
    	$this->listaAtributosParametro = array();
    	if(isset($_REQUEST[$nombreObjeto])) $lista = explode( ',', $_REQUEST[$nombreObjeto] );
    	
    	if(isset($lista[0])&&$lista[0]!=''){
    		$this->metodoAccion = 'actualizar';
    		$_REQUEST['id']= $lista[0];
    		$this->listaParametros[] = $_REQUEST['id'];
    	}else{
    		$this->metodoAccion = 'crear';
    	}
    	
    	
    	$resultado  = array();
    	
    	foreach ($this->atributosObjeto as $nombreObjeto){
    		foreach ($this->columnas as $datosColumna){
    			if($datosColumna['nombre']==$nombreObjeto&&$datosColumna[$this->metodoAccion]=='t'){
    				
    				
    				
    				if(isset($_REQUEST[$nombreObjeto])&&$datosColumna['codificada']!='t') $this->listaParametros[$nombreObjeto] = $_REQUEST[$nombreObjeto];
    				elseif (isset($_REQUEST[$nombreObjeto])&&$datosColumna['codificada']=='t') $this->listaParametros[$nombreObjeto] = $_REQUEST[$nombreObjeto."Codificado"];
    				else $this->listaParametros[$nombreObjeto] = '';
    				
    				if(isset($_REQUEST[$nombreObjeto])&&$datosColumna['input']=='password')
    					$this->listaParametros[$nombreObjeto] = $this->miConfigurador->fabricaConexiones->crypto->codificarClave ($_REQUEST[$nombreObjeto]);
    				
    				if(isset($_REQUEST[$nombreObjeto])&&$datosColumna['input']=='checkbox')
    					$this->listaParametros[$nombreObjeto] = $this->setCheckboxValor ($_REQUEST[$nombreObjeto]);
    				
    				
    				$this->listaAtributosParametros[] = $datosColumna;
    				
    			}
    		}
    	}
    	
    	if($this->metodoAccion == 'actualizar'){
    	
    		$this->listaParametros[] = isset($_REQUEST['justificacion'])?$_REQUEST['justificacion']:'no justifica';
    	}
    	
    	 
    }
    
    private function getAtributosObjeto($idObjeto = ''){
    
    	$metodo = 'getAtributosObjeto';
    	$argumentos =  array($idObjeto);
    
    	try {
    		$this->atributosObjeto =  call_user_func_array(array($this->cliente , $metodo), $argumentos);
    	}catch (\SoapFault $fault) {
    		$this->mensaje->addMensaje($fault->faultcode,":".$fault->faultstring,'information');
    		return false;
    	}
    
    	if(!is_array($this->atributosObjeto)) return false;
    	return true;
    }
    
    private function setBool($valor = ''){
    	if($valor=='t') return true;
    	return false;
    }
    
    private function setCheckboxValor($valor = ''){
    	if($valor=='on') return true;
    	return false;
    }
    
    public function guardarDatos(){
    	
    	
    	
    	if(!$this->seleccionarObjeto()||!$this->getAtributosObjeto($this->objetoId)){
    		echo $this->mensaje->getLastMensaje();
    		return false;
    		
    		
    	}
    	
    	$this->determinarListaParametros();
	    $resultados  =  array();
	    
	    
	    
	    
	    
	    $metodo = $this->metodoAccion.ucfirst($this->objetoAliasSingular);
	    	$argumentos =  $this->listaParametros;
	    	
	    	$accion =  $this->cliente->$metodo($argumentos);
	    	
	    	
	    	
	    	$cadenaMensaje = '';
	    	
	    	if($accion===false) $cadenaMensaje = $this->lenguaje->getCadena ( $this->metodoAccion."AccionFallo" ).$accion;
	    	else {
	    		$cadenaMensaje = $this->lenguaje->getCadena ( $this->metodoAccion."Accion" );
	    		
	    		if($this->metodoAccion=='crear') ;
	    		else $cadenaMensaje .= $this->listaParametros[0];
	    	}
	    	$cadenaMensaje .= '<br>';
    	
    	
    	
	    
    	$this->mensaje->addMensaje('2001',":".$cadenaMensaje,'information');
    	echo $this->mensaje->getLastMensaje();
    	return true;
    }
    
    
}

?>