<?php
/**
*
*/
class Controlador
{
  function __construct(){
    $this->cargarVista();
    $this->cargarModelo();
  }
  private function cargarVista(){
  $vista = get_class($this).'_Vista';
    $rutaVista = './mvc/'.get_class($this).'/'.$vista.'.php';
    if(file_exists($rutaVista)) {
      require_once $rutaVista;
      $this->vista = new $vista(); // cargar clase vista propia heredada de vista
    }else
      $this->vista = new Vista(); // cargar clase vista comun
    $this->vista->controlador = $this;
  }
  protected function setID($id){
    $this->modelo->setID($id);
    $datos = $this->modelo->getAtributo("*");
    if(isset($datos))
    foreach ($datos as $key => $value) {
      $this->{$key} = $value;
      $this->vista->data[$key] = $value;
    }
  }
  protected function cargarModelo(){
    $modelo = get_class($this).'_Modelo';
    $rutaModelo = './mvc/'.get_class($this).'/'.$modelo.'.php';
    if(file_exists($rutaModelo)){
      require_once $rutaModelo;
      $this->modelo = $modelo::getInstance();
    }else $this->modelo = Modelo::getInstance();
    $this->modelo->setNombreTabla(get_class($this));
    $this->listado = $this->modelo->getListado();
    $this->vista->data['listado'] = $this->listado;
    $this->estructura = $this->modelo->getEstructura();
    $this->vista->data['estructura'] = $this->estructura;
    //var_dump( $this->estructura);
  }
  function getAtributo($att){
    return $this->modelo($att);
  }
  function llamarfuncion($nombre = "", $parametros = []){
    $function = new ReflectionMethod($this, $nombre);
    return $function->invokeArgs($this, $parametros);
  }
}
