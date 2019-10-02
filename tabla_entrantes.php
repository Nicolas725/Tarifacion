<?php
	include("./conexion.php");
?>

<html>
	<body>
		<div class="container" style="padding-top:50px">
		<h2>Generar PDF</h2>
		<form class="form-inline" method="post" action="generate_pdf.php">
		<button type="submit" id="pdf" name="generate_pdf" class="btn btn-primary"><i class="fa fa-pdf" aria-hidden="true"></i>
		Generate PDF</button>
		</form>
		</fieldset>
		</div>


		<form method="post" action="process.php">
		 Opciones de filtrado por fecha: <br>
		 From:
		 <input type="date" name="dateFrom" id="dateFrom" value="<?php echo date('2017-01-01'); ?>" min="2017-01-01" max="2017-12-31"/>
		 <br/>
		 To:
		 <input type="date" name="dateTo" id="dateTo" value="<?php echo date('Y-m-d'); ?>" min="2017-01-01" max="2019-12-31"/>
		 <br/>
		 Opciones de filtrado por interno:
		 <input type="number" name="inter" id="inter" value="100" min="100" max="600">

		 <input type="submit" value="Filtrar"/>
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
