<?php
/**
 *
 */
class Lugar_Vista extends Vista{
  public function index(){
    return "{COMPONENTE:navbar}";
  }
  public function ver(){
    return "
    {COMPONENTE:navbar}
    {COMPONENTE:Lugar}";
  }
  public function listado(){
    return '
    <div class="container">
      <div class="row">
        <div class="card-deck-wrapper">
          <div class="card-deck">
          {LOOP:listado}
          {COMPONENTE:Lugar}
          {ENDLOOP}
        </div>
      </div>
    </div>
  </div>
    ';
  }
  public function Lugar(){
    return '
    <div class="card" style="max-width:50%; margin-bottom: 1em;">
      <img class="card-img-top" src="https://github.com/FezVrasta/bootstrap-material-design/raw/master/demo/imgs/banner.jpg"
      style="max-width:100%; min-width: 25vw;">
      <div class="card-block">
        <a href="'.URL.'?c=lugar&p={id_Lugar}"><h4 class="card-title"> {nombre}</h4></a>
        <p class="card-text"><small class="text-muted"> {GET:created_at}</small></p>
      </div>
    </div>
    ';
  }
  public function inicio(){
    return "
    {COMPONENTE:navbar}
    {COMPONENTE:listado}
    {SCRIPT}
    'use strict'
    let cargar = () => Request({url:'http://munch.paap.cup.edu.uy/Turismo/?c=Recorrido&a=get&p=6'}).then(e=>console.log(e)).catch(e=>console.log(e))
    window.onload = cargar
    {ENDSCRIPT}";
  }
}

 ?>
