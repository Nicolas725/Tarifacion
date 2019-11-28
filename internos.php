<?php
include("./conexion.php");
?>
 
<!DOCTYPE html>
<html lang="" dir="ltr">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <link rel="stylesheet" type="text/css" href="librerias/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="librerias/alertifyjs/css/alertify.css">
  <link rel="stylesheet" type="text/css" href="librerias/alertifyjs/css/themes/default.css">
  <link rel="stylesheet" href="css/estilo3.css">
  <script src="librerias/jquery-3.2.1.min.js"></script>
  <script src="js/funcionesI.js"></script>
  <script src="librerias/bootstrap/js/bootstrap.js"></script>
  <script src="librerias/alertifyjs/alertify.js"></script>
</head>
<body>
  <a href="agregar_Internos.php" class="btn btn-primary">Volver</a>
  <a href="salir.php" class="btn btn-danger">Cerrar Sesion</a>
  <?php

  $id=array();
  $datos1=array();
  $datos2=array();
  $datos3=array();
  $datos4=array();

  $i=0;
  $n=0;

  $sql="SELECT id_I, suscribername, chargeduserid, nombreSede,nombreDepar FROM INTERNOS";
  $result=mysqli_query($conexion,$sql);
  while($ver=mysqli_fetch_row($result)){
    $id[$i]=$ver[0];
    $datos1[$n]=$ver[1];
    $datos2[$n]=$ver[2];
    $datos3[$n]=$ver[3];
    $datos4[$n]=$ver[4];
    $i++;
    $n++;
  }

  $p=0;
  $l=0;

  while ($p<$i){

    $sql1 ="UPDATE tickets_outgoing SET nombreSede='$datos3[$p]', nombreDepar='$datos4[$p]'
    where chargeduserid='$datos2[$p]'";
    $result1=mysqli_query($conexion,$sql1);

    $sql2 ="UPDATE tickets_outgoing SET nombreSede='$datos3[$p]', nombreDepar='$datos4[$p]'
    where chargeduserid='$datos2[$p]'";
    $result2=mysqli_query($conexion,$sql2);

    $sql3 ="UPDATE tickets_outgoing SET nombreSede='$datos3[$p]', nombreDepar='$datos4[$p]'
    where chargeduserid='$datos2[$p]'";
    $result3=mysqli_query($conexion,$sql3);

    $sql4 ="UPDATE tickets_outgoing SET nombreSede='$datos3[$p]', nombreDepar='$datos4[$p]'
    where chargeduserid='$datos2[$p]'";
    $result4=mysqli_query($conexion,$sql4);

    $p++;
  }

  ?>
</body>
</html>
