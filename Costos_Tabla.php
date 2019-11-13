
<?php
include("./conexion.php");
?>
<div class="row">
  <div class="col-sm-12">
    <h2>Costos</h2>
    <table class="table table-hover table-condensed table-bordered">
      <tr>
        <td>Llamadas</td>
        <td>Tarifa $</td>
        <td>From</td>
        <td>To</td>
        <td>Editar</td>
      </tr>
      
      <?php

      $sql="SELECT * FROM Costos";
      $result=mysqli_query($conexion,$sql);
      while($ver=mysqli_fetch_row($result)){
        $datosT=$ver[0]."||".
        $ver[2]."||".
        $ver[3]."||".
        $ver[4];
        ?>

        <tr>
          <td><?php echo $ver[1] ?></td>
          <td><?php echo $ver[2] ?></td>
          <td><?php echo $ver[3] ?></td>
          <td><?php echo $ver[4] ?></td>
          <td>
            <button class="btn btn-warning glyphicon glyphicon-pencil"
            data-toggle="modal" data-target="#modalEdicionT" onclick="agregaformT('<?php echo $datosT ?>')">
          </button>
        </td>
      </tr>
      <?php
    }
    ?>
  </table>
</div>
</div>
