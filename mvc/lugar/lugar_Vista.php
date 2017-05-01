<?php
/**
 *
 */
class lugar_Vista extends Vista{
  public function index(){
    return "{COMPONENTE:navbar}";
  }
  public function ver(){
    return "
    {COMPONENTE:navbar}
    {COMPONENTE:datosLugar}";
  }
  public function datosLugar(){
    return '
    <div class="card" style="max-width:50%; margin-bottom: 1em;">
      <img class="card-img-top" src="https://github.com/FezVrasta/bootstrap-material-design/raw/master/demo/imgs/banner.jpg"
      style="max-width:100%; min-width: 25vw;">
      <div class="card-block">
        <a href="'.URL.'?c=lugar&p={GET:id}"><h4 class="card-title"> {GET:nombre}</h4></a>
        <p class="card-text">
        {GET:articulo}
        </p>
        <p class="card-text"><small class="text-muted">desarrollo en proceso</small></p>
      </div>
    </div>
    ';
  }
  public function listado(){
    $this->data['listado'] = $this->controlador->listado;
    return '
    <div class="container">
      <div class="row">
        <div class="card-deck-wrapper">
          <div class="card-deck">
          {LOOP:listado}
          {COMPONENTE:datosLugar}
          {ENDLOOP}
        </div>
      </div>
    </div>
  </div>
    ';
  }
  public function inicio(){
    return "
    {COMPONENTE:navbar}
    {COMPONENTE:listado}";
  }
}

 ?>
