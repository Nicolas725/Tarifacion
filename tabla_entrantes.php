<?php
	include("./conexion.php");
	//require_once "conexion.php";
	//$conexion=conexion();

 ?>
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
									ORDER BY date,time DESC";

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
