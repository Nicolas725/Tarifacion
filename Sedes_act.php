<?php
include("./conexion.php");

	$id=$_POST['id_S'];
	$n=$_POST['sede'];

	$sql="UPDATE SEDES set nombreSede='$n' where id_S='$id'";
	echo $result=mysqli_query($conexion,$sql);

 ?>
