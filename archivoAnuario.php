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
    <form method="post" action="archivoAnuario2.php" enctype="multipart/form-data">
         ¡Subir Anuario para la base de datos!: <input type="file" name="archivo" size="10000"/>
    		 <input type="submit" name="enviar" value="Enviar" />
    </form>
    <script src="js/jquery.js" charset="utf-8"></script>
    <script src="js/bootstrap.min.js" charset="utf-8"></script>
  </body>
</html>
