<?php

namespace inventarios\gestionElementos\clasificarElemento\funcion;

include_once ('redireccionar.php');




if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

class RegistradorOrden {

    var $miConfigurador;
    var $lenguaje;
    var $miFormulario;
    var $miFuncion;
    var $miSql;
    var $conexion;

    function __construct($lenguaje, $sql, $funcion) {
        $this->miConfigurador = \Configurador::singleton();
        $this->miConfigurador->fabricaConexiones->setRecursoDB('principal');
        $this->lenguaje = $lenguaje;
        $this->miSql = $sql;
        $this->miFuncion = $funcion;
    }

    function procesarFormulario() {



        $esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");

        $rutaBloque = $this->miConfigurador->getVariableConfiguracion("raizDocumento") . "/blocks/inventarios/gestionElementos/";
        $rutaBloque .= $esteBloque ['nombre'];

        $host = $this->miConfigurador->getVariableConfiguracion("host") . $this->miConfigurador->getVariableConfiguracion("site") . "/blocks/inventarios/gestionElementos/" . $esteBloque ['nombre'];
        
        $optDepen='';
        $optubica='';
        
        
        if((isset($_REQUEST['dependencia']) && $_REQUEST['dependencia'])!=''){
            $optDepen=$_REQUEST['dependencia'];
        }
        if((isset($_REQUEST['ubicacion']) && $_REQUEST['ubicacion'])!=''){
            $optubica=$_REQUEST['ubicacion'];
        }
       
        

        if(isset($_REQUEST['sede']) && $_REQUEST['sede']!=''){
            $optDependencia=$optDepen;
            $optubicacion=$optubica;
            
        }
     
        
        
        $arreglo = array(
            'responsable' => $_REQUEST['responsable'],
            'sede' => $_REQUEST['sede'],
            'dependencia' => $optDepen,
            'ubicacion' => $optubica,
            'selec_placa' => $_REQUEST['selec_placa'],
            'placa' => $_REQUEST['placa'],
            'campoSeguro' => $_REQUEST['campoSeguro'],
            'tiempo' => $_REQUEST['tiempo'],
            'usuario' =>$_REQUEST['usuario']
        );
        
        $aux=0;
        if(($_REQUEST['responsable'] || $_REQUEST['responsable'])!=''){
            $aux=1;
        }
        if(($_REQUEST['sede'] || $_REQUEST['sede'])!=''){
            $aux=1;
        }
        
       
        if(($_REQUEST['selec_placa'] || $_REQUEST['placa'])!=''){
            $aux=2;
        }
          
        

        if ($aux==1) {
            redireccion::redireccionar('vElementos', $arreglo);
            exit();
        } else {

            redireccion::redireccionar('elemento', $arreglo);
            exit();
        }
    }

    function resetForm() {
        foreach ($_REQUEST as $clave => $valor) {

            if ($clave != 'pagina' && $clave != 'development' && $clave != 'jquery' && $clave != 'tiempo') {
                unset($_REQUEST [$clave]);
            }
        }
    }

}

$miRegistrador = new RegistradorOrden($this->lenguaje, $this->sql, $this->funcion);

$resultado = $miRegistrador->procesarFormulario();
?>