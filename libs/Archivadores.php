<?php
/**
*
*/
require 'config.php';
function stream_copy($src, $dest)
{
  $fsrc = fopen($src,'r');
  $fdest = fopen($dest,'w+');
  $len = stream_copy_to_stream($fsrc,$fdest);
  fclose($fsrc);
  fclose($fdest);
  return $len;
}

/**
*
*/
class _MVC{
  function __construct($nombre){
    $this->nombre = $nombre;
  }
  function Crear(){
    $this->carpetasModulo();
    $this->Controlador();
    $this->Modelo();
    $this->Vistas();
    $this->Recursos();
  }
  function carpetasModulo(){
    echo  shell_exec('IF NOT EXIST mvc mkdir mvc');
    echo  shell_exec('IF NOT EXIST public mkdir public');
    echo  shell_exec('IF NOT EXIST public\\js mkdir public\\js');
    echo  shell_exec('IF NOT EXIST public\\css mkdir public\\css');
    echo  shell_exec('IF NOT EXIST mvc\\'.$this->nombre.' mkdir mvc\\'.$this->nombre);
    //echo  shell_exec('IF NOT EXIST mvc\\'.$this->nombre.'\\modelo mkdir mvc\\'.$this->nombre.'\\modelo');
    echo  shell_exec('IF NOT EXIST mvc\\'.$this->nombre.'\\vista mkdir mvc\\'.$this->nombre.'\\vistas');
    //echo  shell_exec('IF NOT EXIST mvc\\'.$this->nombre.'\\controlador mkdir mvc\\'.$this->nombre.'\\controlador');
  }
  function Vistas(){
    echo  shell_exec('IF NOT EXIST mvc\\'.$this->nombre.'\\vistas mkdir mvc\\'.$this->nombre.'\\vistas');
    $vistas = array('index' => 'index', 'k'=>'k', 'probando'=>'probando');
    $template = LIBS.'/tpl/vista.php';
    $archivo = 'mvc/'.$this->nombre.'/'.$this->nombre.'_Vista.php';
    if(file_exists($template) and is_file($template)){
        $f = fopen($template,"rt");
        $vista = fread($f, filesize($template));
        @fclose($f);
        $vista = preg_replace('~<vista_nombre>~', $this->nombre."_Vista", $vista);
        file_put_contents($archivo, $vista);
        echo "Creando gestor de Vistas...".$archivo."\n";
    }else echo "No se encontro la ruta de la plantoya de vista \n";
    foreach ($vistas as $key => $value) {
      $ruta = 'mvc\\'.$this->nombre.'\\vistas\\'.$value.'.htm';
      shell_exec('IF NOT EXIST '.$ruta.' echo> '.$ruta);
      if (stream_copy('libs\\tpl\\vistas\\'.$value.'.htm', $ruta)) echo "Creando Vistas... $value\n";
    }
  }
  function Recursos(){
    $js = 'public/js/'.$this->nombre.'.js';
    $css = 'public/css/'.$this->nombre.'.css';
    echo shell_exec('echo ^/*--- javascript '.$this->nombre.' ---*/> '.$js);
    echo "Creando js...\n";
    echo shell_exec('echo ^/*--- estilos '.$this->nombre.' ---*/> '.$css);
    echo "Creando css...\n";
  }
  function Controlador(){
    $template = LIBS.'/tpl/controlador.php';
    $archivo = 'mvc/'.$this->nombre.'/'.$this->nombre.'.php';
    if(file_exists($template) and is_file($template)){
        $f = fopen($template,"rt");
        $controlador = fread($f, filesize($template));
        @fclose($f);
        $controlador = preg_replace('~<controlador_nombre>~', $this->nombre, $controlador);
        file_put_contents($archivo, $controlador);
        echo "Creando Controlador...".$archivo."\n";
    }else echo "No se encontro la ruta de la plantoya de controlador \n";
  }
  function Modelo(){
    $template = LIBS.'/tpl/modelo.php';
    $archivo = 'mvc/'.$this->nombre.'/'.$this->nombre.'_Modelo.php';
    if(file_exists($template) and is_file($template)){
        $f = fopen($template,"rt");
        $modelo = fread($f, filesize($template));
        @fclose($f);
        $modelo = preg_replace('~<modelo_nombre>~', $this->nombre."_Modelo", $modelo);
        file_put_contents($archivo, $modelo);
        echo "Creando Modelo...".$archivo."\n";
    }else echo "No se encontro la ruta de la plantoya de modelo \n";
  }
}
