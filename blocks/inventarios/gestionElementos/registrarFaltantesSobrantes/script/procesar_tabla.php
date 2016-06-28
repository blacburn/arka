<?php
/**
 *
 * Los datos del bloque se encuentran en el arreglo $esteBloque.
 */
// Variables
$cadenaACodificar = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificar .= "&procesarAjax=true";
$cadenaACodificar .= "&action=index.php";
$cadenaACodificar .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar .= "&funcion=Consulta";
$cadenaACodificar .= "&usuario=" . $_REQUEST ['usuario'];
$cadenaACodificar .= "&tiempo=" . $_REQUEST ['tiempo'];

if (isset($_REQUEST ['accesoCondor']) && $_REQUEST ['accesoCondor'] == 'true') {

    $_REQUEST ['funcionario'] = $_REQUEST ['usuario'];
    $cadenaACodificar .= "&accesoCondor='true'";
}

if (isset($_REQUEST ['funcionario']) && $_REQUEST ['funcionario'] != '') {
    $funcionario = $_REQUEST ['funcionario'];
} else {
    $funcionario = '';
}

if (isset($_REQUEST ['sede']) && $_REQUEST ['sede'] != '') {
    $sede = $_REQUEST ['sede'];
} else {
    $sede = '';
}

if (isset($_REQUEST ['dependencia']) && $_REQUEST ['dependencia'] != '') {
    $dependencia = $_REQUEST ['dependencia'];
} else {
    $dependencia = '';
}

if (isset($_REQUEST ['ubicacion']) && $_REQUEST ['ubicacion'] != '') {
    $ubicacion = $_REQUEST ['ubicacion'];
} else {
    $ubicacion = '';
}

$arreglo = array(
    'funcionario' => $funcionario,
    'sede' => $sede,
    'dependencia' => $dependencia,
    'ubicacion' => $ubicacion
);

$arreglo = serialize($arreglo);

$cadenaACodificar .= "&arreglo=" . $arreglo;

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$cadena = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar, $enlace);

// URL definitiva
$urlFinal = $url . $cadena;
?>
<script type='text/javascript'>




    $(function () {


        $('#tablaTitulos').ready(function () {

            var table = $('#tablaTitulos').dataTable({
                "language": {
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "Mostrar _MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sSearch": "Buscar:",
                    "sLoadingRecords": "Cargando...",
                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "oPaginate": {
                        "sFirst": "Primero",
                        "sLast": "Ãšltimo",
                        "sNext": "Siguiente",
                        "sPrevious": "Anterior"
                    }
                },
                processing: true,
                searching: true,
                info: true,
                "scrollY": "400px",
                "scrollCollapse": false,
                "bLengthChange": false,
                "bPaginate": false



            });



        });

    });




</script>
