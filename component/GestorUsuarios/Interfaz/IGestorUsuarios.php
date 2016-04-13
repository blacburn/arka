<?php
namespace component\GestorUsuarios\interfaz;

interface IGestionarUsuarios{
    
    
    
    public function validarAcceso($idRegistro = '', $permiso = '', $idObjeto = '');
    public function filtrarPermitidos($consulta);
    public function crearRelacion($usuario ='',$objeto='',$registro='',$permiso = '',$estado='');
    public function actualizarRelacion($id = '',$usuario ='',$objeto='',$registro='',$permiso = '',$estado='',$justificacion='');
    public function consultarRelacion($id = '',$usuario ='',$objeto='',$permiso = '',$estado='',$fecha='');
    public function activarInactivarRelacion($id = '');
    public function permisosUsuario($usuario ='',$objeto='',$registro='');
    public function permisosUsuarioObjeto($usuario ='',$objeto='');
    public function validarRelacion($usuario ='',$objeto='',$registro='',$permiso = '');
    public function habilitarServicio($usuario = '');

}


?>