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
  function cargarModelo(){
    $modelo = get_class($this).'_Modelo';
    $rutaModelo = './mvc/'.get_class($this).'/'.$modelo.'.php';
    if(file_exists($rutaModelo)){
      require_once $rutaModelo;
      $this->modelo = new $modelo();
    }else $this->modelo = new Modelo();
  }
  function llamarfuncion($nombre = "", $parametros = []){
    $function = new ReflectionMethod($this, $nombre);
    return $function->invokeArgs($this, $parametros);
  }
}
