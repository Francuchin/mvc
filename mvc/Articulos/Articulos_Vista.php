<?php

/**
 *
 */
class Articulos_Vista extends Vista
{
  function navbar(){
    return '
    <nav class="navbar navbar-fixed-top navbar-light bg-faded">
      <a class="navbar-brand" href="#">Articulos</a>
      <ul class="nav navbar-nav">
        <li class="nav-item active">
          <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Features</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Pricing</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">About</a>
        </li>
      </ul>
      <form class="form-inline pull-xs-right">
        <input class="form-control" type="text" placeholder="Buscar">
      </form>
    </nav>
    <div style="height:55px"></div>';
  }
}
