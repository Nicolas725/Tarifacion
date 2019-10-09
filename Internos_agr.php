<?php
	include("./conexion.php");

	$n=$_POST['nombre'];
	$a=$_POST['inter'];
	$e=$_POST['sede'];
	$t=$_POST['depar'];

	if ($n && $a && $e){
		$sql="INSERT into INTERNOS (suscribername, chargeduserid, nombreSede,nombreDepar)
									values ('$n','$a','$e','$t')";
		echo $result=mysqli_query($conexion,$sql);

		$sql1 ="UPDATE tickets_incoming SET nombreSede='$e', nombreDepar='$t'
					where chargeduserid='$a'";
		$result=mysqli_query($conexion,$sql1);

		$sql2 ="UPDATE tickets_incoming_transfer SET nombreSede='$e', nombreDepar='$t'
					where chargeduserid='$a'";
		$result=mysqli_query($conexion,$sql2);

		$sql3 ="UPDATE tickets_outgoing SET nombreSede='$e', nombreDepar='$t'
					where chargeduserid='$a'";
		$result=mysqli_query($conexion,$sql3);

		$sql4 ="UPDATE tickets_outgoing_transfer SET nombreSede='$e', nombreDepar='$t'
					where chargeduserid='$a'";
		$result=mysqli_query($conexion,$sql4);
}
else {
	echo "no hago nada";
}
 ?>
