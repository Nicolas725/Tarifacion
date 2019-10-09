<?php
	include("./conexion.php");
?>

<html>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="librerias/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="librerias/alertifyjs/css/alertify.css">
	<link rel="stylesheet" type="text/css" href="librerias/alertifyjs/css/themes/default.css">

	<script src="librerias/jquery-3.2.1.min.js"></script>
  <script src="js/funciones.js"></script>
	<script src="librerias/bootstrap/js/bootstrap.js"></script>
	<script src="librerias/alertifyjs/alertify.js"></script>
</head>
	<body>
		<div class="container">
		<div id="Sedes_Tabla"></div>
		</div>

		<div class="container">
		<div id="Departamentos_Tabla"></div>
		</div>

  <div class="modal fade" id="modalNuevo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Agrega nueva sede</h4>
        </div>
        <div class="modal-body">
          	<label>Sede</label>
          	<input type="text" name="" id="sede" class="form-control input-sm">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal" id="guardarnuevo">
          Agregar
          </button>

        </div>
      </div>
    </div>
  </div>


  <div class="modal fade" id="modalEdicion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Actualizar datos</h4>
        </div>
        <div class="modal-body">
            <input type="text" hidden="" id="id_Sede" name="">
        	 <label>Sede</label>
          	<input type="text" name="" id="sedeu" class="form-control input-sm">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning" id="actualizadatos" data-dismiss="modal">Actualizar</button>

        </div>
      </div>
    </div>
  </div>

	<div class="modal fade" id="modalNuevoD" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Agrega nuevo departamento</h4>
				</div>
				<div class="modal-body">
						<label>Departamento</label>
						<input type="text" name="" id="depar" class="form-control input-sm">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal" id="guardarnuevoD">
					Agregar
					</button>

				</div>
			</div>
		</div>
	</div>


	<div class="modal fade" id="modalEdicionD" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Actualizar datos</h4>
				</div>
				<div class="modal-body">
						<input type="text" hidden="" id="id_Depar" name="">
					 <label>Departamento</label>
						<input type="text" name="" id="deparu" class="form-control input-sm">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-warning" id="actualizadatosD" data-dismiss="modal">Actualizar</button>

				</div>
			</div>
		</div>
	</div>

  <script type="text/javascript">
  	$(document).ready(function(){
  		$('#Sedes_Tabla').load('Sedes_Tabla.php');
  	});
  </script>

  <script type="text/javascript">
      $(document).ready(function(){
          $('#guardarnuevo').click(function(){
            sede=$('#sede').val();
              agregardatos(sede);
          });

          $('#actualizadatos').click(function(){
            actualizaDatos();
          });

      });
  </script>

	<script type="text/javascript">
		$(document).ready(function(){
			$('#Departamentos_Tabla').load('Departamentos_Tabla.php');
		});
	</script>

	<script type="text/javascript">
			$(document).ready(function(){
					$('#guardarnuevoD').click(function(){
						depar=$('#depar').val();
							agregardatosD(depar);
					});

					$('#actualizadatosD').click(function(){
						actualizaDatosD();
					});

			});
	</script>


</body></html>