<?php

namespace component\GestorHTMLCRUD\Vista;


include_once 'component/GestorHTMLCRUD/Modelo/Modelo.class.php';
use component\GestorHTMLCRUD\Modelo\Modelo as Modelo;
include_once ('core/builder/Mensaje.class.php');

include_once ("core/manager/Configurador.class.php");

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}


class Script {
	
	private $operaciones;
	private $funcionInicio;
	private $bloqueNombre;
	private $bloqueGrupo;
	private $miConfigurador;
	private $urlBase;
		
	public function __construct(){
		
		$this->miConfigurador = \Configurador::singleton ();
		$this->setUrlBase();
	}
	
	public function setBloque($nombre , $grupo = ''){
	    $this->bloqueNombre =  $nombre;
	    $this->bloqueGrupo =  $grupo;
			
			
	}
	
	public function setOperaciones($operaciones){
		$this->operaciones =  $operaciones;
	}
	
	public function setFuncionInicio($funcionInicio)	{
		$this->funcionInicio = $funcionInicio;
	}
	
	private function setUrlBase(){
		
		//URL base
		$this->urlBase=$this->miConfigurador->getVariableConfiguracion("host");
		$this->urlBase.=$this->miConfigurador->getVariableConfiguracion("site");
		$this->urlBase.="/index.php?";
		
		
	}
	
	private function setUrl($cadena){
		
		//Variables
		$cadenaACodificar="pagina=".$this->miConfigurador->getVariableConfiguracion("pagina");
		$cadenaACodificar.="&procesarAjax=true";
		$cadenaACodificar.="&action=index.php";
		$cadenaACodificar.="&bloqueNombre=".$this->bloqueNombre;
		$cadenaACodificar.="&bloqueGrupo=".$this->bloqueGrupo;
		
		
		//Codificar las variables
		$enlace=$this->miConfigurador->getVariableConfiguracion("enlace");
		
		//Cadena codificada para consultar
		$cadenaACodificar1=$cadenaACodificar."&".$cadena;//"&funcion=consultar";
		$cadena1=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar1,$enlace);
		
		return $this->urlBase.$cadena1;
				
	}
	
	public function rangoFecha(){
	  	echo "<script type='text/javascript'>\n";
		?>
	       function activarRangoFecha(elemento){
		
		$( '#min'+toTitleCase(elemento) ).datepicker({
		      defaultDate: "+1w",
		      changeMonth: true,
		      numberOfMonths: 3,
		      dateFormat: "dd/mm/yy",
		      onClose: function( selectedDate ) {
		        $( '#max'+toTitleCase(elemento) ).datepicker( "option", "minDate", selectedDate );
		      }
		    });
		    $('#max'+toTitleCase(elemento) ).datepicker({
		      defaultDate: "+1w",
		      changeMonth: true,
		      numberOfMonths: 3,
		      dateFormat: "dd/mm/yy",
		      onClose: function( selectedDate ) {
		        $( '#min'+toTitleCase(elemento) ).datepicker( "option", "maxDate", selectedDate );
		      }
		    });
		}	

	function desactivarRangoFecha(elemento){
		$( '#min'+toTitleCase(elemento) ).datepicker( "option", "disabled", true );
		$( '#max'+toTitleCase(elemento) ).datepicker( "option", "disabled", true );
	}
	
	function setRango(elemento){
		if(elemento.length>0){
			var minimo = $('#min'+toTitleCase(elemento)).val();
			var maximo = $('#max'+toTitleCase(elemento)).val();
			var cadena = minimo + "," + maximo;


			//if((fmin instanceof Date));
            
			
			//var fmin = $.datepicker.parseDate( "dd/mm/yy", minimo);
			//var fmax = $.datepicker.parseDate( "dd/mm/yy", maximo);

			skip =  false;
			try {
				if(($.datepicker.parseDate( "dd/mm/yy", minimo) instanceof Date)) {
					skip = true;
		            			    
				    }
			}
			catch(err) {
			}


			try {
				if(($.datepicker.parseDate( "dd/mm/yy", maximo) instanceof Date)) {
					skip = true;			    
				    }
			}
			catch(err) {
			}
            
			if(skip==false){
				if(Number(minimo)>Number(maximo)){
					
					$('#'+elemento).val('0,1');
					$('#min'+toTitleCase(elemento)).val('');
					$('#max'+toTitleCase(elemento)).val('');
					$("<div><span>Rango Inv&aacutelido</span></div>").dialog();
				}
			}
			
			$('#'+elemento).val(cadena);
 				}
	}

    function goToByScroll(id){
        // Reove "link" from the ID
      id = id.replace("link", "");
        // Scroll
      $('html,body').animate({
          scrollTop: $("#"+id).offset().top},
          'slow');
  }
	
	
	function toTitleCase(str){
      return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
     }

	
		
		<?php
		echo "\n";
        echo "</script>\n";
	
	}

    public function tablaConsulta(){
    	
		echo "<script type='text/javascript'>\n";
		?>
            function tablaConsulta(idElemento){
	
	
				$( "#tabla" ).selectable({
					filter:'.fila',
			      stop: function() {
			       var conteo = 0;
			       var seleccion =  [];
			        $( ".ui-selected", this ).each(function() {
			          var index = $( "#tabla tr.fila" ).index( this );
			          conteo++;
			          seleccion.push($(this).children(":first").html());
			
			        });
			        
			        if(conteo>0) $('#'+idElemento).val(seleccion.join());
			        else $('#'+idElemento).val('');
			        
			        
			      }
			    });
			
				$('#tabla').DataTable({"jQueryUI": true, responsive: true	});
			
			}    
        <?php
		echo "\n";
        echo "</script>\n";
	
    }
	
	public function extensiones(){
	
		echo "<script type='text/javascript'>\n";
	
		?>	
		   //http://jquery-howto.blogspot.com/2013/08/jquery-form-reset.html
			//limpia formulario
			$.fn.clearForm = function() {
				  return this.each(function() {
				    var type = this.type, tag = this.tagName.toLowerCase();
				    if (tag == 'form')
				      return $(':input',this).clearForm();
				    if (type == 'text' || type == 'password' || tag == 'textarea' || type == 'hidden')
				      this.value = '';
				    else if (type == 'checkbox' || type == 'radio')
				      this.checked = false;
				    else if (tag == 'select')
				      this.selectedIndex = -1;
				  });
				};
				
				
				//Funcion para insertar despues del cursor en un text area
				//fuente http://jsfiddle.net/rmDzu/2/
				jQuery.fn.extend({
					insertAtCaret: function(myValue){
					  return this.each(function(i) {
					    if (document.selection) {
					      //For browsers like Internet Explorer
					      this.focus();
					      var sel = document.selection.createRange();
					      sel.text = myValue;
					      this.focus();
					    }
					    else if (this.selectionStart || this.selectionStart == '0') {
					      //For browsers like Firefox and Webkit based
					      var startPos = this.selectionStart;
					      var endPos = this.selectionEnd;
					      var scrollTop = this.scrollTop;
					      this.value = this.value.substring(0, startPos)+myValue+this.value.substring(endPos,this.value.length);
					      this.focus();
					      this.selectionStart = startPos + myValue.length;
					      this.selectionEnd = startPos + myValue.length;
					      this.scrollTop = scrollTop;
					    } else {
					      this.value += myValue;
					      this.focus();
					    }
					  });
					}
					});
					
		<?php
		echo "\n";
        echo "</script>\n";
	
					   
	}

	public function guardarDatos($queryString = "funcion=guardarDatos"){
	
		echo "<script type='text/javascript'>\n";
			
		?>
		
		
		          function guardarElemento(){
            
        	if($("#formularioCreacionEdicion").validationEngine('validate')!=false){
		        	var data =  $( "#objetosFormulario" ).serialize()+"&"+$( "#identificacionFormulario" ).serialize();
		        	data += "&"+ $( "#seleccionFormulario" ).serialize();
		        	data += "&"+ $( "#formularioCreacionEdicion" ).serialize(); 

		    		

					
					//si esta editando y justificacion sea diferente de ''
					if($('#justificacion').length>0) data += "&justificacion="+$('#justificacion').val();
					
					if($('#selectedItems').val()!=''){
						if($('#formularioConsulta').length>0) $('#formularioConsulta')[0].reset();
						if( $('#justificacion').length==0 || $('#justificacion').val()==''){

							
							justificacion =  '<form id="formJustificacion"><label><span>Justificaci&oacuten</span></label><textarea class="validate[required]" id="justificacion" name = "justificacion"></textarea></form>';

							$(".ui-dialog-content").dialog('destroy').remove();
							
							$(justificacion).dialog({
								
								dialogClass: "no-close",
									
								buttons: {
							        "Aceptar": function() {

								      if($("#formJustificacion").validationEngine('validate')==false) return false;

							          $( this ).dialog( "close" );
							          
							          guardarElemento();
								      
							          
							        },
							        "Cancelar":function() {
							        	$( this ).dialog( "close" );
							        	$(".ui-dialog-content").dialog('destroy').remove();

								        }
								}
						   });
							return 0;	   
						}

						$(".ui-dialog-content").dialog('destroy').remove();	
						
						
					}
					
	                var div = document.getElementById("espacioMensaje");
					div.innerHTML = '<div id="loading"></div>';
                    
					 
		        	
					if($('#formularioConsulta').length>0) $('#formularioConsulta')[0].reset();

		        	
		        	$.ajax({
			            url: "<?php echo $this->setUrl($queryString);?>",
			            type:"post",
			            data:data,
			            dataType: "html",
			            success: function(jresp){
			            	if(jresp.indexOf('Error')>0
			            	  ||jresp.indexOf('error')>0
			            	  ||jresp.indexOf('Falló')>0
			            	  ||jresp.indexOf('Fallo')>0
			            	  ||jresp.indexOf('fallo')>0){
			                	div.innerHTML = '';
			                	$(jresp).dialog();
			                }else {
			            	 getFormularioConsulta(true, jresp);
			            	 $('#selectedItems').val('');
					       }
			            }
			        });
        	}
        	
           }
		  
		   
		   
		
		    <?php
		echo "\n";
		echo "</script>\n";
		
		}
	
	
	public function crear($queryString = "funcion=crear"){

			echo "<script type='text/javascript'>\n";
			
			?>

				
				  function getFormularioCreacion(skip, mensaje){
			            
			            idObjeto = $('#idObjeto').val();
			            if($('#formularioConsulta').length>0) $('#formularioConsulta')[0].reset();
			            if($('#formularioCreacionEdicion').length>0) $('#formularioCreacionEdicion')[0].reset();
			            var data =  $( "#objetosFormulario" ).serialize()+"&"+$( "#identificacionFormulario" ).serialize();
			            if($('#selectedItems').val()!=''&&!skip) data += "&"+ $( "#seleccionFormulario" ).serialize();
			            
            
            
	            var div = document.getElementById("espacioMensaje");
				div.innerHTML = '<div id="loading"></div>';

				 

		            $.ajax({
		            url: "<?php echo $this->setUrl($queryString);?>",
		            type:"post",
		            data:data,
		            dataType: "html",
		            success: function(jresp){
		                
			  			var div = document.getElementById("espacioTrabajo");
			  			div.innerHTML = jresp;
	                    tablaConsulta();
			  			var div = document.getElementById("espacioMensaje");
			  			if(mensaje&&mensaje.length>0){
				  			 div.innerHTML=mensaje;
				  			setTimeout(function() {
				  		        $("#divMensaje").hide('drop', {}, 500)
				  		    }, 20000);
			  			}
			  			else div.innerHTML="";
			  			
			  			$("button").button().click(function(event) {
	                		event.preventDefault();
	                	});
	                				  			
		            }
		        });

            

            }
    
				
				
	        <?php
		echo "\n";
		echo "</script>\n";

     }
     
     

     public function editar($queryString = "funcion=editar"){
     
     	echo "<script type='text/javascript'>\n";
     		
     	?>
     
     				
     				  function getFormularioEdicion(skip, mensaje){
     			            
     			            idObjeto = $('#idObjeto').val();
     			            if($('#formularioConsulta').length>0) $('#formularioConsulta')[0].reset();
     			            if($('#formularioCreacionEdicion').length>0) $('#formularioCreacionEdicion')[0].reset();
     			            var data =  $( "#objetosFormulario" ).serialize()+"&"+$( "#identificacionFormulario" ).serialize();
     			            if($('#selectedItems').val()!=''&&!skip) data += "&"+ $( "#seleccionFormulario" ).serialize();
     			            
                 
                 
     	            var div = document.getElementById("espacioMensaje");
     				div.innerHTML = '<div id="loading"></div>';
     
     				 
     
     		            $.ajax({
     		            url: "<?php echo $this->setUrl($queryString);?>",
     		            type:"post",
     		            data:data,
     		            dataType: "html",
     		            success: function(jresp){
     		                
     			  			var div = document.getElementById("espacioTrabajo");
     			  			div.innerHTML = jresp;
     	                    tablaConsulta();
     			  			var div = document.getElementById("espacioMensaje");
     			  			if(mensaje&&mensaje.length>0){
     				  			 div.innerHTML=mensaje;
     				  			setTimeout(function() {
     				  		        $("#divMensaje").hide('drop', {}, 500)
     				  		    }, 20000);
     			  			}
     			  			else div.innerHTML="";
     			  			
     			  			$("button").button().click(function(event) {
	                		event.preventDefault();
	                	});			  			
     		            }
     		        });
     
                 
     
                 }
         
     				
     				
     	        <?php
     		echo "\n";
     		echo "</script>\n";
     
          }
          

	
	public function ayudasFormulario(){
		
		echo "<script type='text/javascript'>\n";
	
		?>
		  	function formularioReset(elemento){
				$('#'+elemento)[0].reset();
				cambiarCategoria('categoria');
				cambiarRango('tipo');
			}
		
			function formularioClean(elemento){
				$('#'+elemento).clearForm()
				cambiarCategoria('categoria');
				cambiarRango('tipo');
			}
			
			function cambiarCategoria(elemento){
				elemento=  'ruta';
		
				if($( "#categoria").length==0) return false;
				
				$('#'+elemento).show();
				switch($( "#categoria option:selected" ).text().toLowerCase()){
				case 'interna':
					  $('#'+elemento).hide();
					  $('#'+elemento).val(0);
					  
			    	  
					break;
				}
				codificarValor('ruta');
			}
			
			
	function cambiarRango(elemento){
		      
		      elemento = 'rango';
		      if(!document.getElementById('valor')) return 0;
		      var valorInicial = $( "#valor" ).val();
		      desactivarRangoFecha(elemento);
		      $('#min'+toTitleCase(elemento)).removeAttr('disabled');
		      $('#max'+toTitleCase(elemento)).removeAttr('disabled');
		      desactivarFechaValor();
		      alternarInput('valor','textarea');
		      
		      switch($( "#tipo option:selected" ).text().toLowerCase()){
			      case 'boleano':

			    	  var minimo = 0;
			    	  var maximo = 1;
			    	  var cadena = minimo + "," + maximo; 

			    	  
			    	  alternarInput('valor','boleano');
			    	  
			    	   
			    	  $('#'+elemento).val(cadena);
			    	  $('#'+elemento).hide();
			    	  
			    	  
			    	  $('#min'+toTitleCase(elemento)).hide();
			    	  $('#max'+toTitleCase(elemento)).hide();
			    	  cambiarClaseValidacion('valor' , 'validate[required,custom[flotante]]');
			    	  cambiarClaseValidacion('min'+toTitleCase(elemento) , 'validate[required,custom[integer]]');
			    	  cambiarClaseValidacion('max'+toTitleCase(elemento) , 'validate[required,custom[integer]]');
			    	  
			    	  break;
			      case 'entero':

			    	  var minimo = $('#min'+toTitleCase(elemento)).val();
			    	  var maximo = $('#max'+toTitleCase(elemento)).val();;
			    	  var cadena = minimo + "," + maximo;
			    	  $('#'+elemento).val(cadena);
			    	  
				      
			    	  $('#min'+toTitleCase(elemento)).attr("placeholder", "ej:-1000");
			    	  $('#max'+toTitleCase(elemento)).attr("placeholder", "ej:1000");
			    	  
			    	  $('#'+elemento).hide();
			    	  $('#min'+toTitleCase(elemento)).show();
			    	  $('#max'+toTitleCase(elemento)).show();
			    	  cambiarClaseValidacion('valor' , 'validate[required,custom[flotante]]');
			    	  cambiarClaseValidacion('min'+toTitleCase(elemento) , 'validate[required,custom[integer]]');
			    	  cambiarClaseValidacion('max'+toTitleCase(elemento) , 'validate[required,custom[integer]]');
			    	  break;
			      case 'doble':			    	  

			    	  var minimo = $('#min'+toTitleCase(elemento)).val();
			    	  var maximo = $('#max'+toTitleCase(elemento)).val();;
			    	  var cadena = minimo + "," + maximo;
			    	  $('#'+elemento).val(cadena);
				      
			    	  
			    	  $('#min'+toTitleCase(elemento)).attr("placeholder", 'ej:-1000.0');
			    	  $('#max'+toTitleCase(elemento)).attr("placeholder", 'ej:1000.0');
			    	  
			    	  $('#'+elemento).hide();
			    	  $('#min'+toTitleCase(elemento)).show();
			    	  $('#max'+toTitleCase(elemento)).show();
			    	  cambiarClaseValidacion('valor' , 'validate[required,custom[flotante]]');
			    	  cambiarClaseValidacion('min'+toTitleCase(elemento) , 'validate[required,custom[flotante]]');
			    	  cambiarClaseValidacion('max'+toTitleCase(elemento) , 'validate[required,custom[flotante]]');
			    	  break;
			      case 'porcentaje':			    	  
			    	  
			    	  var minimo = $('#min'+toTitleCase(elemento)).val();
			    	  var maximo = $('#max'+toTitleCase(elemento)).val();;
			    	  var cadena = minimo + "," + maximo;
			    	  $('#'+elemento).val(cadena);
				      

			    	  $('#min'+toTitleCase(elemento)).attr("placeholder", 'ej:0');
			    	  $('#max'+toTitleCase(elemento)).attr("placeholder", 'ej:100');
			    	  
			    	  $('#'+elemento).hide();
			    	  $('#min'+toTitleCase(elemento)).show();
			    	  $('#max'+toTitleCase(elemento)).show();
			    	  cambiarClaseValidacion('valor' , 'validate[required,custom[flotante]]');
			    	  cambiarClaseValidacion('min'+toTitleCase(elemento) , 'validate[required,custom[flotante]]');
			    	  cambiarClaseValidacion('max'+toTitleCase(elemento) , 'validate[required,custom[flotante]]');
				      break;
			      case 'fecha':
			    	  if($( "#objetoId" ).val()!=4&&$( "#objetoId" ).val()!=3){
			    		  alternarInput('valor','text');
			    		  activarFechaValor();
			    	  }
			    	  
			    	  
			    	  var d = new Date();
			    	  var curr_date = d.getDate();
			    	  var curr_month = d.getMonth();
			    	  var curr_year = d.getFullYear();
			    	  var fecha = curr_date + "/" + curr_month + "/" + curr_year;
			    	  $('#min'+toTitleCase(elemento)).attr("placeholder", 'ej:'+fecha);
			    	  $('#max'+toTitleCase(elemento)).attr("placeholder", 'ej:1/01/2018');


			    	  var minimo = $('#min'+toTitleCase(elemento)).val();
			    	  var maximo = $('#max'+toTitleCase(elemento)).val();;
			    	  var cadena = minimo + "," + maximo;
			    	  $('#'+elemento).val(cadena); 
			    	  
			    	  
			    	  $('#'+elemento).hide();
			    	  
			    	  $('#min'+toTitleCase(elemento)).show();
			    	  $('#max'+toTitleCase(elemento)).show();
                      
			    	  cambiarClaseValidacion('valor' , 'validate[required,custom[date]]');
			    	  cambiarClaseValidacion('min'+toTitleCase(elemento) , 'validate[required,custom[date]]');
			    	  cambiarClaseValidacion('max'+toTitleCase(elemento) , 'validate[required,custom[date]]');
			    	  activarRangoFecha(elemento);

				      
			    	  
				      break;
			      case 'texto':

			    	  var minimo = $('#min'+toTitleCase(elemento)).val();
			    	  var maximo = $('#max'+toTitleCase(elemento)).val();;
			    	  var cadena = minimo + "," + maximo;
			    	  $('#'+elemento).val(cadena);
				       
			    	  var cadena = 'a,b';
			    	  $('#'+elemento).attr("placeholder", 'ej:'+cadena);
			    	  
			    	  $('#min'+toTitleCase(elemento)).hide();
			    	  $('#max'+toTitleCase(elemento)).hide();
			    	  $('#'+elemento).show();
			    	  cambiarClaseValidacion('valor' , 'validate[required]');
			    	  cambiarClaseValidacion('min'+toTitleCase(elemento) , 'validate[required]');
			    	  cambiarClaseValidacion('max'+toTitleCase(elemento) , 'validate[required]');
			    	  break;
			      case 'lista':

			    	  var minimo = $('#min'+toTitleCase(elemento)).val();
			    	  var maximo = $('#max'+toTitleCase(elemento)).val();;
			    	  var cadena = minimo + "," + maximo;
			    	  $('#'+elemento).val(cadena);
			    	  
			    	  var cadena = '1,2';
			    	  $('#'+elemento).attr("placeholder", 'ej:'+cadena);
			    	  
			    	  $('#min'+toTitleCase(elemento)).hide();
			    	  $('#max'+toTitleCase(elemento)).hide();
			    	  $('#'+elemento).show();
			    	  cambiarClaseValidacion('valor' , 'validate[required]');
			    	  cambiarClaseValidacion('min'+toTitleCase(elemento) , 'validate[required]');
			    	  cambiarClaseValidacion('max'+toTitleCase(elemento) , 'validate[required]');
			    	  break;
			      case 'nulo':
			    	  var cadena = 'null';
			    	  $('#'+elemento).val(cadena);
			    	  
			    	  $('#min'+toTitleCase(elemento)).hide();
			    	  $('#max'+toTitleCase(elemento)).hide();
			    	  $('#'+elemento).hide();
			    	  cambiarClaseValidacion('valor' , 'validate[required]');
			    	  cambiarClaseValidacion('min'+toTitleCase(elemento) , 'validate[required]');
			    	  cambiarClaseValidacion('max'+toTitleCase(elemento) , 'validate[required]');
				      break;
				  default:
				      $('#'+elemento).hide();
			    	  $('#min'+toTitleCase(elemento)).show();
			    	  $('#max'+toTitleCase(elemento)).show();
			    	  
					      break;
			      }

		      $( "#valor" ).val(valorInicial);
		}

	
		<?php
		echo "\n";
        echo "</script>\n";
	
		
	}
	
	public function autocompletar($queryString = "funcion=autocompletar"){
	  	echo "<script type='text/javascript'>\n";
		?>
		  
		     var listaIds =  [];
             var listaNombres = [];
             var listaAlias = [];

		  
		  function autocompletar(elemento){


            if($('#formularioCreacionEdicion').length>0&&elemento!='proceso') return false;;
        	
        	if(typeof listaIds[elemento]=='undefined'){

        	$( "#"+elemento+'Nombre' ).attr('disabled',true);
            	
        	listaIds[elemento] =  [];
        	listaNombres[elemento] = [];
        	listaAlias[elemento] = [];       	

        	var data =  $( "#objetosFormulario" ).serialize()+"&"+$( "#identificacionFormulario" ).serialize();
        	data +=  "&field="+elemento;

        	
        	
        	$.ajax({
	            url: "<?php echo $this->setUrl($queryString);?>",
	            type:"post",
	            data:data,
	            dataType: "json",
	            success: function(jresp){
	            	 
	            	
		  			for(i=0;i<jresp.length;i++){
		  				listaIds[elemento].push( jresp[i].id);
		  				listaNombres[elemento].push( jresp[i].nombre);
		  				listaAlias[elemento].push( jresp[i].alias); 
		  			}  
			       }
	        });

        	
        	$( "#"+elemento+'Nombre' ).autocomplete({
        	      source: listaNombres[elemento]
        	    });
        	    
        	$( "#"+elemento+'Nombre' ).change(function() {
        		   
        	    	var indice = listaNombres[elemento].indexOf(this.value);
        	    	if(typeof listaIds[elemento][indice] == 'undefined') $( "#"+elemento).val($( "#"+elemento+'Nombre' ).val());
                	else $( "#"+elemento).val(listaIds[elemento][indice]);
                	$( "#"+elemento).trigger("change");
        	    	
            	    });

        	$( "#"+elemento+'Nombre' ).attr('disabled',false);
        	}

        	
        	//$( "#"+elemento+'Nombre' ).trigger("change");
        	var indice = listaNombres[elemento].indexOf($( "#"+elemento+'Nombre' ).val());
        	if(typeof listaIds[elemento][indice] == 'undefined') $( "#"+elemento).val($( "#"+elemento+'Nombre' ).val());
        	else $( "#"+elemento).val(listaIds[elemento][indice]);
        	
        	
        	
        	return 0;
        	
        	
           }	

        function cambiarValoresAutocomplete(valor,id){
	    	var indice = listaNombres[elemento].indexOf(String(valor));
	    	if(typeof listaIds[elemento][indice] == 'undefined') $( "#"+elemento).val($( "#"+elemento+'Nombre' ).val());
        	else $( "#"+elemento).val(listaIds[elemento][indice]);
	    	$( "#"+elemento).trigger("change");
	    	
        
        }
            
        function validarValorLista(valor,id){
            
            var elemento = id.replace('Nombre','');
            autocompletar(elemento);
            //console.log(listaNombres[elemento]);
            if(typeof listaNombres[elemento] == 'undefined') autocompletar(elemento);
            //for(i=0;i<listaNombres[elemento].length;i++) 
            //    console.log(typeof valor, typeof listaNombres[elemento],valor,listaNombres[elemento]);
            //console.log(listaNombres[elemento].length, elemento,valor,listaNombres[elemento],listaNombres[elemento].indexOf(String(valor)));
        	return listaNombres[elemento].indexOf(String(valor))<0?false:true;
        }

		  
		<?php
		echo "\n";
        echo "</script>\n";
	
		}
		
		public function cambiarEstado($queryString = "funcion=cambiarEstado"){
			echo "<script type='text/javascript'>\n";
			?>
					       
					    function cambiarEstadoElemento(){
        	var data =  $( "#objetosFormulario" ).serialize()+"&"+$( "#identificacionFormulario" ).serialize();
        	data += "&"+ $( "#seleccionFormulario" ).serialize();

        	var div = document.getElementById("espacioMensaje");
			div.innerHTML = '<div id="loading"></div>';
			
        	
        	if($('#formularioConsulta').length>0) $('#formularioConsulta')[0].reset();
        	$.ajax({
	            url: "<?php echo $this->setUrl($queryString);?>",
	            type:"post",
	            data:data,
	            dataType: "html",
	            success: function(jresp){
	            	 
	            	getFormularioConsulta(true, jresp);
		  			
			       }
	        });
        	
           }
			
					    
						<?php
						echo "\n";
				        echo "</script>\n";
					
						
					}

					public function eliminar($queryString = "funcion=eliminar"){
						echo "<script type='text/javascript'>\n";
						?>
										       
										    function eliminarElemento(){
					        	var data =  $( "#objetosFormulario" ).serialize()+"&"+$( "#identificacionFormulario" ).serialize();
					        	data += "&"+ $( "#seleccionFormulario" ).serialize();
					
					        	var div = document.getElementById("espacioMensaje");
								div.innerHTML = '<div id="loading"></div>';
								
					        	
					        	if($('#formularioConsulta').length>0) $('#formularioConsulta')[0].reset();
					        	$.ajax({
						            url: "<?php echo $this->setUrl($queryString);?>",
						            type:"post",
						            data:data,
						            dataType: "html",
						            success: function(jresp){
						            	 
						            	getFormularioConsulta(true, jresp);
							  			
								       }
						        });
					        	
					           }
								
										    
											<?php
											echo "\n";
									        echo "</script>\n";
										
											
										}					

		public function ver($queryString = "funcion=ver"){
			echo "<script type='text/javascript'>\n";
			?>
			       
			    
			    function verElemento(){
		
			    	
		        	idObjeto = $('#idObjeto').val();
		            
		            var data =  $( "#objetosFormulario" ).serialize()+"&"+$( "#identificacionFormulario" ).serialize();
					data +="&"+$( "#selectedItems" ).serialize();
		            
		            var div = document.getElementById("espacioMensaje");
					div.innerHTML = '<div id="loading"></div>';
					$('#selectedItems').val('');
		            $.ajax({
			            url: "<?php echo $this->setUrl($queryString);?>",
			            type:"post",
			            data:data,
			            dataType: "html",
			            success: function(jresp){
			                
				  			var div = document.getElementById("espacioTrabajo");
				  			div.innerHTML = jresp;
		                    
				  			var div = document.getElementById("espacioMensaje");
				  			
				  			 div.innerHTML="";
				  			
					       }
			        });
		
		            }
		
			    
				<?php
				echo "\n";
		        echo "</script>\n";
			
				
			}

			public function duplicar($queryString = "funcion=duplicar"){
				echo "<script type='text/javascript'>\n";
				?>
						       
						    
		function duplicarElemento(){
        	var data =  $( "#objetosFormulario" ).serialize()+"&"+$( "#identificacionFormulario" ).serialize();
        	data += "&"+ $( "#seleccionFormulario" ).serialize();

        	var div = document.getElementById("espacioMensaje");
			div.innerHTML = '<div id="loading"></div>';
			
        	
			if($('#formularioConsulta').length>0) $('#formularioConsulta')[0].reset();
        	
        	$.ajax({
	            url: "<?php echo $this->setUrl($queryString);?>",
	            type:"post",
	            data:data,
	            dataType: "html",
	            success: function(jresp){
	                
	            	getFormularioConsulta(true, jresp);
	            	
			       }
	        });
        	
           }
							
						    
							<?php
							echo "\n";
					        echo "</script>\n";
						
							
						}	
					
			

	
						
	public function formularioConsulta($queryString = "funcion=consultar"){
	  	echo "<script type='text/javascript'>\n";
		?>
	    function cambiarVisibilidadBusqueda(){
		$('#contenedorBuscador').toggle();
		$('#botones').toggle();
	}
	    
	    
	    function getFormularioConsulta(skip, mensaje){

	         listaIds =  [];
             listaNombres = [];
             listaAlias = [];
	    
        	
        	idObjeto = $('#idObjeto').val();
            
            var data =  $( "#objetosFormulario" ).serialize()+"&"+$( "#identificacionFormulario" ).serialize();
			if($( "#formularioConsulta" ).serialize().length>0&&!skip) data += "&"+ $( "#formularioConsulta" ).serialize() ;
			
            
            var div = document.getElementById("espacioMensaje");
			div.innerHTML = '<div id="loading"></div>';
			$('#selectedItems').val('');
            $.ajax({
	            url: "<?php echo $this->setUrl($queryString);?>",
	            type:"post",
	            data:data,
	            dataType: "html",
	            success: function(jresp){
	                
		  			var div = document.getElementById("espacioTrabajo");
		  			div.innerHTML = jresp;
                    tablaConsulta('selectedItems');
		  			var div = document.getElementById("espacioMensaje");
		  			if(mensaje&&mensaje.length>0){
			  			 div.innerHTML=mensaje;
			  			setTimeout(function() {
			  		        $("#divMensaje").hide('drop', {}, 500)
			  		    }, 20000);
			  	        goToByScroll($("#divMensaje").attr("id"));
		  			}
		  			else div.innerHTML="";
		  			var elemento= 'fecha_registro';
		  			activarRangoFecha(elemento);
		  			$("button").button().click(function(event) {
                		event.preventDefault();
                	});
			       }
	        });

            }

	    
		<?php
		echo "\n";
        echo "</script>\n";
	
		
	}
	
	public function cambiarListaObjeto()	{
		echo "<script type='text/javascript'>\n";
		?>
	
		function setObjeto(idObjeto,aliasObjeto){
	
	
	    	if(idObjeto!=0){
	    		$('#objetoId').val(idObjeto);
	    		$('#objetoSeleccionado').html('<span class="ui-button-text">'+aliasObjeto+'</span>');
	    		$('#selectedItems').val('');
	    		<?php echo $this->funcionInicio ?>
	    		//getFormularioConsulta(true);
	        }
	    	
	    }
    	<?php
		echo "\n";
        echo "</script>\n";
		
	}
		
	
	public function ready(){
		echo "<script type='text/javascript'>\n";
        echo "$(document).ready(function(){\n";
		
		
		if(is_array($this->operaciones)){
		   
		   foreach ($this->operaciones as $operacion) {
		  
                  if($operacion['cadena']==''&&$operacion['icono']=='') continue;
               
				  echo '  $( "#'.$operacion['nombre'].'" ).button({'."\n";
			      echo '  text: '.$operacion['text'].','."\n";
			      echo '  icons: {'."\n";
			      echo '  primary: "'.$operacion['icono'].'"'."\n";
			      echo '  }'."\n";
			      echo '  }).click(function() {'."\n";
				  echo '      if($("#objetoId").val()!=0){'."\n";
			      echo '         '.$operacion['click']."\n";
			      echo '  };});'."\n";
			
			
				
			}
		
		}
		
	?>
	
    $( "#objetoSeleccionado" )
    .button()
    .click(function() {
    	<?php echo $this->funcionInicio?>
    	//if($('#objetoId').val()!=0) 	getFormularioConsulta();
    })
    .next()
      .button({
        text: false,
        icons: {
          primary: "ui-icon-triangle-1-s"
        }
      })
      .click(function() {
        var menu = $( this ).parent().next().show().position({
          my: "left top",
          at: "left bottom",
          of: this
        });
        $( document ).one( "click", function() {
          menu.hide();
        });
        return false;
      })
      .parent()
        .buttonset()
        .next()
          .hide()
          .menu();
          
          //if($('#objetoId').val()!=0) 	getFormularioConsulta(true);
    <?php
		echo $this->funcionInicio;
		echo "})"."\n";
	
    
	
	
        
		
		
		echo "\n";
        echo "</script>\n";
		
		
	}
	
}


/*
function autocompletar(elemento){


            if($('#formularioCreacionEdicion').length>0&&elemento!='proceso') return false;;
        	
        	if(typeof listaIds[elemento]=='undefined'){

        	$( "#"+elemento+'Nombre' ).attr('disabled',true);
            	
        	listaIds[elemento] =  [];
        	listaNombres[elemento] = [];
        	listaAlias[elemento] = [];       	

        	var data =  $( "#objetosFormulario" ).serialize()+"&"+$( "#identificacionFormulario" ).serialize();
        	data +=  "&field="+elemento;

        	
        	
        	$.ajax({
	            url: "<?php echo $autocompletar;?>",
	            type:"post",
	            data:data,
	            dataType: "json",
	            success: function(jresp){
	            	 
	            	
		  			for(i=0;i<jresp.length;i++){
		  				listaIds[elemento].push( jresp[i].id);
		  				listaNombres[elemento].push( jresp[i].nombre);
		  				listaAlias[elemento].push( jresp[i].alias); 
		  			}  
			       }
	        });

        	
        	$( "#"+elemento+'Nombre' ).autocomplete({
        	      source: listaNombres[elemento]
        	    });
        	    
        	$( "#"+elemento+'Nombre' ).change(function() {
        		   
        	    	var indice = listaNombres[elemento].indexOf(this.value);
        	    	if(typeof listaIds[elemento][indice] == 'undefined') $( "#"+elemento).val($( "#"+elemento+'Nombre' ).val());
                	else $( "#"+elemento).val(listaIds[elemento][indice]);
                	$( "#"+elemento).trigger("change");
        	    	
            	    });

        	$( "#"+elemento+'Nombre' ).attr('disabled',false);
        	}

        	
        	//$( "#"+elemento+'Nombre' ).trigger("change");
        	var indice = listaNombres[elemento].indexOf($( "#"+elemento+'Nombre' ).val());
        	if(typeof listaIds[elemento][indice] == 'undefined') $( "#"+elemento).val($( "#"+elemento+'Nombre' ).val());
        	else $( "#"+elemento).val(listaIds[elemento][indice]);
        	
        	
        	
        	return 0;
        	
        	
           }

 * */
 