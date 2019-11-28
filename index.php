<?php
session_start();
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
  <?php
  if($_SESSION['ucontrol']){
    ?>
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <br>
          <h2>SOTFWARE DE TARIFACION</h2>
          <table class="table table-hover table-condensed">
            <tr>
              <td>
                <?php
                print "<img src=\"imagenes/officedatabase_103574.ico\">"
                ?>
              </td>
              <td>
                <?php
                print "<img src=\"imagenes/UM_logo2.jpg\">"
                ?>
              </td>
              <td>
                <?php
                print "<img src=\"imagenes/phone-icon_34382.ico\">"
                ?></td>
              </tr>
              <tr>
                <td><a href="agregar_Internos.php" class="btn btn-primary">Crear Internos</a></td>
                <td></td>
                <td><a href="listar_salientes.php" class="btn btn-primary">Llamadas Salientes</a></td>
              </tr>
              <tr>
                <td>
                  <?php
                  print "<img src=\"imagenes/deptos.ico\">"
                  ?>
                </td>
                <td>
                  <?php
                  print "<img src=\"imagenes/qr-code.png\">"
                  ?>
                </td>
                <td>
                  <?php
                  print "<img src=\"imagenes/listado.ico\">"
                  ?>
                </td>
              </tr>
              <tr>
                <td><a href="agregar_S_D.php" class="btn btn-primary">Crear Sede y/o Dpto.</a></td>
                <td><a href="agenda.php" class="btn btn-primary">Tarifacion</a></td>
                <td><a href="listar_entrantes.php" class="btn btn-primary">Llamadas Entrantes</a></td>
              </tr>
              <br>
              <tr>
                <td></td>
                <td><a href="salir.php" class="btn btn-danger">Cerrar Sesion</a></td>
                <td></td>
              </tr>
            </table>
            <br><br>
            <?php
          }
          else{
            header("location: login.php");
          }
          ?>
          <script src="js/jquery.js" charset="utf-8"></script>
          <script src="js/bootstrap.min.js" charset="utf-8"></script>
        </div>
      </div>
    </div>
  </body>
  </html>
