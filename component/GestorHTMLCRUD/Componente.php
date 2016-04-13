<?php

namespace component\GestoHTMLCRUD;


include_once ("core/manager/Configurador.class.php");

require_once ('component/Component.class.php');

use component\Component as Component;

//VISTA
require_once ('component/GestorHTMLCRUD/Vista/Consultar.class.php');
use component\GestoHTMLCRUD\Vista\Consultar as Consultar;

require_once ('component/GestorHTMLCRUD/Vista/Principal.class.php');
use component\GestorHTMLCRUD\Vista\Principal as Principal;

require_once ('component/GestorHTMLCRUD/Vista/Ver.class.php');
use component\GestorHTMLCRUD\Vista\Ver as Ver;

require_once ('component/GestorHTMLCRUD/Vista/Crear.class.php');
use component\GestorHTMLCRUD\Vista\Crear as Crear;

require_once ('component/GestorHTMLCRUD/Vista/Editar.class.php');
use component\GestorHTMLCRUD\Vista\Editar as Editar;

//CONTROLADOR
require_once ('component/GestorHTMLCRUD/Clase/Autocompletar.class.php');
use component\GestoHTMLCRUD\Clase\Autocompletar as Autocompletar;

require_once ('component/GestorHTMLCRUD/Clase/Duplicar.class.php');
use component\GestoHTMLCRUD\Clase\Duplicar as Duplicar;

require_once ('component/GestorHTMLCRUD/Clase/CambiarEstado.class.php');
use component\GestoHTMLCRUD\Clase\CambiarEstado as CambiarEstado;

require_once ('component/GestorHTMLCRUD/Clase/Eliminar.class.php');
use component\GestoHTMLCRUD\Clase\Eliminar as Eliminar;

require_once ('component/GestorHTMLCRUD/Clase/GuardarDatos.class.php');
use component\GestoHTMLCRUD\Clase\GuardarDatos as GuardarDatos;


class Componente extends Component  {
	

	private $consultarForm;
	private $idElemento;
	private $principal;
	private $autocompletar;
	private $ver;
	private $duplicar;
	private $cambiarEstado;
	private $eliminar;
	private $crear;
	private $editar;
	private $guardarDatos;
	
	
	// El componente actua como Fachada
	
	/**
	 * un objeto de la clase GestorUsuarios
	 */
	public function __construct($lenguaje, $idElemento = '') {
		

         $this->consultarForm =  new Consultar($lenguaje, $idElemento);
		 $this->principal = new Principal($lenguaje);
		 $this->ver = new \component\GestoHTMLCRUD\Vista\Ver($lenguaje);
		 $this->crear = new Crear($lenguaje);
		 $this->editar = new Editar($lenguaje);
		 $this->duplicar = new \component\GestoHTMLCRUD\Clase\Duplicar($lenguaje);
		 $this->autocompletar =  new \component\GestoHTMLCRUD\Clase\Autocompletar($lenguaje);
		 $this->cambiarEstado =  new \component\GestoHTMLCRUD\Clase\CambiarEstado($lenguaje);
		 $this->eliminar =  new \component\GestoHTMLCRUD\Clase\Eliminar($lenguaje);
		 $this->guardarDatos =  new \component\GestoHTMLCRUD\Clase\GuardarDatos($lenguaje);
	}
	
	public function iniciarPrincipal()	{
		$this->principal->formulario();
	}
	
	public function setOperaciones($operaciones){
		$this->principal->setOperaciones($operaciones);
	}
	
	public function setObjetos($objetos){
		$this->principal->setObjetos($objetos);
	}
	
	public function setFuncionInicio($funcion){
		$this->principal->setFuncionInicio($funcion);
	}
	
	public function setBloque($nombre , $grupo = ''){
		$this->principal->setBloque($nombre , $grupo);
	}
	
	public function setQueryStringConsulta($value){
		$this->principal->setQueryStringConsulta($value);
	}
	
	public function setLenguajeConsultar($lenguaje){
		$this->consultarForm->setLenguaje($lenguaje);
	}
	
	public function setObjetoIdConsultar($id)	{
		$this->consultarForm->setObjetoId($id);
	}
	
	public function consultar()	{
		$this->consultarForm->formulario();
	}
	
	public function setQueryStringAutocompletar($value){
		$this->principal->setQueryStringAutocompletar($value);
	}
	
	public function setObjetoIdAutocompletar($id)	{
		$this->autocompletar->setObjetoId($id);
	}
	
	public function autocompletar($valor = '')	{
		$this->autocompletar->autocompletar($valor);
	}
	
	public function setLenguajeVer($lenguaje){
		$this->ver->setLenguaje($lenguaje);
	}
	
	public function setObjetoIdVer($id)	{
		$this->ver->setObjetoId($id);
	}
	
	public function ver($valor = '')	{
		$this->ver->ver($valor );
	}
	
	public function addTablasPasoVer($valor = '')	{
		$this->ver->addTablasPaso($valor );
	}
	
	public function setLenguajeDuplicar($lenguaje){
		$this->duplicar->setLenguaje($lenguaje);
	}
	
	public function setObjetoIdDuplicar($id)	{
		$this->duplicar->setObjetoId($id);
	}
	
	public function duplicar($valor = '')	{
		$this->duplicar->duplicar($valor );
	}
	
	public function setLenguajeCambiarEstado($lenguaje){
		$this->cambiarEstado->setLenguaje($lenguaje);
	}
	
	public function setObjetoIdCambiarEstado($id)	{
		$this->cambiarEstado->setObjetoId($id);
	}
	
	public function cambiarEstado($valor = '')	{
		$this->cambiarEstado->cambiarEstado($valor );
	}
	
	public function setLenguajeEliminar($lenguaje){
		$this->eliminar->setLenguaje($lenguaje);
	}
	
	public function setObjetoIdEliminar($id)	{
		$this->eliminar->setObjetoId($id);
	}
	
	public function eliminar($valor = '')	{
		$this->eliminar->eliminar($valor );
	}
	
	public function setLenguajeCrear($lenguaje){
		$this->crear->setLenguaje($lenguaje);
	}
	
	public function setObjetoIdCrear($id)	{
		$this->crear->setObjetoId($id);
	}
	
	public function crear($valor = '')	{
		$this->crear->crear($valor );
	}
	
	public function addTablasPasoCrear($valor = '')	{
		$this->crear->addTablasPaso($valor );
	}
	
	public function setLenguajeEditar($lenguaje){
		$this->editar->setLenguaje($lenguaje);
	}
	
	public function setObjetoIdEditar($id)	{
		$this->editar->setObjetoId($id);
	}
	
	public function editar($valor = '')	{
		$this->editar->editar($valor );
	}
	
	public function addTablasPasoEditar($valor = ''){
		$this->editar->addTablasPaso($valor );
	}
	
	public function setLenguajeGuardarDatos($lenguaje){
		$this->guardarDatos->setLenguaje($lenguaje);
	}
	
	public function setObjetoIdGuardarDatos($id)	{
		$this->guardarDatos->setObjetoId($id);
	}
	
	public function guardarDatos($valor = '')	{
		$this->guardarDatos->guardarDatos($valor );
	}
	
	public function addTablasPasoGuardarDatos($valor = '')	{
		$this->guardarDatos->addTablasPaso($valor );
	}
		
	
}


