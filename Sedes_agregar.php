<?php
	include("./conexion.php");
	$n=$_POST['sede'];

if ($n){
	$sql="INSERT into SEDES (nombreSede)
								values ('$n')";
	echo $result=mysqli_query($conexion,$sql);
} else {
	echo "no hago nada";
}

 ?>
