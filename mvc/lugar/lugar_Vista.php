<?php
/**
 *
 */
class Lugar_Vista extends Vista{
  public function __construct(){
    parent::__construct();
    $this->items_navbar = [ // si siempre es igual, podria modificarse en el contructor del padre (Vista)
      "Lugar"=>"Lugar",
      "wea"=>[
        "wea1"=>"xd",
        "fome2"=>"lel"
      ]
    ];
  }
  public function index(){
    return "{COMPONENTE:navbar}";
  }
  public function ver(){
    return '
    {COMPONENTE:navbar}
    {COMPONENTE:edit}
    ';
  }
  public function edit_(){
    return '
    <form role="form" id="register-form" autocomplete="off">
        <div class="form-header">
          <h3 class="form-title"><i class="fa fa-user"></i> Sign Up</h3>
          <div class="pull-right">
              <h3 class="form-title"><span class="glyphicon glyphicon-pencil"></span></h3>
          </div>
        </div>
        <div class="form-body">
           <div class="form-group">
              <div class="input-group">
              <div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
              <input name="name" type="text" class="form-control" placeholder="Username">
              </div>
              <span class="help-block" id="error"></span>
             </div>
             <div class="form-group">
              <div class="input-group">
              <div class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></div>
              <input name="email" type="text" class="form-control" placeholder="Email">
              </div>
              <span class="help-block" id="error"></span>
             </div>
             <div class="row">
             <div class="form-group  col-lg-6">
              <div class="input-group">
              <div class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></div>
              <input name="email" type="text" class="form-control" placeholder="Email">
              </div>
              <span class="help-block" id="error"></span>
             </div>
             <div class="form-group  col-lg-6">
              <div class="input-group">
              <div class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></div>
              <input name="email" type="text" class="form-control" placeholder="Email">
              </div>
              <span class="help-block" id="error"></span>
             </div>
            </div>
           </div>
           <div class="form-footer">
            <button type="submit" class="btn btn-info">
            <span class="glyphicon glyphicon-log-in"></span> Sign Me Up !
            </button>
           </div>
         </form>
           ';
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
