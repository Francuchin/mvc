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
 public function index($id=null){
   if($id) {
     $this->setID($id);
     return $this->vista->show("ver");
   }
   return $this->vista->show("inicio");
 }
}
