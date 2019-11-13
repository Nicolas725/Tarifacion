<?php
include("./conexion.php");
//$option = isset($_POST['taskOption']) ? $_POST['taskOption'] : false;
$option = isset($_POST['inter1']) ? $_POST['inter1'] : false;
$fecha = isset($_POST['date']) ? $_POST['date'] : false;
$sede = isset($_POST['sed']) ? $_POST['sed'] : false;
$depar = isset($_POST['depar']) ? $_POST['depar'] : false;

if ($option || $fecha || $sede || $depar){
  ?>
  <form method="post" action="process1_entrantes.php">
    <?php if ($fecha) {?>
      Seleccione las fechas: <br>
      From:
      <input type="date" name="dateFrom" id="dateFrom" value="<?php echo date('2017-01-01'); ?>" min="2017-01-01" max="2017-12-31"/>
      <br/>
      To:
      <input type="date" name="dateTo" id="dateTo" value="<?php echo date('Y-m-d'); ?>" min="2017-01-01" max="2019-12-31"/>
    <?php } if ($option) {?>
      <br/>
      Seleccione el interno:
      <input type="number" name="inter" id="inter" value="100" min="100" max="600">
    <?php } if ($sede) {?>
      <br/>
      Seleccione la Sede:
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
      Seleccione el Departamento:
      <?php
      $sql = "SELECT nombreDepar FROM DEPARTAMENTOS";
      $result = mysqli_query($conexion,$sql);
      echo "<select name='nombreDepar'>";
      while ($row = mysqli_fetch_array($result)) {
        echo "<option value='" . $row['nombreDepar'] ."'>" . $row['nombreDepar'] ."</option>";
      }
      echo "</select>";
    }
    ?>
    <input type="submit" value="Filtrar"/>
  </form>
  <?php
}
?>
