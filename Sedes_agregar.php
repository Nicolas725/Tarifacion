<?php
	include("./conexion.php");
	$n=$_POST['sede'];

	$sql="INSERT into SEDES (nombreSede)
								values ('$n')";
	echo $result=mysqli_query($conexion,$sql);

 ?>
