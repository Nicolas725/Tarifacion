<?php
	include("./conexion.php");
?>

<html>
	<body>

		<form method="post" action="process.php">
			<br/>
			Seleccione la opcion por la cual desea filtrar:
			<br/>
			<input type="checkbox" name="inter1" value="inter1">Interno<br>
			<input type="checkbox" name="date" value="date">Fecha<br>
			<input type="checkbox" name="sed" value="sed">Sede<br>
			<input type="checkbox" name="depar" value="depar">Departamento<br>
		  <input type="submit" value="Filtrar">
		</form>

	<div class="row">
		<div class="col-sm-12">
		<h2>LLamadas Entrantes</h2>
			<table class="table table-hover table-condensed table-bordered">
				<tr>
					<td>Interno</td>
	        <td>Nombre</td>
					<td>Fecha</td>
					<td>Hora</td>
					<td>Duration</td>
					<td>Destino</td>
					<td>Tipo</td>
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
											communicationtype

											FROM

													tickets_incoming
													)
											UNION

								(SELECT
											chargeduserid,
											suscribername,
											date,
											time,
											callduration,
											diallednumber,
											communicationtype

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

				</tr>
				<?php
			}
				 ?>
			</table>
		</div>
	</div>
</body></html>
