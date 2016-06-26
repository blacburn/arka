<?php ?>

// Asociar el widget de validación al formulario
$("#registrarTraslados").validationEngine({
promptPosition : "topLeft:180,10",  
scroll: false,
autoHidePrompt: true,
autoHideDelay: 2000
});


$(function() {
$("#registrarTraslados").submit(function() {
$resultado=$("#registrarTraslados").validationEngine("validate");

if ($resultado) {

return true;
}
return false;
});
});











