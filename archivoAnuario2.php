<?php
session_start();
include("./conexion.php");

 
$fp = fopen('baseDatos.txt','rb');
if (!$fp) {
	echo 'ERROR: No ha sido posible abrir el archivo. Revisa su nombre y sus permisos.';
	exit;
	}

$loop = 0; // contador de líneas
while (!feof($fp)) { // loop hasta que se llegue al final del archivo
$loop++;
$line = fgets($fp); // guardamos toda la línea en $line como un string
// dividimos $line en sus celdas, separadas por el caracter ;
// e incorporamos la línea a la matriz $field
$field[$loop] = explode (';', $line);
// generamos la salida HTML

$tabla="internos";
$sql="INSERT INTO $tabla (id,interno,nombre)
	VALUES ('','{$field[$loop][0]}','{$field[$loop][2]}')";
mysql_query($sql);
$fp++; // necesitamos llevar el puntero del archivo a la siguiente línea
}
fclose($fp);
mysql_close();

header('Location: index.php');
?>
