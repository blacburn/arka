<?php
/**
 *
 * Los datos del bloque se encuentran en el arreglo $esteBloque.
 */
// URL base
$url = $this->miConfigurador->getVariableConfiguracion("host");
$url .= $this->miConfigurador->getVariableConfiguracion("site");
$url .= "/index.php?";

// Variables
$cadenaACodificar16 = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificar16 .= "&procesarAjax=true";
$cadenaACodificar16 .= "&action=index.php";
$cadenaACodificar16 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar16 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar16 .= $cadenaACodificar16 . "&funcion=consultarDependencia";
$cadenaACodificar16 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$cadena16 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar16, $enlace);

// URL definitiva
$urlFinal16 = $url . $cadena16;

// Variables
$cadenaACodificar4 = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificar4 .= "&procesarAjax=true";
$cadenaACodificar4 .= "&action=index.php";
$cadenaACodificar4 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar4 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar4 .= $cadenaACodificar4 . "&funcion=consultarUbicacion";
$cadenaACodificar4 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$cadena4 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar4, $enlace);

// URL definitiva
$urlFinal4 = $url . $cadena4;

// Variables
$cadenaACodificarPlaca = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificarPlaca .= "&procesarAjax=true";
$cadenaACodificarPlaca .= "&action=index.php";
$cadenaACodificarPlaca .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificarPlaca .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificarPlaca .= "&funcion=consultaPlaca";
$cadenaACodificarPlaca .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$cadena = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificarPlaca, $enlace);

// URL definitiva
$urlFinalPlaca = $url . $cadena;

// Variables
$cadenaACodificar18 = "pagina=" . $this->miConfigurador->getVariableConfiguracion("pagina");
$cadenaACodificar18 .= "&procesarAjax=true";
$cadenaACodificar18 .= "&action=index.php";
$cadenaACodificar18 .= "&bloqueNombre=" . $esteBloque ["nombre"];
$cadenaACodificar18 .= "&bloqueGrupo=" . $esteBloque ["grupo"];
$cadenaACodificar18 .= $cadenaACodificar18 . "&funcion=consultarSede";
$cadenaACodificar18 .= "&tiempo=" . $_REQUEST ['tiempo'];

// Codificar las variables
$enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
$cadena18 = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($cadenaACodificar18, $enlace);

// URL definitiva
$urlFinal18 = $url . $cadena18;
?>


<script type='text/javascript'>



    function marcar(obj) {
        elem = obj.elements;
        for (i = 0; i < elem.length; i++)
            if (elem[i].type == "checkbox")
                elem[i].checked = true;
    }

    function desmarcar(obj) {
        elem = obj.elements;
        for (i = 0; i < elem.length; i++)
            if (elem[i].type == "checkbox")
                elem[i].checked = false;
    }



    function consultarDependencia(elem, request, response) {
        $.ajax({
            url: "<?php echo $urlFinal16 ?>",
            dataType: "json",
            data: {valor: $("#<?php echo $this->campoSeguro('sede') ?>").val(), funcionario: $("#<?php echo $this->campoSeguro('responsable') ?>").val()},
            success: function (data) {


                if (data[0] != " ") {

                    $("#<?php echo $this->campoSeguro('dependencia') ?>").html('');
                    $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('dependencia') ?>");
                    $.each(data, function (indice, valor) {

                        $("<option value='" + data[ indice ].ESF_CODIGO_DEP + "'>" + data[ indice ].ESF_DEP_ENCARGADA + "</option>").appendTo("#<?php echo $this->campoSeguro('dependencia') ?>");

                    });

                    $("#<?php echo $this->campoSeguro('dependencia') ?>").removeAttr('disabled');

                    $('#<?php echo $this->campoSeguro('dependencia') ?>').width(270);
                    $("#<?php echo $this->campoSeguro('dependencia') ?>").select2();

                    $("#<?php echo $this->campoSeguro('ubicacion') ?>").val(null);
                    $('#<?php echo $this->campoSeguro('ubicacion') ?>').width(270);
                    $("#<?php echo $this->campoSeguro('ubicacion') ?>").select2();
                    $("#<?php echo $this->campoSeguro('ubicacion') ?>").attr('disabled', '');



                }



            }

        });
    }
    ;



    function consultarSede(elem, request, response) {
        $.ajax({
            url: "<?php echo $urlFinal18 ?>",
            dataType: "json",
            data: {funcionario: $("#<?php echo $this->campoSeguro('responsable') ?>").val()},
            success: function (data) {


                if (data[0] != " ") {

                    $("#<?php echo $this->campoSeguro('sede') ?>").html('');
                    $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('sede') ?>");
                    $.each(data, function (indice, valor) {

                        $("<option value='" + data[ indice ].ESF_ID_SEDE + "'>" + data[ indice ].ESF_SEDE + "</option>").appendTo("#<?php echo $this->campoSeguro('sede') ?>");

                    });

                    $("#<?php echo $this->campoSeguro('sede') ?>").removeAttr('disabled');

                    $('#<?php echo $this->campoSeguro('sede') ?>').width(270);
                    $("#<?php echo $this->campoSeguro('sede') ?>").select2();

                    $("#<?php echo $this->campoSeguro('ubicacion') ?>").val(null);
                    $('#<?php echo $this->campoSeguro('ubicacion') ?>').width(270);
                    $("#<?php echo $this->campoSeguro('ubicacion') ?>").attr('disabled', '');
                    $("#<?php echo $this->campoSeguro('ubicacion') ?>").select2();

                    $("#<?php echo $this->campoSeguro('dependencia') ?>").val(null);
                    $('#<?php echo $this->campoSeguro('dependencia') ?>').width(270);
                    $("#<?php echo $this->campoSeguro('dependencia') ?>").attr('disabled', '');
                    $("#<?php echo $this->campoSeguro('dependencia') ?>").select2();



                }



            }

        });
    }
    ;


    function consultarEspacio(elem, request, response) {
        $.ajax({
            url: "<?php echo $urlFinal4 ?>",
            dataType: "json",
            data: {valor: $("#<?php echo $this->campoSeguro('dependencia') ?>").val(),
                funcionario: $("#<?php echo $this->campoSeguro('responsable') ?>").val()},
            success: function (data) {



                if (data[0] != " ") {

                    $("#<?php echo $this->campoSeguro('ubicacion') ?>").html('');
                    $("<option value=''>Seleccione  ....</option>").appendTo("#<?php echo $this->campoSeguro('ubicacion') ?>");
                    $.each(data, function (indice, valor) {

                        $("<option value='" + data[ indice ].ESF_ID_ESPACIO + "'>" + data[ indice ].ESF_NOMBRE_ESPACIO + "</option>").appendTo("#<?php echo $this->campoSeguro('ubicacion') ?>");

                    });

                    $("#<?php echo $this->campoSeguro('ubicacion') ?>").removeAttr('disabled');

                    $('#<?php echo $this->campoSeguro('ubicacion') ?>').width(270);
                    $("#<?php echo $this->campoSeguro('ubicacion') ?>").select2();



                }


            }

        });
    }
    ;

    $(function () {


        $('#<?php echo $this->campoSeguro('responsable') ?>').width(600);
        $("#<?php echo $this->campoSeguro('responsable') ?>").select2();
        $('#<?php echo $this->campoSeguro('responsable_reci') ?>').width(600);
        $("#<?php echo $this->campoSeguro('responsable_reci') ?>").select2();

        $("#<?php echo $this->campoSeguro('responsable') ?>").change(function () {

            if ($("#<?php echo $this->campoSeguro('responsable') ?>").val() != '') {
                consultarSede();
            } else {
            }
        });


        $('#<?php echo $this->campoSeguro('sede') ?>').width(200);
        $("#<?php echo $this->campoSeguro('sede') ?>").select2();
        $('#<?php echo $this->campoSeguro('dependencia') ?>').width(200);
        $("#<?php echo $this->campoSeguro('dependencia') ?>").select2();


        $("#<?php echo $this->campoSeguro('selec_placa') ?>").blur(function () {
            if ($("#<?php echo $this->campoSeguro('placa') ?>").val() == '') {
                $("#<?php echo $this->campoSeguro('selec_placa') ?>").val('Placa No Registrada');
                $("#<?php echo $this->campoSeguro('placa') ?>").val('');
            }
        });


        $("#<?php echo $this->campoSeguro('selec_placa') ?>").autocomplete({
            minChars: 3,
            serviceUrl: '<?php echo $urlFinalPlaca; ?>',
            onSelect: function (suggestion) {
                if (suggestion != 'null') {
                    $("#<?php echo $this->campoSeguro('placa') ?>").val(suggestion.data);
                    if ($("#<?php echo $this->campoSeguro('placa') ?>").val() != '') {
                        $("#<?php echo $this->campoSeguro('responsable') ?>").attr("class", "selectboxdiv  validate[ ]  select2-hidden-accessible");
                        $("#<?php echo $this->campoSeguro('sede') ?>").attr("class", "selectboxdiv  validate[ ]  select2-hidden-accessible");
                    } else {
                        $("#<?php echo $this->campoSeguro('responsable') ?>").attr("class", "selectboxdiv  validate[required]  select2-hidden-accessible");
                    }
                }
            }

        });



        $("#<?php echo $this->campoSeguro('selecc_registros') ?>").change(function () {
            if ($("#<?php echo $this->campoSeguro('selecc_registros') ?>").val() == 1) {
                marcar(this.form);
            } else {
                desmarcar(this.form);
            }
        });

        $("#<?php echo $this->campoSeguro('sede') ?>").change(function () {
            if ($("#<?php echo $this->campoSeguro('sede') ?>").val() != '') {
                consultarDependencia();
            } else {
                $("#<?php echo $this->campoSeguro('dependencia') ?>").select2();
                $("#<?php echo $this->campoSeguro('dependencia') ?>").attr('disabled', '');
                $("#<?php echo $this->campoSeguro('ubicacion') ?>").select2();
                $("#<?php echo $this->campoSeguro('ubicacion') ?>").attr('disabled', '');
            }

        });
        $("#<?php echo $this->campoSeguro('responsable') ?>").change(function () {
            if ($("#<?php echo $this->campoSeguro('responsable') ?>").val() != '') {
                $("#<?php echo $this->campoSeguro('sede') ?>").attr("class", "selectboxdiv  validate[ ]  select2-hidden-accessible");
            }
        });

        $("#<?php echo $this->campoSeguro('dependencia') ?>").change(function () {
            if ($("#<?php echo $this->campoSeguro('dependencia') ?>").val() != '') {
                consultarEspacio();
            } else {
                $("#<?php echo $this->campoSeguro('ubicacion') ?>").attr('disabled', '');
            }

        });



    });
</script>