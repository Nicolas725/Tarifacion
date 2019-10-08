<?php
	include("./conexion.php");
	$n=$_POST['depar'];

	$sql="INSERT into DEPARTAMENTOS (nombreDepar)
								values ('$n')";
	echo $result=mysqli_query($conexion,$sql);

 ?>
