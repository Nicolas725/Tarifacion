<?php
	include("./conexion.php");
?>

<html>
	<body>

    <form method="post" action="agenda1.php">
     Seleccione las fechas: <br>
     From:
     <input type="date" name="dateFrom" id="dateFrom" value="<?php echo date('2017-01-01'); ?>" min="2017-01-01" max="2019-12-31"/>
     <br/>
     To:
     <input type="date" name="dateTo" id="dateTo" value="<?php echo date('Y-m-d'); ?>" min="2017-01-01" max="2019-12-31"/>
     <br/>

     <div class="input-group">
       Seleccione el precio del minuto:
         <span class="input-group-addon">$</span>
         <input type="number" name="valor" value="0" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" class="form-control currency"/>
     </div>
     <br/>
     <input type="submit" value="Filtrar"/>
    </form>

  </body>
</html>
