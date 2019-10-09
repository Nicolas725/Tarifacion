<?php
include("./conexion.php");

	$id=$_POST['id_S'];
	$n=$_POST['sede'];

	if ($n){
	$sql="UPDATE SEDES set nombreSede='$n' where id_S='$id'";
	echo $result=mysqli_query($conexion,$sql);
} else {
	echo "no hago nada";
}

 ?>
