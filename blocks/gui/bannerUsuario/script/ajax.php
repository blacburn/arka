<?php
/**
 * 
 * Importante: Si se desean los datos del bloque estos se encuentran en el arreglo $esteBloque
 */

// Variables
$cadenaACodificar = "pagina=" . $this->miConfigurador->getVariableConfiguracion ( "pagina" );
$cadenaACodificar .= "&procesarAjax=true";
$cadenaACodificar .= "&action=index.php";
$cadenaACodificar .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar .= "&bloqueGrupo=" . $esteBloque ["grupo"];
// Nombre de la función en procesarAjax que deberá procesar la petición
$cadenaACodificar .= "&funcion='procesarWidgetNotificador'";

$url = $this->miConfigurador->getVariableConfiguracion ( "host" );
$url .= $this->miConfigurador->getVariableConfiguracion ( "site" );
$url .= "/index.php?" . $this->miConfigurador->getVariableConfiguracion ( "enlace" );

// Codificar las variables y asociarlas a la url
$cadena = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $cadenaACodificar, $url );

// Registra la función javascript suponiendo que existe un control de texto cuyo id es entrada
?>
<script type='text/javascript'>
 

</script>