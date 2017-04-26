<?php
class Clientes extends Controlador{
 public function __construct(){
  parent::__construct();
 }
 public function escribir($valor=null){
  $d = [["valor" => "uno"],["valor"=>"des"],["valor"=>"tros"]];
  $d = [["valor" => "uno"],["valor"=>"des"],["valor"=>"tros"]];
  $d = [["valor" => "uno"],["valor"=>"des"],["valor"=>"tros"]];
  $this->vista->set("nombre", $valor);
  $this->vista->set("controlador", get_class($this));
  $this->vista->set("prueba", "probando");
  $this->vista->set("lugares", $this->modelo->consulta("SELECT * from lugar"));
  $this->vista->set("numeros", $d);
  $this->vista->show("index");
  //$this->vista->json( $this->modelo->consulta("SELECT * from lugar") );
  //var_dump($this->modelo->consulta("SELECT * from lugar"));
 }
}
