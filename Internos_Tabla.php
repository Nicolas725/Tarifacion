
<?php
include("./conexion.php");
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="librerias/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="librerias/alertifyjs/css/alertify.css">
	<link rel="stylesheet" type="text/css" href="librerias/alertifyjs/css/themes/default.css">
	<link rel="stylesheet" href="css/estilo2.css">
	<script src="librerias/jquery-3.2.1.min.js"></script>
	<script src="js/funcionesI.js"></script>
	<script src="librerias/bootstrap/js/bootstrap.js"></script>
	<script src="librerias/alertifyjs/alertify.js"></script>
</head>
<body >
  <div class="container">
		<div class="row">
			<div class="col-sm-12">
				<table class="table table-hover table-condensed">
          <tr>
            <td>

            </td>
            <td>

            </td>
            <td>

            </td>

            <td>
            <h2>Internos</h2>
            </td>

            <td></td>
					</tr>
          <tr>
            <td>

            </td>
            <td>

            </td>
            <td>

            </td>

            <td>
              <?php
              print "<img src=\"imagenes/UM_logo3.jpg\">"
              ?>
            </td>

            <td></td>
					</tr>

					<br><br>
					<td>

					</td>
					<td>

					</td>
					<td><a href="index.php" class="btn btn-primary">Volver</a></td>
					<td>
            <form class="form-inline" action="internos.php" method="post">
            	<button type="submit" class="btn btn-primary text-right"><i class="fa fa-pdf" aria-hidden="true"></i>
            		Actualizar Internos</button>
            </form>
					</td>
					<td><a href="salir.php" class="btn btn-danger">Cerrar Sesion</a></td>
					<td>

					</td>
					<br>
				</table>
      </div>
    </div>
  </div>
<br>
<br>
<br>
<br><br>
<br><br>
<br>
<br>
<br><br>
<br>
<div class="row">
  <div class="col-sm-12">

    <table class="table table-hover table-condensed table-bordered">
      <caption>
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalNuevoI">
          Agregar Interno
          <span class="glyphicon glyphicon-plus"></span>
        </button>
      </caption>
      <tr>
        <td>Nombre</td>
        <td>Interno</td>
        <td>Sede</td>
        <td>Departamento</td>
        <td>Editar</td>
        <td>Eliminar</td>
      </tr>

      <?php

      $sql="SELECT id_I, suscribername, chargeduserid, nombreSede,nombreDepar FROM INTERNOS";
      $result=mysqli_query($conexion,$sql);
      while($ver=mysqli_fetch_row($result)){
        $datosI=$ver[0]."||".
        $ver[1]."||".
        $ver[2]."||".
        $ver[3]."||".
        $ver[4];
        ?>

        <tr>
          <td><?php echo $ver[1] ?></td>
          <td><?php echo $ver[2] ?></td>
          <td><?php echo $ver[3] ?></td>
          <td><?php echo $ver[4] ?></td>
          <td>
            <button class="btn btn-warning glyphicon glyphicon-pencil"
            data-toggle="modal" data-target="#modalEdicionI" onclick="agregaformI('<?php echo $datosI ?>')">
          </button>
        </td>
        <td>
          <button class="btn btn-danger glyphicon glyphicon-remove"
          onclick="preguntarSiNoI('<?php echo $ver[0] ?>')">
        </button>
      </td>
    </tr>
    <?php
  }
  ?>
</table>
</div>
</div>
