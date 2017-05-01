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
    {COMPONENTE:lugar}";
  }
  public function listado(){
    $this->data['listado'] = $this->controlador->listado;
    return '
    <div class="container">
      <div class="row">
        <div class="card-deck-wrapper">
          <div class="card-deck">
          {LOOP:listado}
          {COMPONENTE:lugar}
          {ENDLOOP}
        </div>
      </div>
    </div>
  </div>
    ';
  }
  public function lugar(){
    return '
    <div class="card" style="max-width:50%; margin-bottom: 1em;">
      <img class="card-img-top" src="https://github.com/FezVrasta/bootstrap-material-design/raw/master/demo/imgs/banner.jpg"
      style="max-width:100%; min-width: 25vw;">
      <div class="card-block">
        <a href="'.URL.'?c=lugar&p={GET:id}"><h4 class="card-title"> {GET:nombre}</h4></a>
        <p class="card-text"><small class="text-muted"> {GET:created_at}</small></p>
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
