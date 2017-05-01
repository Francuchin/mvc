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
     $this->cargarModelo($id);
     $this->vista->show("ver");
   }
   else $this->vista->show("inicio");
 }
}
