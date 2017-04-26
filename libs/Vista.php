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

  function showVariable($name) {
    if (isset($this->stack[$name])) {
      echo $this->stack[$name];
    }else if (isset($this->data[$name])) {
      echo $this->data[$name];
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
  function set($nombre, $valor){
    $this->data[$nombre] = $valor;
  }
  function wrap($element) {
    foreach ($element as $k => $v) {
      $this->stack[$k] = $v;
    }
  }
  function unwrap() {
    $this->stack = [];
  }
  function show($vista){
    $c = get_class($this->controlador);
    $this->vista = $vista;
    $ruta = './mvc/'.$c.'\vistas/'.$vista.'.htm';
    $this->render($ruta);
  }
  function traducirDatos($salida){
    return preg_replace_callback('~\{GET:([^\r\n}]+)\}~', function($m) {
      return $this->getVariable($m[1]);
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
      if(!in_array( $m[1], $this->scripts)){
        array_push($this->scripts, $m[1]);
      }
    }, $salida);
  }
  function render($ruta) {
    echo "<!-- Inicio Vista ".$this->vista." -->";
    if (!file_exists($ruta)) return (new ControlErrores())->faltaVista();
    $template = file_get_contents($ruta);
    $this->stack = array();
    $template = str_replace('<', '<?php echo \'<\'; ?>', $template);
    $template = preg_replace_callback('~\{TITULO:([^\r\n}]+)\}~', function($m) {
      $this->titulo = $m[1];
    }, $template);
    $template = preg_replace('~\{ENDIF\}~', '<?php endif; ?>', $template);
    $template = preg_replace('~\{ENDLOOP\}~', '<?php $this->unwrap(); endforeach; endif;?>', $template);
    $template = $this->traducirCondiciones($template);
    $template = $this->traducirDatos($template);
    $template = preg_replace('~\{(\w+)\}~', '<?php $this->showVariable(\'$1\'); ?>', $template);
    $template = preg_replace('~\{IF(([^\r\n}]+))\}~', '<?php if ($1): ?>', $template); // error, falta mejorar la manera en la que se plantean las condiciones
    $template = preg_replace('~\{LOOP:(\w+)\}~', '<?php
    if (isset($this->data[\'$1\']) && $this->data[\'$1\'][0]):
      $this->data[\'index_$1\'] = 0;
      foreach ($this->data[\'$1\'] as $ELEMENT):
        $this->data[\'index_$1\']++;
        $this->wrap($ELEMENT); ?>', $template); // falta aceptar un numero de iteraciones

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
    //if(!isset($this->vistaPadre)) $template = $this->Cabecera().$template.$this->Pie();
    $template = '?>' . $template;
    ob_start();
    eval ($template);
    $result = ob_get_contents();
    ob_end_clean();
    if(!isset($this->vistaPadre)) $result = $this->Cabecera().$result.$this->Pie();
    echo $result;
      echo "<!-- Fin Vista ".$this->vista." -->";


  }

  public function Cabecera()
  {
    return "\n<!DOCTYPE html>
    <html>
      <head>
        <meta charset='utf-8'>
        <title>".((!isset($this->titulo)) ? get_class($this->controlador) :  $this->titulo )."</title>
        <!-- Estilos -->
        ".$this->Estilos()."
        <!-- Scripts -->
        ".$this->Scripts()."
      </head>
      <body>";
  }
  public function Estilos(){
    $salida = "<link href=\"".URL."/public/css/".get_class($this->controlador).".css\" rel=\"stylesheet\" type=\"text/css\" />
    ";
    foreach ($this->estilos as $key => $value) {
      $salida .= "<link href=\"".URL."/".$value."\" rel=\"stylesheet\" type=\"text/css\" />
      ";
    }
    return $salida;
  }

  public function Scripts(){
    $salida = "<script src=\"".URL."/public/js/".get_class($this->controlador).".js\" type=\"text/javascript\"> </script>
    ";
    foreach ($this->scripts as $key => $value) {
      $salida .= "<script src=\"".URL."/".$value."\" type=\"text/javascript\"> </script>
      ";
    }
    return $salida;
  }

  public function Pie()
  {
    return '    </body>
  </html>';
  }




}
