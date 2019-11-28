<?php
include("./conexion.php");

$id=$_POST['id'];
$n=$_POST['Tarifa'];
$a=$_POST['Fromf'];
$e=$_POST['Tof'];
 
//if ($n && $a && $e){
$sql="UPDATE Costos set Tarifa='$n',
Fromf ='$a',
Tof ='$e'
where id_T='$id'";
echo $result=mysqli_query($conexion,$sql);

//}
//else {
//	echo "no hago nada";
//}
?>
