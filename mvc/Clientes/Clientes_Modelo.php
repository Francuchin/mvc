<?php
class Clientes_Modelo extends Modelo{
 public function __construct(){
  parent::__construct();
 }
 function consulta($tecto){
   echo "consultando desde el modelo de cliente antes que el modelo por defecto<br>";
   return parent::consulta($tecto);
 }
}
