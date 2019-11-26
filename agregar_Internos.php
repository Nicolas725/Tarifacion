<?php
include("./conexion.php");
session_start();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="librerias/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="librerias/alertifyjs/css/alertify.css">
	<link rel="stylesheet" type="text/css" href="librerias/alertifyjs/css/themes/default.css">
	<link rel="stylesheet" href="css/estilo3.css">
	<script src="librerias/jquery-3.2.1.min.js"></script>
	<script src="js/funcionesI.js"></script>
	<script src="librerias/bootstrap/js/bootstrap.js"></script>
	<script src="librerias/alertifyjs/alertify.js"></script>
</head>
<body >
	<div class="container">
		<div id="Internos_Tabla"></div>
	</div>
	<div class="modal fade" id="modalNuevoI" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Agrega nuevo interno</h4>
				</div>
				<div class="modal-body">
					<label>Nombre</label>
					<input type="text" name="" id="nombre" class="form-control input-sm" placeholder="Ingrese el nombre" required oninvalid="this.setCustomValidity('Ingrese el nombre aqui')" oninput="this.setCustomValidity('')">
					<label>Interno</label>
					<input type="text" name="" id="inter" class="form-control input-sm" placeholder="Ingrese el interno" required oninvalid="this.setCustomValidity('Ingrese el interno aqui')"  oninput="this.setCustomValidity('')">
					<!--<label>Sede</label>
					<input type="text" name="" id="sedeI" class="form-control input-sm" placeholder="Ingrese la sede" required oninvalid="this.setCustomValidity('Ingrese la sede aqui')"
					oninput="this.setCustomValidity('')">
					<label>Departamento</label>
					<input type="text" name="" id="deparI" class="form-control input-sm" placeholder="Ingrese el departamento">-->
					<label>Sede</label>:
					<?php
					$sql = "SELECT nombreSede FROM SEDES";
					$result = mysqli_query($conexion,$sql);
					echo "<select name='sede' id='sedeI' class='form-control input-sm' required>";
					echo "<option value=''></option>";
					while ($row = mysqli_fetch_array($result)) {
						echo "<option value='" . $row['nombreSede'] ."'>" . $row['nombreSede'] ."</option>";
					}
					echo "</select>";
					?>
					<label>Departamento</label>:
					<?php
					$sql = "SELECT nombreDepar FROM DEPARTAMENTOS";
					$result = mysqli_query($conexion,$sql);
					echo "<select name='depar' id='deparI' class='form-control input-sm'>";
					echo "<option value=''></option>";
					while ($row = mysqli_fetch_array($result)) {
						echo "<option value='" . $row['nombreDepar'] ."'>" . $row['nombreDepar'] ."</option>";
					}
					echo "</select>";
					?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal" id="guardarnuevoI">Agregar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="modalEdicionI" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Actualizar datos</h4>
				</div>
				<div class="modal-body">
					<input type="text" hidden="" id="id_Inter" name="">
					<label>Nombre</label>
					<input type="text" name="" id="nombreu" class="form-control input-sm" placeholder="Ingrese el nombre" required oninvalid="this.setCustomValidity('Ingrese el nombre aqui')" oninput="this.setCustomValidity('')">
					<label>Interno</label>
					<input type="text" name="" id="interu" class="form-control input-sm" placeholder="Ingrese el interno" required oninvalid="this.setCustomValidity('Ingrese el interno aqui')" oninput="this.setCustomValidity('')">
					<!--<label>Sede</label>
					<input type="text" name="" id="sedeIu" class="form-control input-sm" placeholder="Ingrese la sede" required oninvalid="this.setCustomValidity('Ingrese la sede aqui')"
					oninput="this.setCustomValidity('')">
					<label>Departamento</label>
					<input type="text" name="" id="deparIu" class="form-control input-sm" placeholder="Ingrese el departamento">
				-->
				<label>Sede</label>:
				<?php
				$sql = "SELECT nombreSede FROM SEDES";
				$result = mysqli_query($conexion,$sql);
				echo "<select name='sede' id='sedeIu' class='form-control input-sm' required>";
				echo "<option value=''></option>";
				while ($row = mysqli_fetch_array($result)) {
					echo "<option value='" . $row['nombreSede'] ."'>" . $row['nombreSede'] ."</option>";
				}
				echo "</select>";
				?>
				<label>Departamento</label>:
				<?php
				$sql = "SELECT nombreDepar FROM DEPARTAMENTOS";
				$result = mysqli_query($conexion,$sql);
				echo "<select name='depar' id='deparIu' class='form-control input-sm'>";
				echo "<option value=''></option>";
				while ($row = mysqli_fetch_array($result)) {
					echo "<option value='" . $row['nombreDepar'] ."'>" . $row['nombreDepar'] ."</option>";
				}
				echo "</select>";
				?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" id="actualizadatosI" data-dismiss="modal">Actualizar</button>
			</div>
		</div>
	</div>
</div>



<form class="form-inline" action="internos.php" method="post">

	<button type="submit" class="btn btn-primary text-right"><i class="fa fa-pdf" aria-hidden="true"></i>
		Actualizar Internos</button>

</form>

	<script type="text/javascript">
	$(document).ready(function(){
		$('#Internos_Tabla').load('Internos_Tabla.php');
	});
</script>
<script type="text/javascript">
$(document).ready(function(){
	$('#guardarnuevoI').click(function(){
		nombre=$('#nombre').val();
		inter=$('#inter').val();
		sede=$('#sedeI').val();
		depar=$('#deparI').val();
		agregardatosI(nombre,inter,sede,depar);
	});
	$('#actualizadatosI').click(function(){
		actualizaDatosI();
	});
});
</script>
<a href="index.php" class="btn btn-primary">Volver</a>
<a href="salir.php" class="btn btn-danger">Cerrar Sesion</a>
</body>
</html>
