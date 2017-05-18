<?php
/**
*
*/
require_once 'config.php';
class Modelo
{
  protected static $instancia;
  /**
  *
  * __construct
  *
  */
  protected function __construct(){
    $this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (mysqli_connect_errno()) {
      printf("Connect failed: %s\n", mysqli_connect_error());
      exit();
    }
  }
  protected static function getInstance(){
     if (!self::$instancia instanceof self){
        self::$instancia = new self;
     }
     return self::$instancia;
  }
  public static function setID($id){
    self::getInstance()->id = $id;
  }
  public static function setNombreTabla($nombreTabla){
    self::getInstance()->nombreTabla = $nombreTabla;
  }
  public static function getAtributo($att){
    $self = self::getInstance();
    if(is_array($att)){
      $consulta = "SELECT '";
      foreach ($att as $v) {
        $consulta.=$v."', '";
      }
      $consulta = substr($consulta, 0, -3);
      $consulta .=" FROM ".$self->nombreTabla;
      if(isset($self->id)) $consulta .= " WHERE id_".$self->nombreTabla."=".$self->id;
    }else $consulta = "SELECT ".$att." FROM ".$self->nombreTabla;
    if(isset($self->id)){
      $consulta .= " WHERE id_".$self->nombreTabla."=".$self->id;
      return $self->consulta($consulta)[0];
    }
    return $self->consulta($consulta);
  }
  /**
  *
  * __destruct
  *
  */
  public function __destruct(){
    $self = self::$instancia;
    $thread_id = $self->mysqli->thread_id;
    $self->mysqli->kill($thread_id);
    $self->mysqli->close();
  }
  /**
  * para consultas cosas mas complejas
  * @param String $consulta
  *
  * @return Array $result / Error / Null
  * @return $result = [ 0 => ['id' => 1, 'nombre' => 'el uno'], 1 => ['id' => 2, 'nombre' => 'el dos xD']]
  *
  */
  public static function consulta($consulta){
    $self = self::getInstance();
    $result = null;
    //echo "consulta - ".$consulta."\n";
    if ($stmt = $self->mysqli->prepare($consulta)) {
      $stmt->execute();
      $meta = $stmt->result_metadata();
      while ($field = $meta->fetch_field()) $params[] = &$row[$field->name];
      call_user_func_array(array($stmt, 'bind_result'), $params);
      while ($stmt->fetch()) {
        foreach($row as $key => $val) $c[$key] = $val;
        $result[] = $c;
      }
      $stmt->close();
    }
    return $result;
  }
  /**
  *
  *
  */
  public static function getEstructura(){
    $self = self::getInstance();
    return $self->consulta("SHOW columns FROM ".$self->nombreTabla.";");
  }
  public static function getListado(){
    $self = self::getInstance();
    return $self->consulta("SELECT * FROM ".$self->nombreTabla.";");
  }
  /*
  * select
  * @param Array<String> $atributos
  * @param Array<String> $tablas
  * @param String $where
  * @param Array<> $whereValores
  *
  * @return Array $result / Error
  * @return $result = [ 0 => ['id' => 1, 'nombre' => 'el uno'], 1 => ['id' => 2, 'nombre' => 'el dos xD']]
  function select($atriburos, $tablas, $where=null, $whereValores=null){
    $columnas = "";
    $from = "";
    $bindTipos = "";
    $result = null;
    foreach ($atriburos as $value) $columnas.=ucwords(strtolower($value)).',';
    $columnas = substr($columnas, 0, -1);
    foreach ($tablas as $value) $from.=ucwords(strtolower($value)).',';
    $from = substr($from, 0, -1);
    if(isset($whereValores))
    foreach ($whereValores as $value)
    $bindTipos.= (gettype($value) == "integer" || gettype($value) == "boolean")? 'i' : 's';
    $consulta = "SELECT ".$columnas." FROM ".$from;
    if(isset($where) && isset($whereValores)) $consulta .= " WHERE ".$where;
    if ($stmt = $this->mysqli->prepare($consulta)) {
      $stmt->bind_param($bindTipos, $whereValores);
      $stmt->execute();
      $stmt->bind_result($result);
      $stmt->fetch();
      $stmt->close();
    }
    return $result;
  }
  */
}
