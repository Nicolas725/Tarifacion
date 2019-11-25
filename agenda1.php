<?php
include("./conexion.php");
session_start();

$Tinter = isset($_POST['inter']) ? $_POST['inter'] : false;
$Tnacion = isset($_POST['nacion']) ? $_POST['nacion'] : false;
$Tlocal = isset($_POST['local']) ? $_POST['local'] : false;
$Tcel = isset($_POST['cel']) ? $_POST['cel'] : false;
$fecha = isset($_POST['dateFrom']) ? $_POST['dateFrom'] : false;
$fecha1 = isset($_POST['dateTo']) ? $_POST['dateTo'] : false;

$_SESSION['dateFrom'] = $fecha;
$_SESSION['dateTo'] = $fecha1;

$Prefijo=array('00',
'011','0221','0223','0291','03833',
'0351','03783','03722','02965','0343',
'03717','0388','02954','03822','0261',
'03752','0299','02920','0387','0264',
'02652','02966','0342','0341','0385',
'0381','02901',
'3','4','5','6',
'15');
$min=[];
$costo=[];
$internacional=[];
$nacional=[];
$local=[];
$celular=[];
$individual=[];
$individualI=[];
$individualN=[];
$individualL=[];
$individualC=[];
?>
<html lang="" dir="ltr">

<head>
	<meta charset="utf-8">
	<title>Graficos con plotly</title>
	<link rel="stylesheet" type="text/css" href="librerias/bootstrap/css/bootstrap.css">
	<script src="librerias/jquery-3.4.1.min.js"></script>
	<script src="librerias/chart/plotly-latest.min.js"></script>
</head>
<body>

	<div class="row">
		<div class="col-sm-12">
			<h2>LLamadas Salientes</h2>
			<table table border="1" class="table table-hover table-condensed table-bordered">
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
					<td>Costo</td>

				</tr>
				<?php
				error_reporting(E_ERROR | E_PARSE); //hace que no se muestren los warning
				$n=0;
				$t=0;
				$l=0;
				$y=0;
				while ($n<1){ //INTERNACIONAL
					$sql3="";
					$sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";
					$i=0;
					$h=0;
					$sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . ")
					UNION ALL
					(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . ")";

					$result2=mysqli_query($conexion,$sql2);
					while($ver2=mysqli_fetch_row($result2)){
						$valor1[$i]= $ver2[0];
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
						$timeFormatI[$i]=$hours1*3600+$mins1*60;
						//COSTO LLAMADA INDIVIDUAL
						$individualI[$h]=$timeFormatI[$i];
						$individualI[$h]="$".($individualI[$h]/60)*$Tinter;
						$individual[$y]=$individualI[$h];

						$i++;
						$h++;
						$y++;
					}
					if ($timeFormatI){
						$valor2=array_sum($timeFormatI);
						//echo "INTERNACIONAL TIEMPO ",$valor2,"\n";
						$min[$t]=$valor2;
						$valor2=($valor2/60)*$Tinter;
						$costo[$t]=round($valor2,2);
						$internacional[$t]=$costo[$t];
						unset($timeFormatI);
						$t++;
					}
					$h=0;
					$sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
					communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
					" . $sql3 . ")
					UNION ALL
					(SELECT chargeduserid,suscribername, date, time, diallednumber,
					communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
					" . $sql3 . ")";

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
							<td><?php echo $individualI[$h] ?></td>

						</tr>
						<?php
						$h++;
					}
					$n++;
				}

				while ($n<28){ //NACIONAL
					$sql3="";
					$sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";
					$i=0;
					$h=0;
					$sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . ")
					UNION ALL
					(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . ")";

					$result2=mysqli_query($conexion,$sql2);

					while($ver2=mysqli_fetch_row($result2)){
						$valor1[$i]= $ver2[0];

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
						$timeFormatN[$i]=$hours1*3600+$mins1*60;
						//COSTO LLAMADA INDIVIDUAL
						$individualN[$h]=$timeFormatN[$i];
						$individualN[$h]="$".($individualN[$h]/60)*$Tnacion;
						$individual[$y]=$individualN[$h];
						$i++;
						$h++;
						$y++;
					}
					if ($timeFormatN){
						$valor3=array_sum($timeFormatN);
						//echo "NACIONAL TIEMPO ",$valor3,"\n";
						$min[$t]=$valor3;
						$valor3=($valor3/60)*$Tnacion;
						$costo[$t]=round($valor3,2);
						$nacional[$t]=$costo[$t];
						$t++;
						unset($timeFormatN);
					}
					$h=0;
					$sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
					communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
					" . $sql3 . ")
					UNION ALL
					(SELECT chargeduserid,suscribername, date, time, diallednumber,
					communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
					" . $sql3 . ")";

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
							<td><?php echo $individualN[$h] ?></td>

						</tr>
						<?php
						$h++;
					}
					$n++;
				}

				while ($n<32){ //LOCAL
					$sql3="";
					$sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";
					$i=0;
					$h=0;
					$sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . ")
					UNION ALL
					(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . ")";

					$result2=mysqli_query($conexion,$sql2);

					while($ver2=mysqli_fetch_row($result2)){
						$valor1[$i]= $ver2[0];

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
						$timeFormatL[$i]=$hours1*3600+$mins1*60;
						$individualL[$h]=$timeFormatL[$i];
						$individualL[$h]="$".($individualL[$h]/60)*$Tlocal;
						$individual[$y]=$individualL[$h];
						$i++;
						$h++;
						$y++;
					}
					if ($timeFormatL){
						$valor4=array_sum($timeFormatL);
						//echo "LOCAL TIEMPO ",$valor4,"\n";
						$min[$t]=$valor4;
						$valor4=($valor4/60)*$Tlocal;
						$costo[$t]=round($valor4,2);
						$local[$t]=$costo[$t];
						unset($timeFormatL);
						$t++;
					}
					$h=0;
					$sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
					communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
					" . $sql3 . ")
					UNION ALL
					(SELECT chargeduserid,suscribername, date, time, diallednumber,
					communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
					" . $sql3 . ")";

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
							<td><?php echo $individualL[$h] ?></td>

						</tr>
						<?php
						$h++;
					}
					$n++;
				}
				while ($n<33){ //CELULAR
					$sql3="";
					$sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";
					$i=0;
					$h=0;
					$sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . ")
					UNION ALL
					(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . ")";

					$result2=mysqli_query($conexion,$sql2);

					while($ver2=mysqli_fetch_row($result2)){
						$valor1[$i]= $ver2[0];

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
						$timeFormatC[$i]=$hours1*3600+$mins1*60;
						$individualC[$h]=$timeFormatC[$i];
						$individualC[$h]="$".($individualC[$h]/60)*$Tcel;
						$individual[$y]=$individualL[$h];
						$i++;
						$h++;
						$y++;
					}
					if ($timeFormatC){
						$valor5=array_sum($timeFormatC);
						//echo "CELULAR TIEMPO ",$valor5,"\n";
						$min[$t]=$valor5;
						$valor5=($valor5/60)*$Tcel;
						$costo[$t]=round($valor5,2);
						$celular[$t]=$costo[$t];
						unset($timeFormatC);
						$t++;
					}
					$h=0;
					$sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
					communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
					" . $sql3 . " AND " . $sql4 . ")
					UNION ALL
					(SELECT chargeduserid,suscribername, date, time, diallednumber,
					communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
					" . $sql3 . " AND " . $sql4 . ")";

					//echo $sql;
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
							<td><?php echo $individualC[$h] ?></td>

						</tr>
						<?php
						$h++;
					}
					$n++;
				}
				$internacional="$".array_sum($internacional);
				$nacional="$".array_sum($nacional);
				$local="$".array_sum($local);
				$celular="$".array_sum($celular);

				$tiempo=array_sum($min);
				$hours = floor($tiempo / 3600);
				$mins = floor($tiempo / 60 % 60);
				$secs = floor($tiempo % 60);
				$timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
				$tarifa="$".array_sum($costo);
				?>
				<td>TOTAL<td><td><td><td><td><td><td><td><?php echo $timeFormat ?><td><?php echo $tarifa ?></td></td>
			</table>
		</div>
	</div>
	<br>
	<table border="1">
		<tr>
			<td></td>
			<td>Internacional</td>
			<td>Nacional</td>
			<td>Local</td>
			<td>Celular</td>
		</tr>
		<tr>
			<td>Precio Min</td>
			<td><?php	echo "$".$Tinter ?></td>
			<td><?php	echo "$".$Tnacion ?></td>
			<td><?php	echo "$".$Tlocal ?></td>
			<td><?php	echo "$".$Tcel ?></td>
		</tr>
		<tr>
			<td>Total</td>
			<td><?php	echo $internacional ?></td>
			<td><?php	echo $nacional ?></td>
			<td><?php	echo $local ?></td>
			<td><?php	echo $celular ?></td>
		</tr>
	</table>
	<br>
	El precio total por la cantidad de minutos hablados es:
	<?php
	echo $tarifa;
	?>
	<br>
	<br>
	La cantidad de tiempo hablado es (hh/mm/ss):
	<?php
	echo $timeFormat;
	?>
	<br>
	<br>

	<div class="container">
		<div class="row">
			<div class="col-sm-16">
				<div class="panel panel-primary">
					<div class="panel panel-heading"> Graficos
					</div>

					<div class="panel panel-body">
						<div class="row">
							<div class="col-sm-6">
								<div id="cargaBarras">
								</div>
								<div class="col-sm-6">
									<div id="cargaBarrasM">
									</div>
								</div>
								<div class="col-sm-6">
									<div id="cargaBarrasSR">
									</div>
								</div>
								<br>
								<br>
								<div class="col-sm-6">
									<div id="cargaBarrasRC">
									</div>
								</div>
								<div class="col-sm-6">
									<div id="cargaBarrasE">
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>


	<br>
	<br>
	<br>
	<form class="form-inline" method="post" action="generate_pdf_agenda.php">
		<?php if ($tarifa) {?>
			<input type='hidden' name='tarifa' value='<?php echo "$tarifa";?>'/>
		<?php } if ($timeFormat) {?>
			<input type='hidden' name='timeFormat' value='<?php echo "$timeFormat";?>'/>
		<?php } if ($fecha) {?>
			<input type='hidden' name='dateFrom' value='<?php echo "$fecha";?>'/>
		<?php } if ($fecha1) {?>
			<input type='hidden' name='dateTo' value='<?php echo "$fecha1";?>'/>
		<?php } if ($internacional) {?>
			<input type='hidden' name='internacional' value='<?php echo "$internacional";?>'/>
		<?php } if ($nacional) {?>
			<input type='hidden' name='nacional' value='<?php echo "$nacional";?>'/>
		<?php } if ($local) {?>
			<input type='hidden' name='local' value='<?php echo "$local";?>'/>
		<?php } if ($celular) {?>
			<input type='hidden' name='celular' value='<?php echo "$celular";?>'/>
		<?php } if ($Tinter) {?>
			<input type='hidden' name='Tinter' value='<?php echo "$Tinter";?>'/>
		<?php } if ($Tnacion) {?>
			<input type='hidden' name='Tnacion' value='<?php echo "$Tnacion";?>'/>
		<?php } if ($Tlocal) {?>
			<input type='hidden' name='Tlocal' value='<?php echo "$Tlocal";?>'/>
		<?php } if ($Tcel) {?>
			<input type='hidden' name='Tcel' value='<?php echo "$Tcel";?>'/>
		<?php }	foreach ($individual as $identifier) {
			echo "<input type='hidden' name='costos[]' value='$identifier' />";
		} ?>
		<button type="submit" id="pdf" name="generate_pdf" class="btn btn-primary"><i class="fa fa-pdf" aria-hidden="true"></i>
			Generate PDF</button>
		</form>
		<form class="form-inline" method="post" action="generate_xls_agenda.php">
			<?php if ($tarifa) {?>
				<input type='hidden' name='tarifa' value='<?php echo "$tarifa";?>'/>
			<?php } if ($timeFormat) {?>
				<input type='hidden' name='timeFormat' value='<?php echo "$timeFormat";?>'/>
			<?php } if ($fecha) {?>
				<input type='hidden' name='dateFrom' value='<?php echo "$fecha";?>'/>
			<?php } if ($fecha1) {?>
				<input type='hidden' name='dateTo' value='<?php echo "$fecha1";?>'/>
			<?php } if ($internacional) {?>
				<input type='hidden' name='internacional' value='<?php echo "$internacional";?>'/>
			<?php } if ($nacional) {?>
				<input type='hidden' name='nacional' value='<?php echo "$nacional";?>'/>
			<?php } if ($local) {?>
				<input type='hidden' name='local' value='<?php echo "$local";?>'/>
			<?php } if ($celular) {?>
				<input type='hidden' name='celular' value='<?php echo "$celular";?>'/>
			<?php } if ($Tinter) {?>
				<input type='hidden' name='Tinter' value='<?php echo "$Tinter";?>'/>
			<?php } if ($Tnacion) {?>
				<input type='hidden' name='Tnacion' value='<?php echo "$Tnacion";?>'/>
			<?php } if ($Tlocal) {?>
				<input type='hidden' name='Tlocal' value='<?php echo "$Tlocal";?>'/>
			<?php } if ($Tcel) {?>
				<input type='hidden' name='Tcel' value='<?php echo "$Tcel";?>'/>
			<?php }	foreach ($individual as $identifier) {
				echo "<input type='hidden' name='costos[]' value='$identifier' />";
			} ?>
			<button type="submit" id="pdf" name="generate_pdf" class="btn btn-primary"><i class="fa fa-pdf" aria-hidden="true"></i>
				Generate XLS</button>
			</form>
		</body>
		</html>

		<script type="text/javascript">
		$(document).ready(function(){
			$('#cargaBarras').load('barra.php');
			$('#cargaBarrasM').load('barraM.php');
			$('#cargaBarrasSR').load('barraSR.php');
			$('#cargaBarrasRC').load('barraRC.php');
			$('#cargaBarrasE').load('barraE.php');
		});
		</script>
