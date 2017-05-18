<?php

/**
*
*
*
**/
class __T{
  function __construct($e){
    $this->tipo = $e;
  }
}
class mysqlTipo{
  private static $i;
  private static $TIPOS = array(
    ENTERO      => [
      TIPO => 'INT(11)'],
    ENTEROLARGO => [
      TIPO => 'BIGINT(20)'],
    DECIMAL     => [
      TIPO => 'DECIMAL(10,0)'],
    DOBLE       => [
      TIPO => 'DOUBLE'],
    TEXTOCORTO  => [
      TIPO => 'TINYTEXT'],
    TEXTOMEDIO  => [
      TIPO => 'MEDIUMTEXT'],
    TEXTO       => [
      TIPO => 'TEXT'],
    TEXTOLARGO  => [
      TIPO => 'LONGTEXT'],
    FECHA       => [
      TIPO => 'DATE'],
    HORA        => [
      TIPO => 'TIME'],
    TIEMPO      => [
      TIPO => 'DATETIME'],
    MOMENTO     => [
      TIPO => 'TIMESTAMP'],
  );
  private function __construct(){
  }
  public static function getInstance(){
    if (!self::$i instanceof self)
    self::$i = new self;
    return self::$i;
  }
  public static function getData($id=ENTERO){
    self::$i = mysqlTipo::getInstance();
    foreach (self::$TIPOS as $key => $value) {
      if($key==$id) return $value;
    }
    return self::$TIPOS['ENTERO'];
  }
  public static function getDataByTipo($tipo = 'INT(11)'){
    self::$i = mysqlTipo::getInstance();
    foreach (self::$TIPOS as $key => $value) {
      if($value['TIPO'] == strtoupper($tipo)){
        $value['CLAVE'] = $key;
        return $value;
      }
    }
  }
}
