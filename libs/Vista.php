<?php
/**
*
*/
class Vista {
  function __construct(){
    $this->data = [];
    $this->stack = [];
    $this->estilos = [];
    $this->scripts = [];
    $this->vistaPadre = null;
    $this->titulo = null;
  }
  function json($data=null){
    if(isset($data)) $this->data = $data;
    header('Content-Type: application/json');
    echo json_encode( $this->data );
  }
  function arrayToString($res){
    if(!is_array($res)) {
      return $res;
    }else{
      $aux = "[";
      foreach ($res as $key => $value) {
        $aux.="'".$key."'=>".$this->arrayToString($value).",";
      }
      $aux = substr($aux, 0, -1);
      $aux .= "]";
      return $aux;
    }
  }
  function showVariable($name) {
    if (isset($this->stack[$name])) {
      echo $this->arrayToString($this->stack[$name]);
    }else if (isset($this->data[$name])) {
      echo  $this->arrayToString($this->data[$name]);
    } else {
      echo '{' . $name . '}';
    }
  }
  function getVariable($name){
    if (isset($this->stack[$name])) {
      return $this->stack[$name];
    }else if (isset($this->data[$name])) {
      return $this->data[$name];
    } else {
      return 'null';
    }
  }
  function buscarVariableEnRecurso($recurso, $variable){
    $aux = $recurso;
    foreach ($variable as $key){
      if (isset($aux[$key]) || isset($aux[intval($key)]))
      $aux = isset($aux[$key]) ? $aux[$key] : $aux[intval($key)];
      else return null;
    }
    return $aux;
  }
  function buscarVariableEnControlador($variable){
    $aux = $this->controlador;
    foreach ($variable as $key){
      if (isset($aux->$key))
      $aux = $aux->$key;
      else return null;
    }
    return $aux;
  }
  function getVariable2($index){
    $aux = $this->buscarVariableEnRecurso($this->stack, $index);
    if (!isset($aux)) $aux = $this->buscarVariableEnRecurso($this->data, $index);
    if (!isset($aux)) $aux = $this->buscarVariableEnControlador($index);
    echo $aux;
  }
  function set($nombre, $valor){
    $this->data[$nombre] = $valor;
  }
  function wrap($element){
    foreach ($element as $k => $v) {
      $this->stack[$k] = $v;
    }
  }
  function unwrap(){
    $this->stack = [];
  }
  function traducirDatos($salida){
    return preg_replace_callback('~\{GET:([^\r\n}]+)\}~', function($m) {
      return $this->getVariable2(preg_split("/\|/",$m[1]));
    }, $salida);
  }
  function traducirCondiciones($salida){ //faltan agregas mas expresiones, ej: tamaño
    return preg_replace_callback('~\{ISSET:([^\r\n}]+)\}~', function($m) {
      return 'isset($this->data[\''.$m[1].'\'])';
    }, $salida);
  }
  function getEstilos($salida){
    return preg_replace_callback('~\{ESTILO:([^\r\n}]+)\}~', function($m) {
      if(!in_array( $m[1], $this->estilos)){
        array_push($this->estilos, $m[1]);
      }
    }, $salida);
  }
  function getScripts($salida){
    return preg_replace_callback('~\{SCRIPT:([^\r\n}]+)\}~', function($m) {
      if(!in_array( $m[1], $this->scripts) && file_exists($m[1])){
        array_push($this->scripts, $m[1]);
      }
    }, $salida);
  }
  function reemplazarComponentes($salida){
    return preg_replace_callback('~\{COMPONENTE:(\w+)\}~',function($m) {
      return $this->reemplazarComponentes("<!--$m[1]-->".$this->$m[1]());
    }, $salida);
  }
  function show($vista){
    $c = get_class($this->controlador);
    $this->vista = $vista;
    $ruta = './mvc/'.$c.'\vistas/'.$vista.'.htm';
    if(!file_exists($ruta)) return $this->render($this->$vista());
    $template = file_get_contents($ruta);
    $this->render($template);
  }
  function render($template){
    echo "<!-- Inicio Vista ".$this->vista." -->";
    $this->stack = array();
    $template = str_replace('<', '<?php echo \'<\'; ?>', $template);
    $template = preg_replace_callback('~\{TITULO:([^\r\n}]+)\}~', function($m) {
      $this->titulo = $m[1];
    }, $template);
    $template = $this->reemplazarComponentes($template);
    $template = preg_replace('~\{SCRIPT\}~', '<?php echo \'<script type="text/javascript">\'."\n"; ?>', $template);
    $template = preg_replace('~\{ENDSCRIPT\}~', '<?php echo \'</script>\'; ?>', $template);
    $template = preg_replace('~\{ENDIF\}~', '<?php endif; ?>', $template);
    $template = preg_replace('~\{ENDLOOP\}~', '<?php $this->unwrap(); endforeach; endif;?>', $template);
    $template = preg_replace('~\{GET:([^\r\n}]+)\}~', '<?php $this->getVariable2(preg_split("/\|/",$1)); ?>', $template);
    $template = $this->traducirCondiciones($template);
    $template = preg_replace('~\{(\w+)\}~', '<?php $this->showVariable(\'$1\'); ?>', $template);
    $template = preg_replace('~\{IF(([^\r\n}]+))\}~', '<?php if ($1): ?>', $template); // error, falta mejorar la manera en la que se plantean las condiciones
    $template = preg_replace('~\{LOOP:(\w+)\}~', '<?php
    if (isset($this->data[\'$1\']) && $this->data[\'$1\'][0]):
      $this->data[\'index_$1\'] = 0;
      foreach ($this->data[\'$1\'] as $ELEMENT):
        $this->data[\'index_$1\']++;
        $this->wrap($ELEMENT);
        ?>', $template); // falta aceptar un numero de iteraciones y mejorar la manera en que se leen los parametros
    $template = preg_replace('~\{RENDER:(\w+)\}~', '<?php
    $temp = new Vista();
    $temp->controlador = $this->controlador;
    $temp->vistaPadre = $this;
    $temp->data = array_merge($this->data , $this->stack);
    $temp->show(\'$1\')?>', $template);
    $template = $this->getEstilos($template);
    $template = $this->getScripts($template);
    if(isset($this->vistaPadre)) $this->vistaPadre->estilos = array_unique(array_merge($this->vistaPadre->estilos , $this->estilos), SORT_REGULAR);
    if(isset($this->vistaPadre)) $this->vistaPadre->scripts = array_unique(array_merge($this->vistaPadre->scripts , $this->scripts), SORT_REGULAR);
    $template = '?>' . $template;
    ob_start();
    eval ($template);
    $result = ob_get_contents();
    if(!isset($this->vistaPadre)) $result = $this->Cabecera().$result.$this->Pie();
    ob_end_clean();
    echo $result;
    echo "<!-- Fin Vista ".$this->vista." -->";


  }

  public function Cabecera(){
    return "
    <!DOCTYPE html>
    <html lang='en'>
      <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0, shrink-to-fit=no'>
        <title>".((!isset($this->titulo)) ? get_class($this->controlador) :  $this->titulo )."</title>
        <link rel='icon' type='image/png' href='".URL."\\".ICON."'>
        <!-- Estilos -->
        ".$this->Estilos()."
        <!-- Scripts -->
        ".$this->Scripts()."
      </head>
      <body>
        ";
  }
  public function Estilos(){
    $salida="<link rel=\"stylesheet\" href=\"https://fonts.googleapis.com/css?family=Roboto:300,400,500,700\">
        <link rel=\"stylesheet\" href=\"https://fonts.googleapis.com/icon?family=Material+Icons\">";
    foreach (['libs/css/bootstrap-material-design.min.css'] as $value) $salida .= "
        <link rel=\"stylesheet\" href=\"".URL."/".$value."\" type=\"text/css\" />";
    $salida .= "
        <link rel=\"stylesheet\" href=\"".URL."/public/css/".get_class($this->controlador).".css\" type=\"text/css\" />";
    foreach ($this->estilos as $key => $value)
      $salida .= "
          <link href=\"".URL."/".$value."\" rel=\"stylesheet\" type=\"text/css\" />";
    return $salida;
  }

  public function Scripts(){
    $salida .= "<script src=\"".URL."/public/js/".get_class($this->controlador).".js\" type=\"text/javascript\"></script>";
    foreach ($this->scripts as $key => $value)
      $salida .= "
          <script src=\"".URL."/".$value."\" type=\"text/javascript\"></script>";
    return $salida;
  }
  public function ScriptsPie(){
    $js = [
      'libs/js/jquery.min.js',
      'libs/js/tether.min.js',
      'libs/js/snackbar.min.js',
      'libs/js/bootstrap-material-design.iife.min.js',
      'libs/js/ie10-viewport-bug-workaround.js'];
    foreach ($js as $value) $salida .= "
        <script src=\"".URL."/".$value."\" type=\"text/javascript\"> </script>";
    $salida.="
        <script type=\"text/javascript\">
          $('body').bootstrapMaterialDesign();
        </script>";
    return $salida;
  }
  public function Pie(){
    return '
      </body>
      <!-- Scripts Pie-->'.
      $this->ScriptsPie().'
    </html>';
  }

/*
* COMPONENTES
*/
private function navbar_item($text, $c=null, $a=null){
  return '
  <li class="nav-item">
    <a class="nav-link" href="'.URL.'?c='.(isset($c) ? $c : $text).'">'.$text.'</a>
  </li>
  ';
}
function navbar(){
  $l = "";
  if(isset($this->items_navbar) && isset($this->items_navbar[0]))
  foreach ($this->items_navbar as $key => $v) {
    $l .= $this->navbar_item($key, $v);
  }
  return '
  <nav class="navbar navbar-fixed-top navbar-light bg-faded">
    <a class="navbar-brand" href="#">'.(isset($this->navbar_brand) ? $this->navbar_brand : PROYECTO).'</a>
    <ul class="nav navbar-nav">
    '.$l.'
    </ul>
    <form class="form-inline pull-xs-right">
      <input class="form-control" type="text" placeholder="Buscar">
    </form>
  </nav>
  <div style="height:55px"></div>';
}

}
