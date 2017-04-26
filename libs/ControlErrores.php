<?php

/**
 *
 */
class ControlErrores extends Controlador{

  function __construct()
  {
    # code...
  }
  public function faltaControlador(){
    echo "falta Controlador";
  }
  public function faltaMetodo(){
    echo "falta Accion";
  }
  public function faltaVista(){
    echo "falta Vista";
  }
}
