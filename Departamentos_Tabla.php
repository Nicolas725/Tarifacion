
<?php
include("./conexion.php");
?>
<div class="row">
  <div class="col-sm-12">
    <h2>Departamentos</h2>
    <table class="table table-hover table-condensed table-bordered">
      <caption>
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalNuevoD">
          Agregar Departamento
          <span class="glyphicon glyphicon-plus"></span>
        </button>
      </caption>
      <tr>
        <td>Departamentos</td>
        <td>Editar</td>
        <td>Eliminar</td>
      </tr>

      <?php

      $sql="SELECT id_D, nombreDepar FROM DEPARTAMENTOS";
      $result=mysqli_query($conexion,$sql);
      while($ver=mysqli_fetch_row($result)){
        $datosD=$ver[0]."||". $ver[1];
        ?>

        <tr>
          <td><?php echo $ver[1] ?></td>
          <td>
            <button class="btn btn-warning glyphicon glyphicon-pencil"
            data-toggle="modal" data-target="#modalEdicionD" onclick="agregaformD('<?php echo $datosD ?>')">
          </button>
        </td>
        <td>
          <button class="btn btn-danger glyphicon glyphicon-remove"
          onclick="preguntarSiNoD('<?php echo $ver[0] ?>')">

        </button>
      </td>
    </tr>
    <?php
  }
  ?>
</table>
</div>
</div>
