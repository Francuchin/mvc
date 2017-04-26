<?php
// editado EEMAC
header('Access-Control-Allow-Origin: *');
// Notificar solamente errores de ejecución
 error_reporting(E_ERROR | E_WARNING | E_PARSE);
require __DIR__ . '/vendor/autoload.php';
require_once 'config.php';
// -->?c=controlador&a=accion&p=parametro1:/:parametro2
if(!isset($_GET["c"]) && isset($_GET["p"])) $_GET["c"] = CONTROLADOR_X_DEFECTO;
$controller = (isset($_GET["c"]))? ucfirst(strtolower($_GET["c"])) : CONTROLADOR_X_DEFECTO;
$method =  (isset($_GET["a"]))? ucfirst(strtolower($_GET["a"])) : ACCION_X_DEFECTO;
if(isset($_GET["p"])){
  $p = $_GET["p"];
  $p = explode(":/:", $p);
}
$params = [];
if(isset($p))
  if($p != ''){
    $x = 0;
    while(true){
      if(!isset($p[$x]) || $p[$x] == '') break;
      $params[$x] = $p[$x];
      $x++;
    }
  }
spl_autoload_register(function($class){
  if(file_exists(LIBS.$class.".php")) require LIBS.$class.".php";
});
$error = new ControlErrores();
$path = './mvc/'.$controller.'/'.$controller.'.php';
if(!file_exists($path)) return $error->faltaControlador();
require $path;
$controller = new $controller();
if(!isset($method)) return $controller->index();
$method = strtolower($method);
if(!method_exists($controller, $method)){
  $viewPath = './views/'.get_class($controller).'/'.$method.'.php';
  if(!file_exists($viewPath)) return $error->faltaMetodo();
  return $controller->view->render($controller,$method);
}
$check = new ReflectionMethod(get_class($controller), $method);
if(!$check->isPublic()) return $error->metodoPrivado();
if(isset($params[0])) return $controller->llamarfuncion($method, $params);
return $controller->{$method}();
