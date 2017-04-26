<?php
/*
*
* controlador modulo <controlador_nombre>
*
*/

class <controlador_nombre> extends Controlador{
 public function __construct(){
  parent::__construct();
 }
 public function escribir($valor=null){
  $d = [["valor" => "uno"],["valor"=>"des"],["valor"=>"tros"]];
  $this->vista->set("nombre", $valor);
  $this->vista->set("controlador", get_class($this));
  $this->vista->set("prueba", "probando");
  $this->vista->set("numeros", $d);
  $this->vista->show("index");
 }
}
