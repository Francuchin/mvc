<?php
/**
 *
 */
class Clientes_Vista extends Vista{
  public function carta(){
    return '
    <div class="card" style="margin-bottom: 1em;">
      <img class="card-img-top" src="https://github.com/FezVrasta/bootstrap-material-design/raw/master/demo/imgs/banner.jpg"
      style="max-width:100%; min-width: 25vw;">
      <div class="card-block">
        <h4 class="card-title">{nombre}</h4>
        <p class="card-text">
        {articulo}
        </p>
        <p class="card-text"><small class="text-muted">desarrollo en proceso</small></p>
      </div>
    </div>
    ';
  }
  public function container(){
    return '
    <div class="container">
      <div class="row">
        <div class="card-deck-wrapper">
          <div class="card-deck">
            {LOOP:lugares}
              {COMPONENTE:carta}
            {ENDLOOP}
          </div>
        </div>
      </div>
    </div>
';
  }
  public function test(){
    return "{COMPONENTE:navbar}{COMPONENTE:container}";
  }
}

 ?>
