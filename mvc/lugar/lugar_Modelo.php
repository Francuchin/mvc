<?php
class Lugar_Modelo extends Modelo{
  public static function getInstance(){
    if (!self::$instancia instanceof self) self::$instancia = new self;
    return self::$instancia;
  }
}
