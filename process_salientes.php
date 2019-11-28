<?php
include("./conexion.php");
//$option = isset($_POST['taskOption']) ? $_POST['taskOption'] : false;
$option = isset($_POST['inter1']) ? $_POST['inter1'] : false;
$fecha = isset($_POST['date']) ? $_POST['date'] : false;
$sede = isset($_POST['sed']) ? $_POST['sed'] : false;
$depar = isset($_POST['depar']) ? $_POST['depar'] : false;

if ($option || $fecha || $sede || $depar){
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
        <br>
				<h2>Llamadas Salientes</h2>
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
    <form method="post" action="process1_salientes.php">
      <?php if ($fecha) {?>
        Seleccione las fechas: <br><br>
        From:
        <input type="date" name="dateFrom" id="dateFrom" value="<?php echo date('2017-01-01'); ?>" min="2017-01-01" max="2019-12-31"/><br>
        <br/>
        To:
        <input type="date" name="dateTo" id="dateTo" value="<?php echo date('Y-m-d'); ?>" min="2017-01-01" max="2019-12-31"/><br>
      <?php } if ($option) {?>
        <br/>
        Seleccione el interno:
        <input type="number" name="inter" id="inter" value="100" min="100" max="600"><br>
      <?php } if ($sede) {?>
        <br/>
        Seleccione la Sede:<br>
        <?php
        $sql = "SELECT nombreSede FROM SEDES";
        $result = mysqli_query($conexion,$sql);
        echo "<select name='sede'>";
        while ($row = mysqli_fetch_array($result)) {
          echo "<option value='" . $row['nombreSede'] ."'>" . $row['nombreSede'] ."</option>";
        }
        echo "</select>";
      }
      if ($depar) {?>
        <br/>
        <br>Seleccione el Departamento:
        <?php
        $sql = "SELECT nombreDepar FROM DEPARTAMENTOS";
        $result = mysqli_query($conexion,$sql);
        echo "<select name='nombreDepar'>";
        echo "<option value=''></option>";
        while ($row = mysqli_fetch_array($result)) {
          echo "<option value='" . $row['nombreDepar'] ."'>" . $row['nombreDepar'] ."</option>";
        }
        echo "</select>";
      }
      ?>
      <br><br><input type="submit" value="Filtrar" class="btn btn-primary">
    </form>
    <?php
  }
  ?>
</div>
</div>
</div>
</body>
</html>
