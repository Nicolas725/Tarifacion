<?php
include("./conexion.php");

$id=$_POST['id'];
$n=$_POST['nombre'];
$a=$_POST['inter'];
$e=$_POST['sede'];
$t=$_POST['depar'];

if ($n && $a && $e){
	$sql="UPDATE INTERNOS set suscribername='$n',
	chargeduserid='$a',
	nombreSede='$e',
	nombreDepar='$t'
	where id_I='$id'";
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
