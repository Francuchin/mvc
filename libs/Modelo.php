<?php
/**
*
*/
require_once 'config.php';
class Modelo
{
  /**
  *
  * __construct
  *
  */
  function __construct(){
    $this->DB_HOST= DB_HOST;
    $this->DB_USER= DB_USER;
    $this->DB_PASS= DB_PASS;
    $this->DB_NAME= DB_NAME;
    $this->mysqli = new mysqli($this->DB_HOST, $this->DB_USER, $this->DB_PASS, $this->DB_NAME);
    if (mysqli_connect_errno()) {
      printf("Connect failed: %s\n", mysqli_connect_error());
      exit();
    }
  }
  /**
  *
  * __destruct
  *
  */
  function __destruct(){
    $thread_id = $this->mysqli->thread_id;
    $this->mysqli->kill($thread_id);
    $this->mysqli->close();
  }
  /**
  * para consultas cosas mas complejas
  * @param String $consulta
  *
  * @return Array<String> $result / Error / Null
  */
  function consulta($consulta){
    $result = null;
    if ($stmt = $this->mysqli->prepare($consulta)) {
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
  * select
  * @param Array<String> $atributos
  * @param Array<String> $tablas
  * @param String $where
  * @param Array<> $whereValores
  *
  * @return Array<String> $result / Error
  */
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
}
