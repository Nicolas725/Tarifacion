
<?php
include("./conexion.php");

$id=$_POST['id_S'];

$sql="DELETE from SEDES where id_S='$id'";
echo $result=mysqli_query($conexion,$sql);
?>
