<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Software de Tarifacion</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1, minimum-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/estilos.css">
  </head>
  <body class="background">
    <div class="row">
      <div class="container">
        <br><br>
        <div class="col-md-12 col-center">
          <tr><td><label style="font-size: 20pt">Software de Tarifacion</label></td></tr>
        </div>
        <br><br>
      </div>
    </div>
    <div class="row">
      <div class="container">
        <div class="col-md-12 col-center">
          <?php
      			print "<img src=\"imagenes/UM_logo.jpg\">"
      		?>
        </div>
      </div>
    </div>
		<br><br>
    <div class="row">
      <div class="container">
        <div class="col-md-12 col-center">
          <br><br><br><br><br><br><br><br><br><br>
          <form action="verificar.php" method="post">
      			<tr><td><label style="font-size: 12pt"><b>Usuario: </b></label></td>
      			<td width=80> <input style="border-radius:10px;" type="text" name="usuario" placeholder="Ingresa tu usuario"></td></tr>
      			<br>
      			<tr><td><label style="font-size: 12pt"><b>Contrasena: </b></label></td>
      			<td width=80><input style="border-radius:10px;" type="password" name="clave" placeholder="Ingresa tu contrasena"></td></tr>
      			<br><br>
      			<td width=80 align=center><input class="btn btn-primary" type="submit" value="Aceptar"></td>
      		</form>
        </div>
      </div>
    </div>
    <script src="js/jquery.js" charset="utf-8"></script>
    <script src="js/bootstrap.min.js" charset="utf-8"></script>
  </body>
</html>
