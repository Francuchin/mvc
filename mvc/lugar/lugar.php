<?php
/*
*
* controlador modulo lugar
*
*/

class lugar extends Controlador{
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
