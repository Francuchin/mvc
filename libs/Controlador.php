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
  function cargarVista(){
  $vista = get_class($this).'_Vista';
    $rutaVista = './mvc/'.get_class($this).'/'.$vista.'.php';
    if(file_exists($rutaVista)) {
      require_once $rutaVista;
      $this->vista = new $vista(); // cargar clase vista propia heredada de vista
    }else
      $this->vista = new Vista(); // cargar clase vista comun
    $this->vista->controlador = $this;
  }
  function cargarModelo($id=null){
    $modelo = get_class($this).'_Modelo';
    $rutaModelo = './mvc/'.get_class($this).'/'.$modelo.'.php';
    if(file_exists($rutaModelo)){
      require_once $rutaModelo;
      $this->modelo = new $modelo($id);
    }else $this->modelo = new Modelo($id);
    $this->modelo->nombreTabla = get_class($this);
    $this->modelo->id = $id;
    if(isset($id)){
      $datos = $this->modelo->getAtributo("*");
      foreach ($datos as $key => $value) {
        $this->{$key} = $value;
        $this->vista->data[$key] = $value;
      }
    }else{
      $this->listado = $this->modelo->getAtributo("*");
      $this->vista->data['listado'] = $this->listado;
    }
  }
  function getAtributo($att){
    return $this->modelo($att);
  }
  function llamarfuncion($nombre = "", $parametros = []){
    $function = new ReflectionMethod($this, $nombre);
    return $function->invokeArgs($this, $parametros);
  }
}
