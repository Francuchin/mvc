<?php
/**
 *
 */
class Clientes_Vista extends Vista
{
  public function carta()
  {
    return '
    <div class="card" style="margin-bottom: 1em;">
      <img class="card-img-top" src="https://github.com/FezVrasta/bootstrap-material-design/raw/master/demo/imgs/banner.jpg"
      style="max-width:100%; min-width: 25vw;">
      <div class="card-block">
        <h4 class="card-title">{nombre}</h4>
        <p class="card-text">{articulo}</p>
        <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
      </div>
    </div>';
  }
}

 ?>
