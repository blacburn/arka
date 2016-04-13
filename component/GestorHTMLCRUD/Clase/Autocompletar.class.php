<?php 



namespace component\GestoHTMLCRUD\Clase;


include_once ('core/builder/Mensaje.class.php');

include_once 'component/GestorHTMLCRUD/Modelo/Modelo.class.php';
use component\GestorHTMLCRUD\Modelo\Modelo as Modelo;

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}



class Autocompletar {

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
    private $proceso;
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
	private $objetoAliasSingular;
	    
    function __construct($lenguaje,$objetoId = '') {

    	$this->objetoId = $objetoId;
        $this->miConfigurador = \Configurador::singleton ();

        $this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );


        $this->mensaje = \Mensaje::singleton();
		$this->cliente  = new Modelo();
		
		if($lenguaje!='')$this->lenguaje = $lenguaje;
        
        
    }
    
    public function setObjetoId($valor){
    	$this->objetoId = $valor;
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
    			
    			$this->objetoConsultar = $this->setBool($this->cliente->getObjeto($this->objetoId, 'id','consultar'));
    			 
    			if($this->objetoConsultar==false) {
    				$this->mensaje->addMensaje("4000","errorOperacionNoPermitida: ".ucfirst('consultar'),'information');
    				return false;
    			}
    			
    					 
    			return true;
    	
    }
    
    private function getObjetoAliasPorId($objeto ='', $id = ''){
    	foreach ($this->$objeto as $elemento){
    		if($elemento['id']==$id) return $elemento['alias'];
    	}
    }
    
    private function getObjetoNombrePorId($objeto ='', $id = ''){
    	foreach ($this->$objeto as $elemento){
    		if($elemento['id']==$id) return $elemento['nombre'];
    	}
    }
    
    private function getListaPropiedad($nombre='anonimo'){

    	if($this->seleccionarObjeto()){
    		
    		$metodo = "consultar".ucfirst($this->objetoAliasSingular);
    		
    		$this->cliente->setGroupBy(array($nombre));
    		$this->cliente->columnasConsulta($nombre);
    		$peticion = $this->cliente->$metodo();
    		
    		if(!is_array($peticion)) return false;
    		
    		$resultado = array();
    		$indice = 0;
    		foreach ($peticion as $registro){
    			$indice++;
    			$resultado [] =  array('id'=>$registro[$nombre],'nombre'=>$registro[$nombre],'alias'=>$registro[$nombre]);
    		}
    		return $resultado; 
    		
    		
    	}
    	return false;
    }
    
    public function autocompletar($objetoId = ''){
    	
		if(!is_null($objetoId)&&$objetoId!='')  $this->setObjetoId($objetoId);
		else $this->setObjetoId($_REQUEST['objetoId']);
		
    	$mensaje = 'accionAutocompletar';
    	
		//valida si la columna esta registrada
		$idColumna = $this->cliente->getColumnas(stripslashes ($_REQUEST['field']),'nombre','id');
		$idObjetoColumna = $this->cliente->getColumnas($idColumna,'id','objetos_id');
		
		if($idObjetoColumna===FALSE) return false;
		 
		//accion 1: si es fk
		if($idObjetoColumna!=$_REQUEST['objetoId']&&$idObjetoColumna!=0){
			
          $nombreEjecucion = $this->cliente->getObjeto($_REQUEST['objetoId'],'id','ejecutar');
			
			if($nombreEjecucion===false) return false;		
		  $metodo =  "getLista".ucfirst($nombreEjecucion);
		  $lista =  	$this->cliente->$metodo();
		  
		  if(is_array($lista))echo json_encode($lista);
		  else{
		  	echo json_encode($this->getListaPropiedad(stripslashes ($_REQUEST['field'])));
		  	echo json_encode(false);
		  }
		  
		  
			
		}////accion 2 si no es fk, columna cualquiera de la misma tabla
		else{
			
			echo json_encode($this->getListaPropiedad(stripslashes ($_REQUEST['field'])));
			
		}
		
		return true;
		
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

?>