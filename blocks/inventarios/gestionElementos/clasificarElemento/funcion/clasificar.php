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

        $conexion = "inventarios";
        $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);



        $fechaActual = date('Y-m-d');

        $arreglo = array(
            'id_elemento' => $_REQUEST['idElemento'],
            'tipo_bien' => $_REQUEST['tipo_bien_select'],
            'observacion' => $_REQUEST['observacion'],
            'fecha' => $fechaActual
        );

        $cadenaSql = $this->miSql->getCadenaSql('actualizar_tipo_bien', $arreglo);
        $contratista = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $arreglo, "actualizar_tipo_bien");

        $cadenaSql = $this->miSql->getCadenaSql('insertar_version_tipo_bien', $arreglo);
        $contratista=$esteRecursoDB->ejecutarAcceso($cadenaSql, "acceso");

        

        if ($contratista) {
            redireccion::redireccionar('inserto', $_REQUEST['usuario']);
            exit();
        } else {

            redireccion::redireccionar('noInserto', $_REQUEST['usuario']);
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