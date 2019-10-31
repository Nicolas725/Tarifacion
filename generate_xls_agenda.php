<?php
	include("./conexion.php");
	header('Content-type:application/xls');
	header('Content-Disposition: attachment; filename=periodo_facturacion.xls');
	$valor = isset($_POST['valor']) ? $_POST['valor'] : false;
	$fecha = isset($_POST['dateFrom']) ? $_POST['dateFrom'] : false;
	$fecha1 = isset($_POST['dateTo']) ? $_POST['dateTo'] : false;
	$valor2 = isset($_POST['valor2']) ? $_POST['valor2'] : false;
	$timeFormat = isset($_POST['timeFormat']) ? $_POST['timeFormat'] : false;

	$i=0;
		//error_reporting(E_ERROR | E_PARSE); //hace que no se muestren los warning
	$sql="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE date BETWEEN '$fecha' AND '$fecha1')
					UNION ALL
					(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE date BETWEEN '$fecha' AND '$fecha1')";
	//echo $sql;
	$result=mysqli_query($conexion,$sql);
	while($ver=mysqli_fetch_row($result)){
		$valor1[$i]= $ver[0];

		$hours1 = floor($valor1[$i] / 3600);
		$mins1 = floor($valor1[$i] / 60 % 60);
		$secs1 = floor($valor1[$i] % 60);

		if ($secs1>=1){
			$mins1=$mins1+1;
			if ($mins1>59){
				$mins1=0;
				$hours1=$hours1+1;
				}
			$secs1=0;
		}

		$timeFormat1 = sprintf('%02d:%02d:%02d', $hours1, $mins1, $secs1);
		ceil($timeFormat1);
		$timeFormat2[$i]=$hours1*3600+$mins1*60;
		$i++;
	}

	$valor2=array_sum($timeFormat2);
	$hours = floor($valor2 / 3600);
	$mins = floor($valor2 / 60 % 60);
	$secs = floor($valor2 % 60);
	$timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
	$valor2=($valor2/60)*$valor;
	$valor2="$".round($valor2,2);

?>
<div class="row">
 <div class="col-sm-12">
 <h2>Periodo de Facturacion</h2>
 El precio del minuto es:
 <?php
 echo "$".$valor;
	?>
	<br>

 El precio total por la cantidad de minutos hablados es:
 <?php
 echo $valor2;
	?>
	<br>
 La cantidad de minutos hablados es:
	<?php
	echo $timeFormat;
	 ?>
	 <br>
	 <br>
				<table class="table table-hover table-condensed table-bordered">
					<tr>
						<td>Interno</td>
						<td>Nombre</td>
						<td>Fecha</td>
						<td>Hora</td>
						<td>Destino</td>
						<td>Tipo</td>
						<td>Sede</td>
						<td>Departamento</td>
						<td>Duration</td>

					</tr>

					<?php
					//error_reporting(E_ERROR | E_PARSE); //hace que no se muestren los warning
						$sql1="(SELECT chargeduserid, suscribername, date, time, diallednumber,
												 communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE date BETWEEN '$fecha' AND '$fecha1'
												 )
														UNION ALL
									(SELECT chargeduserid,suscribername, date, time, diallednumber,
												 communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE date BETWEEN '$fecha' AND '$fecha1')";
						 //echo $sql;
							$result=mysqli_query($conexion,$sql1);
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
		<?php
		}
		?>
					</tr>
						<td>TOTAL<td><td><td><td><td><td><td><td><?php echo $timeFormat ?><td><?php echo $valor2 ?><td></td></td>
					</tr>

				</table>
			</div>
		</div>
