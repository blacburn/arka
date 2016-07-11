<?php
/**
 *
 * Los datos del bloque se encuentran en el arreglo $esteBloque.
 */
// Variables

?>
<script type='text/javascript'>




    $(function() {
        $('#tablaTitulos').ready(function() {

            var table =$('#tablaTitulos').dataTable( {
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
               
                
                "aoColumns" : [
                    { sWidth: "10%" },
                    { sWidth: "30%" },
                    { sWidth: "10%" },
                    { sWidth: "10%" },
                    { sWidth: "10%" },
                    { sWidth: "10%" },
                    { sWidth: "10%" },
                    { sWidth: "5%" },
                    { sWidth: "5%" }
                ]
               
             
            });
            
     
                  
        });

    });




</script>
