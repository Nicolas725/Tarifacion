
<?php
include("./conexion.php");
 ?>
<div class="row">
	<div class="col-sm-12">
	<h2>Internos</h2>
		<table class="table table-hover table-condensed table-bordered">
		<caption>
			<button class="btn btn-primary" data-toggle="modal" data-target="#modalNuevoI">
				Agregar Interno
				<span class="glyphicon glyphicon-plus"></span>
			</button>
		</caption>
			<tr>
        <td>Nombre</td>
        <td>Interno</td>
        <td>Sede</td>
        <td>Departamento</td>
				<td>Editar</td>
				<td>Eliminar</td>
			</tr>

			<?php

				$sql="SELECT id_I, suscribername, chargeduserid, nombreSede,nombreDepar FROM INTERNOS";
				$result=mysqli_query($conexion,$sql);
				while($ver=mysqli_fetch_row($result)){
          $datosI=$ver[0]."||".
						   $ver[1]."||".
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
          data-toggle="modal" data-target="#modalEdicionI" onclick="agregaformI('<?php echo $datosI ?>')">
					</button>
				</td>
				<td>
					<button class="btn btn-danger glyphicon glyphicon-remove"
					onclick="preguntarSiNoI('<?php echo $ver[0] ?>')">
					</button>
				</td>
			</tr>
			<?php
		}
			 ?>
		</table>
	</div>
</div>
