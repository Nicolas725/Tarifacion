<?php
include("./conexion.php");
$n=$_POST['depar'];

if ($n){
 
	$sql="INSERT into DEPARTAMENTOS (nombreDepar)
	values ('$n')";
	echo $result=mysqli_query($conexion,$sql);
} else {
	echo "no hago nada";
}

?>
