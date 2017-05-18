<?php
class <modelo_nombre> extends Modelo{
  public static function getInstance(){
    if (!self::$instancia instanceof self) self::$instancia = new self;
    return self::$instancia;
  }
}
