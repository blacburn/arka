<?php 

namespace component\GestoHTMLCRUD\Vista;


include_once ('core/builder/Mensaje.class.php');

include_once 'component/GestorHTMLCRUD/Modelo/Modelo.class.php';
use component\GestorHTMLCRUD\Modelo\Modelo as Modelo;

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}


class Ver {

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
	private $tablasPaso;
	private $listaObjetosPaso;
	private $listaElementosPaso;
    
    function __construct($lenguaje = '') {

    	
        $this->miConfigurador = \Configurador::singleton ();
        $this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );

        $this->mensaje = \Mensaje::singleton();
		$this->cliente  = new Modelo();
		
		if($lenguaje!='')$this->lenguaje = $lenguaje;
        
		$this->tablasPaso = array();
        	
			$this->objeto = $this->cliente->getListaObjetos();
           $this->columnas = $this->cliente->getListaColumnas();
        
        

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
    	
    	$this->objetoConsultar = $this->setBool($this->cliente->getObjeto($this->objetoId, 'id','consultar'));
    	 
    	if($this->objetoConsultar==false) {
    		$this->mensaje->addMensaje("4000","errorOperacionNoPermitida: ".ucfirst('consultar'),'information');
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
    
    private function getListaElementos(){
    	 
    	//determinar si en la variable request hay algo de aqui
    	//$this->atributosObjeto
    	//
    	$this->determinarListaParametros();
    	 
    	$metodo = "consultar".ucfirst($this->objetoAliasSingular);
    	
    
    	$this->listaPks =  $this->cliente->listaLlavesPrimarias($this->objetoId);
    	$this->listaElementos =  array();
    	foreach ($this->listaParametros as $parametro){

    		$argumentos =  array($this->listaPks[0]=>$parametro);
    		 
    		$lista =  $this->cliente->$metodo($argumentos);
    		
    		if(!is_array($lista)) {
    		
    			$this->mensaje->addMensaje("4000","errorLectura: ".ucfirst($this->objetoAlias),'information');
    			continue;
    		}
    		
    		if(count($lista)==0) {
    			 
    			$this->mensaje->addMensaje("4001","errorLectura: ".ucfirst($this->objetoAlias),'information');
    		
    		}
    		
    		if(count($this->listaObjetosPaso>0)){
    			$this->listaElementosPaso =  array();
    			foreach ($this->listaObjetosPaso as $el){
    				$metodo = "consultar".ucfirst($el);
    				$listaPaso =  $this->cliente->$metodo($argumentos);
    				if(is_array($listaPaso)&&count($listaPaso)>0){
    					unset ($listaPaso[0]['id']);
    					
    					if(end($this->listaElementosPaso)&&isset(end($this->listaElementosPaso)[$this->listaPks[0]]))
    					  unset($listaPaso[0][$this->listaPks[0]]);
    					$this->listaElementosPaso = array_merge($this->listaElementosPaso,$listaPaso[0] );
    					
    				}
    				
    			}
    		}
    		
    		$this->listaElementos =  array_merge_recursive($this->listaElementos,$lista);
    		
    	}
    	
    		 
    	return true;
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
	
    public function ver(){
    	
    	if(!$this->seleccionarObjeto()){
    		$verifica =  false;
    	}
    	
    	$listaObjetosPaso =  array();
    	if(count($this->tablasPaso)>0){
    		foreach ($this->tablasPaso as $elemento){
    			 
    			if($this->objetoAliasSingular==$elemento[0]
    			&&$this->cliente->getObjeto($elemento[1], 'ejecutar','id')){
    				$idEl = $this->cliente->getObjeto($elemento[1], 'ejecutar','id') ;
    				$listaObjetosPaso[] = $this->cliente->getObjeto($idEl, 'id','ejecutar') ;
    			}
    		}
    	}
    	
    	$this->listaObjetosPaso = $listaObjetosPaso; 
    	
    	if(!$this->getListaElementos()){
    	
    		$verifica =  false;
    		 
    	}
		
    	$listado = $this->listaElementos   ;
    	
    	var_dump($this->listaElementos,$this->listaElementosPaso);
		
		echo '<div class="container-fluid">';
		
    	foreach ($listado as $fila){
    		echo "<form><fieldset><table>";
    		foreach ($fila as $f=>$g){
    			if(!$this->columnaVer($f)) continue;
    			echo "<tr><td><div><span><b>".utf8_encode($this->lenguaje->getCadena ($f))."</b>: </span></td><td><span> ".strtolower($this->setTextoTabla($g,$f))."</span></div></td></tr>";;
    		}
    		echo "</table></fieldset></form><br>";
    		
    		
    	}
    	
    	echo "</div>";
    	
    	    	 
    }
    
}

?>