<?php
  session_start();
  include("./conexion.php");

  $sql="SELECT * FROM usuario";
  $consulta=  $conexion->query($sql);
  while ($registro=  mysqli_fetch_array($consulta, MYSQLI_ASSOC)){
    if($_POST['usuario']===$registro['usuario']){
        if($_POST['clave']===$registro['clave']){
            $ucontrol=true;
            $_SESSION['id']=$registro['id'];
            $_SESSION['clave']=$registro['clave'];
            if($registro['estado']==='0'){
                $deshabilitada=true;
            }
          }
        }
      }
  if($ucontrol){
    if($deshabilitada){
        header("location: nohabilitado.php");
      }
    else{
    $_SESSION['usuario']="{$_POST['usuario']}";
    $_SESSION['ucontrol']=true;
    header("location: index.php");
        }}

  else{
        session_destroy();
        header("location: incorrecto.html");
      }
  mysqli_close();

?>
