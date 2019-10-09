
//SEDES
function agregardatos(sede){

	cadena="sede=" + sede;
	$('#sede').val('');

	$.ajax({
		type:"POST",
		url:"Sedes_agregar.php",
		data:cadena,
		success:function(r){
			if(r==1){
				$('#Sedes_Tabla').load('Sedes_Tabla.php');
				alertify.success("agregado con exito :)");
			}else{
				alertify.error("Fallo el servidor :(");
			}
		}
	});

}




function agregaform(datos){
	d=datos.split('||');

	$('#id_Sede').val(d[0]);
	$('#sedeu').val(d[1]);

}

function actualizaDatos(){


	id_S=$('#id_Sede').val();
	sede=$('#sedeu').val();

	cadena= "id_S=" + id_S +
			"&sede=" + sede;

	$.ajax({
		type:"POST",
		url:"Sedes_act.php",
		data:cadena,
		success:function(r){

			if(r==1){
				$('#Sedes_Tabla').load('Sedes_Tabla.php');
				alertify.success("Actualizado con exito :)");
			}else{
				alertify.error("Fallo el servidor :(");
			}
		}
	});

}

function preguntarSiNo(id_S){
	alertify.confirm('Eliminar Datos', '¿Esta seguro de eliminar este registro?',
					function(){ eliminarDatos(id_S) }
                , function(){ alertify.error('Se cancelo')});
}

function eliminarDatos(id_S){

	cadena="id_S=" + id_S;

		$.ajax({
			type:"POST",
			url:"Sedes_eliminar.php",
			data:cadena,
			success:function(r){
				if(r==1){
					$('#Sedes_Tabla').load('Sedes_Tabla.php');
					alertify.success("Eliminado con exito!");
				}else{
					alertify.error("Fallo el servidor :(");
				}
			}
		});
}


//DEPARTAMENTOS


function agregardatosD(depar){

	cadena="depar=" + depar;
	$('#depar').val('');

	$.ajax({
		type:"POST",
		url:"Depar_agregar.php",
		data:cadena,
		success:function(r){
			if(r==1){
				$('#Departamentos_Tabla').load('Departamentos_Tabla.php');
				alertify.success("agregado con exito :)");
			}else{
				alertify.error("Fallo el servidor :(");
			}
		}
	});

}




function agregaformD(datosD){
	d=datosD.split('||');

	$('#id_Depar').val(d[0]);
	$('#deparu').val(d[1]);

}

function actualizaDatosD(){


	id_D=$('#id_Depar').val();
	depar=$('#deparu').val();

	cadena= "id_D=" + id_D +
			"&depar=" + depar;

	$.ajax({
		type:"POST",
		url:"Depar_act.php",
		data:cadena,
		success:function(r){

			if(r==1){
				$('#Departamentos_Tabla').load('Departamentos_Tabla.php');
				alertify.success("Actualizado con exito :)");
			}else{
				alertify.error("Fallo el servidor :(");
			}
		}
	});

}

function preguntarSiNoD(id_D){
	alertify.confirm('Eliminar Datos', '¿Esta seguro de eliminar este registro?',
					function(){ eliminarDatosD(id_D) }
                , function(){ alertify.error('Se cancelo')});
}

function eliminarDatosD(id_D){

	cadena="id_D=" + id_D;

		$.ajax({
			type:"POST",
			url:"Depar_eliminar.php",
			data:cadena,
			success:function(r){
				if(r==1){
					$('#Departamentos_Tabla').load('Departamentos_Tabla.php');
					alertify.success("Eliminado con exito!");
				}else{
					alertify.error("Fallo el servidor :(");
				}
			}
		});
}
