<?php

?>

// Asociar el widget de validación al formulario
              $("#gestionDisponibilidadOrden").validationEngine({
            promptPosition : "centerRight", 
            scroll: false,
            autoHidePrompt: true,
            autoHideDelay: 2000
	         });
	
        
        $(function() {
            $("#gestionDisponibilidadOrden").submit(function() {
                $resultado=$("#gestionDisponibilidadOrden").validationEngine("validate");
                   
                if ($resultado) {
                
                    return true;
                }
                return false;
            });
        });


// Asociar el widget tabs a la división cuyo id es tabs
$(function() {
$("#tabs").tabs();
}); 

$(function() {
$( "input[type=submit], button" )
.button()
.click(function( event ) {
event.preventDefault();
});
});




        setTimeout(function() {
    		$('#divMensaje').hide( "drop", { direction: "up" }, "slow" );
			}, 3000); // <-- time in milliseconds
        

  $('#<?php echo $this->campoSeguro('sedeConsulta')?>').width(290);              	 
 $("#<?php echo $this->campoSeguro('sedeConsulta')?>").select2({
             	 placeholder: "Ingrese Mínimo 3 Caracteres de Búsqueda",
              	 minimumInputLength: 3,
              	 });  
 
 $("#<?php echo $this->campoSeguro('dependenciaConsulta')?>").select2({
             	 placeholder: "Ingrese Mínimo 3 Caracteres de Búsqueda",
              	 minimumInputLength: 3,
              	 });              	            
              	 			
$('#<?php echo $this->campoSeguro('vigencia_disponibilidad')?>').width(100);				
$("#<?php echo $this->campoSeguro('vigencia_disponibilidad')?>").select2();


$("#<?php echo $this->campoSeguro('unidad_ejecutora')?>").select2();



$('#<?php echo $this->campoSeguro('diponibilidad')?>').width(300);	
$("#<?php echo $this->campoSeguro('diponibilidad')?>").select2({
             	 placeholder: "Ingrese Mínimo 3 Caracteres de Búsqueda",
              	 minimumInputLength: 1,
              	 });              	            
$("#<?php echo $this->campoSeguro('rubro')?>").select2();

        
             
 
 
 $("#<?php echo $this->campoSeguro('numero_orden')?>").select2();
 $("#<?php echo $this->campoSeguro('tipo_orden')?>").select2();
 $("#<?php echo $this->campoSeguro('nivel')?>").select2();
 $("#<?php echo $this->campoSeguro('numero_entrada')?>").select2();
 
 
 
 
 
 
 
 
		  
  
	
	    

                
 

        
                        
        $('#<?php echo $this->campoSeguro('fecha_inicio')?>').datepicker({
		dateFormat: 'yy-mm-dd',
		maxDate: 0,
		changeYear: true,
		changeMonth: true,
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
		    'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		    monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
		    dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
		    dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
		    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
		    onSelect: function(dateText, inst) {
			var lockDate = new Date($('#<?php echo $this->campoSeguro('fecha_inicio')?>').datepicker('getDate'));
			$('input#<?php echo $this->campoSeguro('fecha_final')?>').datepicker('option', 'minDate', lockDate);
			},
			onClose: function() { 
		 	    if ($('input#<?php echo $this->campoSeguro('fecha_inicio')?>').val()!='')
                    {
                        $('#<?php echo $this->campoSeguro('fecha_final')?>').attr("class", "cuadroTexto ui-widget ui-widget-content ui-corner-all   validate[required]");
                }else {
                        $('#<?php echo $this->campoSeguro('fecha_final')?>').attr("class", "cuadroTexto ui-widget ui-widget-content ui-corner-all ");
                    }
			  }
			
			
		});
              $('#<?php echo $this->campoSeguro('fecha_final')?>').datepicker({
		dateFormat: 'yy-mm-dd',
		maxDate: 0,
		changeYear: true,
		changeMonth: true,
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
		    'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		    monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
		    dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
		    dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
		    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
		    onSelect: function(dateText, inst) {
			var lockDate = new Date($('#<?php echo $this->campoSeguro('fecha_final')?>').datepicker('getDate'));
			$('input#<?php echo $this->campoSeguro('fecha_inicio')?>').datepicker('option', 'maxDate', lockDate);
			 },
			 onClose: function() { 
		 	    if ($('input#<?php echo $this->campoSeguro('fecha_final')?>').val()!='')
                    {
                        $('#<?php echo $this->campoSeguro('fecha_inicio')?>').attr("class", "cuadroTexto ui-widget ui-widget-content ui-corner-all   validate[required]");
                }else {
                        $('#<?php echo $this->campoSeguro('fecha_inicio')?>').attr("class", "cuadroTexto ui-widget ui-widget-content ui-corner-all ");
                    }
			  }
			
	   });
	   
	   
	   			
	          $('#tablaRegistros').dataTable( {
                  "sScrollY": "80px",
		         "bPaginate": false
                 } );
	   
	          $('#tablaTitulos').dataTable( {
                "sPaginationType": "full_numbers"
                 } );
                 
              $('#tablaDisponibilidades').dataTable( {
                   "sScrollY": "150px",
		         "bPaginate": false
        
                 } );
                 
                 
	   
          






