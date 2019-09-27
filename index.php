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

    <script src="jquery-3.2.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="icono.min.css">
  </head>
  <body class="background">
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
                  <td><?php
                  print "<img src=\"imagenes/officedatabase_103574.ico\">"
                  ?></td>
			            <td><?php
				          print "<img src=\"imagenes/actualizar.ico\">"
			            ?></td>
			            <td><?php
				          print "<img src=\"imagenes/phone-icon_34382.ico\">"
			            ?></td>
            </tr>
            <tr>
		            <td><a href="archivoAnuario.php" class="btn btn-primary">Crear Anuario</a></td>
		            <td><a href="actualizar.php" class="btn btn-primary">Modificar</a></td>
		            <td><a href="listar_salientes.php" class="btn btn-primary">Llamadas Salientes</a></td>
            </tr>

            <tr>
                 <td><?php
                 print "<img src=\"imagenes/deptos.ico\">"
                 ?></td>
                 <td><?php
                 print "<img src=\"imagenes/address-book.ico\">"
                 ?></td>
                 <td><?php
                 print "<img src=\"imagenes/listado.ico\">"
                 ?></td>
            </tr>
            <tr>
               <td><a href="agrDptos.php" class="btn btn-primary">Agrupar por Sede y Dpto.</a></td>
               <td><a href="agenda.php" class="btn btn-primary">Crear Agenda</a></td>
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
