<?php
include("./conexion.php");
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1, minimum-scale=1.0">
	<title>Software de Tarifacion</title>
	<link rel="stylesheet" href="css/bootstrap.css">
	<script src="jquery-3.2.1.min.js"></script>
	<script src="js/bootstrap.js"></script>
	<link rel="stylesheet" href="css/estilo2.css">
	<link rel="stylesheet" href="icono.min.css">
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h2>Llamadas Entrantes</h2>
				<br><br>
				<table class="table table-hover table-condensed">
					<tr>
						<?php
						print "<img src=\"imagenes/UM_logo3.jpg\">"
						?>
					</tr>
					<br><br>
					<td>

					</td>
					<td>

					</td>
					<td><a href="index.php" class="btn btn-primary">Volver</a></td>
					<td>

					</td>
					<td><a href="salir.php" class="btn btn-danger">Cerrar Sesion</a></td>
					<td>

					</td>
					<br>
				</table>
				<form method="post" action="process_entrantes.php">
					<br>
					Seleccione la opcion por la cual desea filtrar:
					<br><br>
					<td>
						<input type="checkbox" name="inter1" value="inter1">Interno
					</td>
					<td>
						<input type="checkbox" name="date" value="date">Fecha
					</td>
					<td>
						<input type="checkbox" name="sed" value="sed">Sede
					</td>
					<td>
						<input type="checkbox" name="depar" value="depar">Departamento
					</td>
					<td>
						<br><br>
						<input type="submit" value="Filtrar" class="btn btn-primary">
					</td>
				</form>
				<br><br>
	<div class="row">
		<div class="col-sm-12">
			<table class="table table-hover table-condensed table-bordered">
				<tr>
					<td>Interno</td>
					<td>Nombre</td>
					<td>Fecha</td>
					<td>Hora</td>
					<td>Duration</td>
					<td>Destino</td>
					<td>Tipo</td>
					<td>Sede</td>
					<td>Departamento</td>
				</tr>

				<?php
				/*if(!isset($_POST['frmSearch'])){
				$opcion = $_POST['frmSearch'];
				echo $opcion;
				echo "estoy aca";
			}*/
			/*if(!isset($_POST['dateFrom'])){
			$new_date = date('Y-m-d', strtotime($_POST['dateFrom']));
			echo $new_date;
		}*/

		$sql="(SELECT
		chargeduserid,
		suscribername,
		date,
		time,
		callduration,
		diallednumber,
		communicationtype,
		nombreSede,
		nombreDepar

		FROM

		tickets_incoming
		)
		UNION ALL

		(SELECT
		chargeduserid,
		suscribername,
		date,
		time,
		callduration,
		diallednumber,
		communicationtype,
		nombreSede,
		nombreDepar

		FROM

		tickets_incoming_transfer)
		";

		$result=mysqli_query($conexion,$sql);
		while($ver=mysqli_fetch_row($result)){
			?>

			<tr>
				<td><?php echo $ver[0] ?></td>
				<td><?php echo $ver[1] ?></td>
				<td><?php echo $ver[2] ?></td>
				<td><?php echo $ver[3] ?></td>
				<td><?php echo $ver[4] ?></td>
				<td><?php echo $ver[5] ?></td>
				<td><?php echo $ver[6] ?></td>
				<td><?php echo $ver[7] ?></td>
				<td><?php echo $ver[8] ?></td>

			</tr>
			<?php
		}
		?>
	</table>
</div>
</div>
</div>
</div>
</div>
</body>
</html>
