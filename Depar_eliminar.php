
<?php
include("./conexion.php");

	$id=$_POST['id_D'];

	$sql="DELETE from DEPARTAMENTOS where id_D='$id'";
	echo $result=mysqli_query($conexion,$sql);
 ?>
