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
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/estilo2.css">
    <link rel="stylesheet" href="icono.min.css">
  </head>
	<body>
		<div class="row">
			<div class="container">
				<div class="col-md-12 col-center">
					<br><br><br><br><br><br><br><br>
    <form method="post" action="agenda1.php">
			<tr><td><label style="font-size: 12pt"><b>Seleccione las fechas:</b></label></td>

		 <br>
     From:
     <input type="date" name="dateFrom" id="dateFrom" value="<?php echo date('2017-01-01'); ?>" min="2017-01-01" max="2019-12-31"/>
     <br/>
     To:
     <input type="date" name="dateTo" id="dateTo" value="<?php echo date('Y-m-d'); ?>" min="2017-01-01" max="2019-12-31"/>
     <br/>

     <div class="container">
			 <tr><td><label style="border-radius:10px;"><b>Seleccione el precio del minuto:</b></label></td>

         <span class="input-group-addon">$</span>
         <input type="number" name="valor" value="0" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" class="form-control currency"/>
     </div>
     <br/>
     <input type="submit" value="Filtrar" class="btn btn-primary"/>
		 <br>
		 <br>
    </form>
		<a href="index.php" class="btn btn-primary">Volver</a>
		<a href="salir.php" class="btn btn-danger">Cerrar Sesion</a>
				</div>
			</div>
		</div>

  </body>
</html>
