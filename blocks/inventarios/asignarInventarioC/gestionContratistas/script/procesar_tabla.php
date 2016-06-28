<?php

/**
 *
 * Los datos del bloque se encuentran en el arreglo $esteBloque.
 */

// Variables
$cadenaACodificar = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaACodificar .= "&procesarAjax=true";
$cadenaACodificar .= "&action=index.php";
$cadenaACodificar .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar .= "&funcion=ConsultarContratistas";
$cadenaACodificar .= "&usuario=" . $_REQUEST ['usuario'];

$cadenaACodificar .= "&tiempo=" . $_REQUEST ['tiempo'];

if (isset ( $_REQUEST ['vigencia'] )==true) {
	

	$cadenaACodificar .= "&vigencia=" .$_REQUEST ['vigencia'] ;   
	
}





// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion ( "enlace" );
$cadena = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificar, $enlace );

// URL definitiva
$urlFinal = $url . $cadena;



$urlDirectorio="http://10.20.0.38/arka/plugin/scripts/javascript/dataTable/Spanish.json";

?>
<script type='text/javascript'>




$(function() {
         	$('#tablaTitulos').ready(function() {

             $('#tablaTitulos').dataTable( {
//              	 serverSide: true,
		
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
                info:true,
                "scrollY":"400px",
                "scrollCollapse": false, 
                "pagingType": "full_numbers",
                "bLengthChange": false,
                "bPaginate": false,
                  ajax:{
                      url:"<?php echo $urlFinal?>",
                      dataSrc:"data"                                                                  
                  },
                  columns: [
				  { data :"vigencia" },
				  { data :"tipo_contrato"},
				  { data :"numero"},
	              { data :"identificacion" },
                  { data :"nombre" },
                  { data :"fecha_inicio" },
                  { data :"fecha_final" },
                  { data :"modificar" },
                  { data :"eliminar" },
                            ]
             });
                  
         		});

});




</script>
