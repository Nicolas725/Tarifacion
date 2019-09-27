<?php
session_start();
include("./conexion.php");

$tabla="noticias";
$sql="INSERT INTO $tabla (idUsuario,titulo,resumen,notacompleta,fechapublic)
	VALUES ('{$_SESSION['usuario']}','{$_POST['titulo']}','{$_POST['resumen']}','{$_POST['notacompleta']}','{$_POST['fechapublic']}')";

mysql_query($sql);

mysql_close();

header('Location: user.php');

?>