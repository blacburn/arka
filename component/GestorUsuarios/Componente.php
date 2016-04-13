<?php

namespace component\GestorUsuarios;


require_once ('component/Component.class.php');
use component\Component as Component;

require_once ('component/GestorUsuarios/Clase/GestorUsuariosComponentes.class.php');
require_once ('component/GestorUsuarios/Interfaz/IGestorUsuarios.php');

use component\GestorUsuarios\interfaz\IGestionarUsuarios as IGestionarUsuarios;
use component\GestorUsuarios\clase\GestorUsuariosComponentes as GestorUsuariosComponentes;

class Componente extends Component implements IGestionarUsuarios {
	private $miUsuario;

	
	// El componente actua como Fachada
	
	/**
	 * un objeto de la clase GestorUsuarios
	 */
	public function __construct($usuario = '') {
		return $this->miUsuario = new GestorUsuariosComponentes($usuario);

	}
	
	public function validarAcceso($idRegistro = '', $permiso = '', $idObjeto = ''){
		return $this->miUsuario->validarAcceso($idRegistro, $permiso , $idObjeto );
	}
	
    public function filtrarPermitidos($consulta){
    	return $this->miUsuario->filtrarPermitidos($consulta);
    }
    
    public function crearRelacion($usuario ='',$objeto='',$registro='',$permiso = '',$estado=''){
    	return $this->miUsuario->crearRelacion($usuario,$objeto,$registro,$permiso,$estado);
    }
    public function actualizarRelacion($id = '',$usuario ='',$objeto='',$registro='',$permiso = '',$estado='',$justificacion=''){
    	return $this->miUsuario->actualizarRelacion($id,$usuario,$objeto,$registro,$permiso,$estado,$justificacion);
    }
    
    public function consultarRelacion($id = '',$usuario ='',$objeto='',$permiso = '',$estado='',$fecha=''){
    	return $this->miUsuario->consultarRelacion($id,$usuario,$objeto,$permiso,$estado,$fecha);
    }
    
    public function activarInactivarRelacion($id = ''){
    	return $this->miUsuario->activarInactivarRelacion($id );
    }
    
    public function permisosUsuario($usuario ='',$objeto='',$registro=''){
    	return $this->miUsuario->permisosUsuario($usuario,$objeto,$registro);
    }
    
    public function permisosUsuarioObjeto($usuario ='',$objeto=''){
    	return $this->miUsuario->permisosUsuarioObjeto($usuario,$objeto);
    }
    
    public function validarRelacion($usuario ='',$objeto='',$registro='',$permiso = ''){
    	return $this->miUsuario->validarRelacion($usuario,$objeto,$registro,$permiso);
    }
    
    public function habilitarServicio($usuario = ''){
    	return $this->miUsuario->habilitarServicio($usuario);
    }
	
	
}


