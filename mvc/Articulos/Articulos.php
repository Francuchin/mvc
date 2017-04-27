<?php
/*
*
* controlador modulo Articulos
*
*/

class Articulos extends Controlador{
 public function __construct(){
  parent::__construct();
 }
 public function index($valor=null){
  $d = [["valor" => "uno"],["valor"=>"des"],["valor"=>"tros"]];
  $this->vista->set("nombre", $valor);
  $this->vista->set("controlador", get_class($this));
  $this->vista->set("prueba", "probando");
  $this->vista->set("numeros", $d);
  $this->vista->show("index");
 }
}
