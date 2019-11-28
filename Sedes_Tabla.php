
<?php
include("./conexion.php");
?>
<div class="row">
  <div class="col-sm-12">
    <h2>Sedes</h2>
    <table class="table table-hover table-condensed table-bordered">
      <caption>
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalNuevo">
          Agregar Sede
          <span class="glyphicon glyphicon-plus"></span>
        </button>
      </caption>
      <tr>
        <td>Sedes</td>
        <td>Editar</td>
        <td>Eliminar</td>
      </tr>
 
      <?php

      $sql="SELECT id_S, nombreSede FROM SEDES";
      $result=mysqli_query($conexion,$sql);
      while($ver=mysqli_fetch_row($result)){
        $datos=$ver[0]."||". $ver[1];
        ?>

        <tr>
          <td><?php echo $ver[1] ?></td>
          <td>
            <button class="btn btn-warning glyphicon glyphicon-pencil"
            data-toggle="modal" data-target="#modalEdicion" onclick="agregaform('<?php echo $datos ?>')">
          </button>
        </td>
        <td>
          <button class="btn btn-danger glyphicon glyphicon-remove"
          onclick="preguntarSiNo('<?php echo $ver[0] ?>')">

        </button>
      </td>
    </tr>
    <?php
  }
  ?>
</table>
</div>
</div>
