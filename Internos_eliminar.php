
<?php
include("./conexion.php");

	$id=$_POST['id_I'];

	$sql="DELETE from INTERNOS where id_I='$id'";
	echo $result=mysqli_query($conexion,$sql);
 ?>
