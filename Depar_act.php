<?php
include("./conexion.php");

	$id=$_POST['id_D'];
	$n=$_POST['depar'];

	if ($n){

	$sql="UPDATE DEPARTAMENTOS set nombreDepar='$n' where id_D='$id'";
	echo $result=mysqli_query($conexion,$sql);
} else {
	echo "no hago nada";
}

 ?>
