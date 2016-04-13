<?php

namespace component\GestorUsuarios\Clase;

if (! isset ( $GLOBALS ["autorizado"] )) {
    include ("../index.php");
    exit ();
}

include_once ('component/GestorUsuarios/Interfaz/IGestorUsuarios.php');
include_once ("core/builder/Mensaje.class.php");
include_once ("core/connection/DAL.class.php");

use component\GestorUsuarios\interfaz\IGestionarUsuarios as IGestionarUsuarios;

class GestorUsuariosComponentes implements IGestionarUsuarios {
    
	
	private $parametros ;
    private $registrador;
    private $usuario;
    public $mensaje;
    private $listaRegistrosPermitidos =  array();
    private $superUsuario =  false;
    
    
    function __construct($usuario = ''){
    	
    	$this->registrador = new \DAL();
    	$this->mensaje =  \Mensaje::singleton();
    	
    	
    	//configurar usuario
    	if($usuario ==''){
    		$this->usuario = $_REQUEST['usuario'];
    		$this->registrador->setUsuario($this->usuario);
    		
    	}else{
    		$this->usuario = $usuario;
    		$this->registrador->setUsuario($this->usuario);
    	}
    	
    }
    
    public function validarAcceso($idRegistro = '', $permiso = '', $idObjeto = ''){
    	
    	
    	
    	$listaPermitidos = $this->permisosUsuarioObjeto($this->usuario,$idObjeto);

    	
    	if($this->superUsuario===true) return true;
    	
    	if(!is_array($listaPermitidos)){
    		$this->mensaje->addMensaje("101","errorPermisosGeneral",'error');
    		return false;
    	}
    	
    	$idRegistro = (integer) $idRegistro;
    	$permiso =  (integer) $permiso;
    	$idObjeto =  (integer) $permiso;

    	
    	foreach ($listaPermitidos as $permitidos){
    		
    		
    		
    		
    		//actua propietario del objeto
    		if($permitidos['registro']==0&&$permitidos['permiso_id']==0) {
    			$this->superUsuario = true;
    			return true;
    		}
    		
    		//tiene el permiso sobre todo el objeto
    		if($permitidos['registro']==0&&$permitidos['permiso_id']==$permiso) {
    			$this->superUsuario = true;
    			return true;
    		}
    		
    		//es admin
    		if($permitidos['permiso_id']==5){
    			$this->superUsuario = true;
    			return true;
    		}
    		
    		
    		//tiwne permiso solicitado explicitamente
    		if($permitidos['registro']==$idRegistro&&$permitidos['permiso_id']==$permiso) return true;
    		
    		//es propietario del registro
    		if($permitidos['registro']==$idRegistro&&$permitidos['permiso_id']==0) return true;
    		
    		
    		//propietario de algunos elementos y se permite consultarlos
    		if($permiso==2&&$permitidos['permiso_id']==0) return true;

    		
    		//tiene el permiso de consultar, no es claro sobre cuales elementos
    		if($permiso==$permitidos['permiso_id']&&$permiso==2) return true;
    		
    		
    		
    		
    	}
    	
    	$this->mensaje->addMensaje("101","errorPermisosGeneral",'error');
    	return false;
    	

    }
    
    public function filtrarPermitidos($consulta){
    	
    	if(!is_array($consulta)) return false;
    	
    	if($this->superUsuario===true) return $consulta;
    	
    	$resultado =  array();
    	foreach ($consulta as $fila){
    		if(in_array($fila['id'],$this->listaRegistrosPermitidos)) $resultado[] = $fila ;
    	}
    	
    	return $resultado;
    }
    
    private function verificaUsuario($usuario){
    	
    	
    	$parametros =  array();
    	$parametros['id'] = $usuario;
    	
    	
    	//consulta
    	$consulta =  $this->registrador->consultarUsuario($parametros);
    	
    	if(!is_array($consulta)){
    		$this->mensaje->addMensaje("101","usuarioNoExiste",'error');
    		return false;
    	}
    	return true;
    }
    
    private function verificaRegistroObjeto($objeto,$registro){
    	
    	if($registro==0) return  true;
    	
    	$parametros =  array();
    	
    	$parametros['id'] = $registro;

    	//$metodo =  "consultar".strtoupper($this->registrador->getObjeto($objeto,'id','ejecutar'));
    	
    	//consulta
    	$consulta =  $this->registrador->ejecutar($objeto,array($parametros),2);
    	if(!is_array($consulta)){
    		 
    		$this->mensaje->addMensaje("101","registroObjetoNoExiste",'error');
    		return false;
    	}
    	
    	return true;
    }
    
    public function crearRelacion($usuario ='',$objeto='',$registro='',$permiso = '',$estado=''){
    	$idObjetoRelacion = $this->registrador->getObjeto('relacion','nombre','id');
    	
    	if(!$this->validarAcceso(0,1,$idObjetoRelacion)) return false;
    	
    	if($usuario===''||$objeto===''||$registro===''||$permiso===''){
    		
    		$this->mensaje->addMensaje("101","errorEntradaParametrosGeneral",'error');
    		return false;
    	}
    	
    	if($estado=='') $estado = 1;
    	
    	//verifica que el usuario exista
    	if(!$this->verificaUsuario($usuario)) return false;
    	
    	//verifica que el objeto exista
    	if(!$this->registrador->getObjeto($objeto,'id','id')){
    		
    		return false;
    	}
    	
    	//verifica que el registro exista
    	if(!$this->verificaRegistroObjeto($objeto,$registro)) return false;
    	
    	//verifica que el permiso exista
    	if(!$this->registrador->getPermiso($permiso,'id','id')){
    		
    		
    		return false;
    	}
    	
    	$parametros =  array();
    	$parametros['usuario_id'] = $usuario;
    	$parametros['objetos_id'] = $objeto;
    	$parametros['registro'] = $registro;
    	$parametros['permiso_id'] = $permiso;
    	$parametros['estado_registro_id'] = $estado;
    	
    	
    	$ejecutar = $this->registrador->crearRelacion($parametros);
    	
    	   	if(!$ejecutar){
    		
    		
    		return false;
    	}
    	
    	return $ejecutar;
    	
    	
    	
    }
    
    
    public function actualizarRelacion($id = '',$usuario ='',$objeto='',$registro='',$permiso = '',$estado='',$justificacion=''){
    	
    	$idObjetoRelacion = $this->registrador->getObjeto('relacion','nombre','id');

    	
    	if(!$this->validarAcceso($id,3,$idObjetoRelacion)) return false;
    	if($id==''||is_null($id)||$justificacion == ''||is_null($justificacion)){
    		$this->mensaje->addMensaje("101","errorEntradaParametrosGeneral",'error');
    		return false;
    	}

    	
    	
    	$parametros =  array();
    	$parametros['id'] = $id;
    	if($usuario!=''){
    		//verifica que el usuario exista
    		if(!$this->verificaUsuario($usuario)) return false;
    		 
    		$parametros['usuario_id'] = $usuario;
    	}
    	if($objeto!=''){
    		//verifica que el objeto exista
    		if(!$this->registrador->getObjeto($objeto,'id','id')){
    			
    			return false;
    		}
    		$parametros['objetos_id'] = $objeto;
    	}
    	if($registro!=''){
    		//verifica que el registro exista
    		if(!$this->verificaRegistroObjeto($objeto,$registro)) return false;
    		 
    		$parametros['registro'] = $registro;
    	}
    	if($permiso!=''){
	    	//verifica que el permiso exista
	    	if(!$this->registrador->getPermiso($permiso,'id','id')){
	    		
	    		
	    		return false;
	    	}
    			 
    		$parametros['permiso_id'] = $permiso;
    	}
    	if($estado!='')$parametros['estado_registro_id'] = $estado;
    	
    	$parametros['justificacion'] = $justificacion;
    	
    	
    	if(!$this->registrador->actualizarRelacion($parametros)){
             
    		
    		return false;
    	}
    	 
    	return true;
    	 
    }
    
    public function consultarRelacion($id = '',$usuario ='',$objeto='',$permiso = '',$estado='',$fecha=''){
    
    	$idObjetoRelacion = $this->registrador->getObjeto('relacion','nombre','id');
    	if(!$this->validarAcceso($id,2,$idObjetoRelacion)) return false;
    	$parametros =  array();
    	if($id!='')$parametros['id'] = $id;
    	if($usuario!='')$parametros['usuario_id'] = $usuario;
    	if($objeto!='')$parametros['objetos_id'] = $objeto;
    	if($permiso!='')$parametros['permiso_id'] = $permiso;
    	if($estado!='')$parametros['estado_registro_id'] = $estado;
    	if($fecha!='') $parametros['fecha_registro'] = $fecha;
    	 
    	$consulta = $this->registrador->consultarRelacion($parametros);
    	
    	if(!$consulta){
    
    		
    		return false;
    	}
    
    	return $this->filtrarPermitidos($consulta);
    
    }
    
    public function activarInactivarRelacion($id = ''){
    
    	$idObjetoRelacion = $this->registrador->getObjeto('relacion','nombre','id');
    	if(!$this->validarAcceso($id,3,$idObjetoRelacion)) return false;
    	if($id==''||is_null($id)){
    		$this->mensaje->addMensaje("101","errorEntradaParametrosGeneral",'error');
    		return false;
    	}
    
    	$parametros =  array();
    	$parametros['id'] = $id;
    
    	
    	 
    	if(!$this->registrador->activarInactivarRelacion($parametros)){
    
    		
    		return false;
    	}
    
    	return true;
    
    }
    
    public function permisosUsuario($usuario ='',$objeto='',$registro=''){    	
    	
    	if($usuario===''||$objeto===''||$registro===''){
    		
    		$this->mensaje->addMensaje("101","errorEntradaParametrosGeneral",'error');
    		return false;
    	}
    	
    	 
    	$parametros =  array();
    	if($usuario!='')$parametros['usuario_id'] = $usuario;
    	if($objeto!='')$parametros['objetos_id'] = $objeto;
    	if($registro!='')$parametros['registro'] = $registro;
    	$consulta = $this->registrador->consultarRelacion($parametros);
    	
    	
    	if(!is_array($consulta)){
    	
    		$this->mensaje->addMensaje("101","usuarioSinPermisos",'error');
    		return false;
    	}
    	
    	$retorna =  array();
    	
    	foreach ($consulta as $registro){
    		
    		$retorna[] = $registro['permiso_id'];
    		 
    	}
    	
    	return $retorna; 
    	 
    	 
    }
    
    public function permisosUsuarioObjeto($usuario ='',$objeto=''){
    	
    	if($usuario===''||$objeto===''){
    
    		$this->mensaje->addMensaje("101","errorEntradaParametrosGeneral",'error');
    		return false;
    	}
    	 
    	
    	//es admin?
    
    	if($usuario!='')$parametros['usuario_id'] = $usuario;
    	$parametros['permiso_id'] = 5;
    	$consulta = $this->registrador->consultarRelacion($parametros);
    	return $consulta; 
    	if(is_array($consulta)){
    		
    		$this->superUsuario =  true;
    		
    	}
    	
    	
    	// consulta relaciones asociadas al usuario y objeto
    	$parametros =  array();
    	if($usuario!='')$parametros['usuario_id'] = $usuario;
    	if($objeto!='')$parametros['objetos_id'] = $objeto;
    	$consulta = $this->registrador->consultarRelacion($parametros);
    	 
    	
    	  
    	if(!is_array($consulta)){
    		 
    		$this->mensaje->addMensaje("101","usuarioSinPermisos",'error');
    		return false;
    	}
    	 
    	$retorna =  array();

    	
    	foreach ($consulta as $registro){
    		$this->listaRegistrosPermitidos[] = (integer) $registro['registro'];
    		$a = (integer) $registro['registro'];
    		$b = (integer) $registro['permiso_id'];
    		$retorna[] = array( 'registro'=> $a,  'permiso_id'=> $b);
    		 
    	}
    	asort($this->listaRegistrosPermitidos);
    	return $retorna;
    
    
    }
    
    public function validarRelacion($usuario ='',$objeto='',$registro='',$permiso = ''){
    
    	if($usuario===''||$objeto===''||$registro===''||$permiso===''){
    	
    		$this->mensaje->addMensaje("101","errorEntradaParametrosGeneral",'error');
    		return false;
    	}
    	
    	$parametros =  array();
    	
    	if($usuario!='')$parametros['usuario_id'] = $usuario;
    	if($objeto!='')$parametros['objetos_id'] = $objeto;
    	if($registro!='')$parametros['registro'] = $registro;
    	if($permiso!='')$parametros['permiso_id'] = $permiso;
    	
    	$parametros['estado_registro_id'] = 1;
    
    	$consulta = $this->registrador->consultarRelacion($parametros);
    	 
    	if(!is_array($consulta)){
    
    		$this->mensaje->addMensaje("101","relacionNoExiste",'error');
    		return false;
    	}
    
    	
    	return true;
    
    }
    
    private function registrarAcceso($codigo , $usuario, $detalle){
    	
    	
    	//archivo
    	//http://stackoverflow.com/questions/19898688/how-to-create-a-logfile-in-php
    	//Something to write to txt log
    	$log  = "Cliente: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
    	"Intento: ".($this->verificaUsuario($usuario)?'Exito':'Fallo').PHP_EOL.
    	"Usuario: ".$usuario.PHP_EOL.
    	"Codigo: ".$codigo.PHP_EOL.
    	"-------------------------".PHP_EOL.PHP_EOL;
    	//Save string to log, use FILE_APPEND to append.
    	file_put_contents(__DIR__.'/../log/log_'.date("j.n.Y").'.txt', $log, FILE_APPEND);
    	
    	
    	//bd
    	//id objeto de acceso
    	
    	$parametros['codigo'] = $codigo;
    	$parametros['usuario'] = $usuario;
    	$parametros['detalle'] = $detalle;
    	
    	$this->registrador->crearAcceso($parametros);
    	
    }
    
    private function codificar($array){
    	
    	$cadena = serialize($array);
    	$cadena = base64_encode ($cadena);
    	return $cadena;
    }
    
    private function decodificar($cadena){
    	$decodificada = unserialize($cadena);
    	$decodificada = base64_decode ($cadena);
    	return $decodificada;
    }
    
    public function habilitarServicio($usuario = ''){

    	if($usuario==''&&!is_null($usuario)){
    		$usuario = $this->usuario  ;
    	}
    	
    	//codigo
    	$codigo = uniqid();
    	
    	//detalle
    	$detalle = $this->codificar(array_merge ($_SERVER,$_REQUEST));
    	
    	//hace registro del acceso
    	$this->registrarAcceso($codigo, $usuario , $detalle);
    	
    	
    	//verifica que el usuario este en la lista de usuarios
    	if(!$this->verificaUsuario($usuario)){
    		$this->mensaje->addMensaje("101","usuarioNoAutorizado",'error');
    		return false;
    	}

    	return true;
    	
    		
    	
    	
    	
     
    }
       


}

?>
