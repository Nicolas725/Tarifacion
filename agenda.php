<?php
include("./conexion.php");
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
	<meta charset="utf-8">
	<title>Software de Tarifacion</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="librerias/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="librerias/alertifyjs/css/alertify.css">
	<link rel="stylesheet" type="text/css" href="librerias/alertifyjs/css/themes/default.css">
	<link rel="stylesheet" href="css/estilo3.css">
	<script src="librerias/jquery-3.2.1.min.js"></script>
	<script src="js/funcionesT.js"></script>
	<script src="librerias/bootstrap/js/bootstrap.js"></script>
	<script src="librerias/alertifyjs/alertify.js"></script>

</head>
<body>

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
							<h2>Período de Tarifacíon</h2>
						</td>


						<td>
						</td>

						<td>
						</td>

						<td>
						</td>

					</tr>
					<tr>

						<td>
						</td>

						<td>
						</td>

						<td>
							<?php
							print "<img src=\"imagenes/UM_logo3.jpg\">"
							?>
						</td>

						<td>
						</td>

						<td>
						</td>

						<td>
						</td>

					</tr>
					<tr>
						<td>
							<a href="index.php" class="btn btn-primary">Volver</a>
						</td>

						<td>
						</td>

						<td>
						</td>

						<td>
						</td>

						<td>
							<a href="salir.php" class="btn btn-danger">Cerrar Sesion</a>
						</td>

					</tr>


				</table>
			</div>
		</div>
	</div>






	<div class="container">
		<div id="Costos_Tabla"></div>
	</div>

	<div class="modal fade" id="modalEdicionT" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Actualizar datos</h4>
				</div>
				<div class="modal-body">
					<input type="text" hidden="" id="id_T" name="">
					<input type="text" hidden="" id="Llamada" name="">
					<span class="input-group-addon">Tarifa $</span>
					<input type="number" name="" id="Tarifa"  min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" class="form-control input-sm currency" placeholder="Ingrese la tarifa">
					<label>From</label>
					<input type="date" name="" id="Fromf" class="form-control input-sm" placeholder="Ingrese la tarifa">
					<label>To</label>
					<input type="date" name="" id="Tof" class="form-control input-sm" placeholder="Ingrese la tarifa">

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-warning" id="actualizadatosT" data-dismiss="modal">Actualizar</button>
				</div>
			</div>
		</div>
	</div>


	<div class="row">
		<div class="container">
			<div class="col-md-12 col-center">
				<br><br>
				<form method="post" action="agenda1.php">
					<tr><td><label style="font-size: 12pt"><b>Seleccione las fechas por las que desea filtrar:</b></label></td>
						<br>
						From:
						<input type="date" name="dateFrom" id="dateFrom" value="<?php echo date('2017-01-01'); ?>" min="2017-01-01" max="2019-12-31"/>
						<br/>
						To:
						<input type="date" name="dateTo" id="dateTo" value="<?php echo date('Y-m-d'); ?>" min="2017-01-01" max="2019-12-31"/>
						<br/>

						<?php
						$p=0;
						$sql = "SELECT Tarifa FROM Costos";
						$result = mysqli_query($conexion,$sql);
						while ($ver = mysqli_fetch_array($result)) {
							if ($p==0){
								$datos=$ver['Tarifa'];
								$p++;
							}
							else if ($p==1){
								$datos1=$ver['Tarifa'];
								$p++;
							}
							else if ($p==2){
								$datos2=$ver['Tarifa'];
								$p++;
							}
							else {
								$datos3=$ver['Tarifa'];
								$p++;
							}
						}
						echo "<input type='hidden' id='valor' name='inter' value='".$datos."'>";
						echo "<input type='hidden' id='valor' name='nacion' value='".$datos1."'>";
						echo "<input type='hidden' id='valor' name='local' value='".$datos2."'>";
						echo "<input type='hidden' id='valor' name='cel' value='".$datos3."'>";
						?>

						<input type="submit" value="Filtrar" class="btn btn-primary"/>
						<br>
						<br>
					</form>


					<script type="text/javascript">
					$(document).ready(function(){
						$('#Costos_Tabla').load('Costos_Tabla.php');
					});
					</script>
					<script type="text/javascript">
					$(document).ready(function(){
						$('#actualizadatosT').click(function(){
							actualizaDatosT();
						});
					});
					</script>
				</div>
			</div>
		</div>

	</body>
	</html>
