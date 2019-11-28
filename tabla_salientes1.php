<?php
	include("./conexion.php");
	//require_once "conexion.php";
	//$conexion=conexion();

 ?>
<div class="row">
	<div class="col-sm-12">
	<h2>LLamadas Salientes</h2>
		<table class="table table-hover table-condensed table-bordered">
			<tr>
				<td>Interno</td>
        <td>Nombre</td>
				<td>Fecha</td>
				<td>Hora</td>
				<td>Duration</td>
        <td>Destino</td>
			</tr>

			<?php
 
				$params1='ext';
				$params2='d_inicio';
				$params3='d_fin';
				$sql="SELECT
										`tickets_outgoing`.`chargeduserid` as `origin_userid`,
										`tickets_outgoing`.`suscribername` as `origin_suscribername`,
										`tickets_outgoing_transfer`.`date`,
										`tickets_outgoing_transfer`.`time`,
										`tickets_outgoing_transfer`.`chargeduserid`,
										`tickets_outgoing_transfer`.`suscribername`,
										`tickets_outgoing_transfer`.`callduration`,
										`tickets_outgoing_transfer`.`diallednumber`

										FROM

												`tickets_outgoing`,
												`tickets_outgoing_transfer`

				         WHERE (
                        (SUBTIME(`tickets_outgoing_transfer`.`time`, `tickets_outgoing`.`time`) >= '00:00:00') AND
                        (SUBTIME(`tickets_outgoing_transfer`.`time`, `tickets_outgoing`.`time`) < '00:02:00') AND
                        `tickets_outgoing`.`date` = `tickets_outgoing_transfer`.`date` AND
                        `tickets_outgoing`.`DialledNumber` = `tickets_outgoing_transfer`.`DialledNumber` AND
                        `tickets_outgoing`.`trunkid` = `tickets_outgoing_transfer`.`trunkid`
											)

                    UNION

                    SELECT
                        '*' as `origin_userid`,
                        '*' as `origin_suscribername`,
                        `date`,
                        `time`,
                        `chargeduserid`,
                        `suscribername`,
                        `callduration`,
                        `diallednumber`

                    FROM

                        `tickets_outgoing`

                    ORDER BY date,time ASC

											";/*
                        `tickets_outgoing_transfer`.`chargeduserid`='" .$params1. "'
												AND
                        `tickets_outgoing_transfer`.`date` >= '" . $params2. "' AND
                        `tickets_outgoing_transfer`.`date` <= '" . $params3. "'


                    UNION

                    SELECT
                        '*' as `origin_userid`,
                        '*' as `origin_suscribername`,
                        `date`,
                        `time`,
                        `chargeduserid`,
                        `suscribername`,
                        `callduration`,
                        `diallednumber`

                    FROM

                        `tickets_outgoing`

                    WHERE (

                        `chargeduserid` = '" . $params->ext . "' AND
                        `date` >= '" . $params->d_inicio . "' AND
                        `date`<= '" . $params->d_fin . "'

                    )

                    ORDER BY date,time ASC

              ";*/
				$result=mysqli_query($conexion,$sql);
				while($ver=mysqli_fetch_row($result)){
			 ?>

			<tr>
				<td><?php echo $ver[1] ?></td>
				<td><?php echo $ver[2] ?></td>
				<td><?php echo $ver[3] ?></td>
				<td><?php echo $ver[4] ?></td>
				<td><?php echo $ver[5] ?></td>
				<td><?php echo $ver[6] ?></td>
				<td><?php echo $ver[7] ?></td>


				<td>
					<button class="btn btn-warning glyphicon glyphicon-pencil" data-toggle="modal" data-target="#modalEdicion"></button>
				</td>
				<td>
					<button class="btn btn-danger glyphicon glyphicon-remove"></button>
				</td>
			</tr>
			<?php
		}
			 ?>
		</table>
	</div>
</div>
