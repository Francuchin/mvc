<?php
/**
 *
 */
class <vista_nombre> extends Vista{
  public function index(){
    return "{COMPONENTE:navbar}";
  }
  public function ver(){
    return "
    {COMPONENTE:navbar}
    {COMPONENTE:datosLugar}";
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
