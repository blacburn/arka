<?php 

namespace component\GestoHTMLCRUD\Clase;


include_once ('core/builder/Mensaje.class.php');

include_once 'component/GestorHTMLCRUD/Modelo/Modelo.class.php';
use component\GestorHTMLCRUD\Modelo\Modelo as Modelo;

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}


class CambiarEstado {

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
	private $listaElementos;
	private $listaPks;
    
    function __construct($lenguaje = '') {

    	
        $this->miConfigurador = \Configurador::singleton ();
        $this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );

        $this->mensaje = \Mensaje::singleton();
		$this->cliente  = new Modelo();
		
		if($lenguaje!='')$this->lenguaje = $lenguaje;
        
        	
			$this->objeto = $this->cliente->getListaObjetos();
           $this->columnas = $this->cliente->getListaColumnas();
        
        

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
    
    private function seleccionarObjeto(){
    	 
    
    
    	$this->objetoNombre = $this->cliente->getObjeto($this->objetoId, 'id','nombre');;
    	if(!$this->objetoNombre) return false;
    	$this->objetoAlias = $this->cliente->getObjeto($this->objetoId, 'id','alias'); 	;
    	$this->objetoAliasSingular = $this->cliente->getObjeto($this->objetoId, 'id','ejecutar');;
    	 
    	$this->objetoVisble = $this->setBool($this->cliente->getObjeto($this->objetoId, 'id','visible'));
    	
    	$estado = $this->setBool($this->cliente->getObjeto($this->objetoId, 'id','cambiarestado'));
    	 
    	if($estado==false) {
    		$this->mensaje->addMensaje("4000","errorOperacionNoPermitida: ".ucfirst('Cambiar Estado'),'information');
    		return false;
    	}
    	 
    	
    	 
    	return true;
    	 
    }
    
    private function determinarListaParametros(){
    	$nombreObjeto = 'selectedItems';
    	$this->listaParametros = array();
    	 
    	$this->listaAtributosParametro = array();
    	if(isset($_REQUEST[$nombreObjeto])) $this->listaParametros = explode( ',', $_REQUEST[$nombreObjeto] );
    
    }
    

    
    
	
    private function setBool($valor = ''){
    	if($valor=='t') return true;
    	return false;
    }
    
    
    
    
    private function setTextoTabla($valor = '', $nombre =''){
    	
    	$nombreSelect = '';
    	$aliasSelect = '';
    	foreach ($this->columnas as $columna){
    		
    		
    		
    		if($columna['nombre']==$nombre&&$columna['codificada']=='t'){
    			
    			return base64_decode($valor);
    		}
    		
    		if($columna['nombre']==$nombre&&$columna['input']=='select'){
    			$objeto = $columna['nombre'];
    			$id = $valor;
    			return $this->getObjetoAliasPorId($objeto, $id);
    		}
    		
    		
    	}
    	
    	return $valor;
    	
    	
    }
    
	private function getObjetoAliasPorId($objeto= '', $id = ''){
    	
		$idObjeto = $this->cliente->getColumnas($objeto, 'nombre','objetos_id');
		
		$nombreEjecucion = $this->cliente->getObjeto($idObjeto, 'id','ejecutar');
		
		$metodo = "get".ucfirst($nombreEjecucion);
		 
		return $this->cliente->$metodo($id, 'id','alias');
		
    }
    

    private function columnaVer($nombre){
    	 
    	$idColumna = $this->cliente->getColumnas($nombre, 'nombre','id');
    
    	return  $this->setBool($this->cliente->getColumnas($idColumna, 'id','ver'));
    
    	
    
    }
	
    public function cambiarEstado(){
    	
    	
    	
    	if(!$this->seleccionarObjeto()){
    	
    		$verifica =  false;
    		 
    	}
    	
    	$this->determinarListaParametros();
    	
    	$metodo = "activarInactivar".ucfirst($this->objetoAliasSingular);

    	
    	
    	$this->listaPks =  $this->cliente->listaLlavesPrimarias($this->objetoId);
    	$resultados =  array();
    	$cadenaMensaje = '';
    	
    	
    	foreach ($this->listaParametros as $parametro){
    	
    		$argumentos =  array($this->listaPks[0]=>$parametro);
    		 
    		$accion =  $this->cliente->$metodo($argumentos);
    	
    	
    		
    		$resultados []= array($parametro, $accion);
    		$cadenaMensaje .= 'id Elemento '.$parametro;
    		if(!$accion) $cadenaMensaje .= ' <span style="color=red;">FALLO</span>';
    		else $cadenaMensaje .= ' <span style="color=green;">EXITO</span>';
    		$cadenaMensaje .= '<br>';
    		
    		
    	
    	}
    	
    	$this->mensaje->addMensaje('2001',":".$cadenaMensaje,'information');
    	echo $this->mensaje->getLastMensaje();
    	return true;
    	
    	    	 
    }
    
}

?>