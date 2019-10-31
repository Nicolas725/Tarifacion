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
    <link rel="stylesheet" href="css/estilo2.css">
    <link rel="stylesheet" href="icono.min.css">
  </head>
  <body>
    <?php
        if($_SESSION['ucontrol']){
    ?>
    <br>
    <div class="container">
  		<div id="tabla"></div>
  	</div>
      <?php
        }
        else{
            header("location: login.php");
        }
      ?>
    <script src="js/jquery.js" charset="utf-8"></script>
    <script src="js/bootstrap.min.js" charset="utf-8"></script>
  </body>
</html>
<script type="text/javascript">
	$(document).ready(function(){
		$('#tabla').load('tabla_salientes.php');
	});
</script>
