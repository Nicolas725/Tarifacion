<?php
  $host='localhost';
  $usuario='root';
  $clave='mac1778##';
  $baseDatos='telefonos';
  //$conexion= mysql_connect($host,$usuario,$clave,$baseDatos);
  $conexion = new mysqli($host, $usuario, $clave, $baseDatos);
  //mysql_select_db($baseDatos);
  if ($conexion->connect_error) {
    die("Connection failed: " . $conexion->connect_error);
  }
?>
