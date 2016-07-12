<?php
use inventarios\gestionElementos\registrarBajas\Sql;

$conexion = "inventarios";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

if ($_REQUEST ['funcion'] == 'consultarDependencia') {

    $arreglo = array(
        'valor' => $_REQUEST['valor'],
        'funcionario' => $_REQUEST['funcionario']
    );

    $cadenaSql = $this->sql->getCadenaSql('dependenciasConsultadas', $arreglo);

    $resultado = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");


    $resultado = json_encode($resultado);

    echo $resultado;
}
if ($_REQUEST ['funcion'] == 'consultarUbicacion') {

	$arreglo = array (
           'dependencia'=>$_REQUEST['valor'],
           'funcionario'=>$_REQUEST['funcionario'],
       );
	$cadenaSql = $this->sql->getCadenaSql ( 'UbicacionesConsultadas', $arreglo);
	
	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );


	$resultado = json_encode ( $resultado);

	echo $resultado;
}

if ($_REQUEST ['funcion'] == 'consultaPlaca') {
	
	$cadenaSql = $this->sql->getCadenaSql ( 'ConsultasPlacas', $_GET ['query'] );
	
	$resultadoItems = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
	
	foreach ( $resultadoItems as $key => $values ) {
		$keys = array (
				'value',
				'data' 
		);
		$resultado [$key] = array_intersect_key ( $resultadoItems [$key], array_flip ( $keys ) );
	}
	
	echo '{"suggestions":' . json_encode ( $resultado ) . '}';
}
if ($_REQUEST ['funcion'] == 'consultarSede') {


    $cadenaSql = $this->sql->getCadenaSql('sedesConsultadas', $_REQUEST['funcionario']);

    $resultado = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");


    $resultado = json_encode($resultado);

    echo $resultado;
}
?>

