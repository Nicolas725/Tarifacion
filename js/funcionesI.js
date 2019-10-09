//INTERNOS

function agregardatosI(nombre,inter,sede,depar){

	cadena="nombre=" + nombre +
			"&inter=" + inter +
			"&sede=" + sede +
			"&depar=" + depar;

		$('#nombre').val('');
		$('#inter').val('');
		$('#sedeI').val('');
		$('#deparI').val('');

	$.ajax({
		type:"POST",
		url:"Internos_agr.php",
		data:cadena,
		success:function(r){
			if(r==1){
				$('#Internos_Tabla').load('Internos_Tabla.php');
				alertify.success("Agregado con exito");
			}else{
				alertify.error("Los campos deben estar completos");
			}
		}
	});

}




function agregaformI(datosI){
	d=datosI.split('||');

	$('#id_Inter').val(d[0]);
	$('#nombreu').val(d[1]);
	$('#interu').val(d[2]);
	$('#sedeIu').val(d[3]);
	$('#deparIu').val(d[4]);

}

function actualizaDatosI(){


	id=$('#id_Inter').val();
	nombre=$('#nombreu').val();
	inter=$('#interu').val();
	sede=$('#sedeIu').val();
	depar=$('#deparIu').val();

	cadena="id=" + id +
			"&nombre=" + nombre +
			"&inter=" + inter +
			"&sede=" + sede +
			"&depar=" + depar;

	$.ajax({
		type:"POST",
		url:"Internos_act.php",
		data:cadena,
		success:function(r){

			if(r==1){
				$('#Internos_Tabla').load('Internos_Tabla.php');
				alertify.success("Actualizado con exito");
			}else{
				alertify.error("Los campos deben estar completos");
			}
		}
	});

}

function preguntarSiNoI(id_I){
	alertify.confirm('Eliminar Datos', '¿Esta seguro de eliminar este registro?',
					function(){ eliminarDatosI(id_I) }
                , function(){ alertify.error('Se cancelo')});
}

function eliminarDatosI(id_I){
	cadena="id_I=" + id_I;

		$.ajax({
			type:"POST",
			url:"Internos_eliminar.php",
			data:cadena,
			success:function(r){
				if(r==1){
					$('#Internos_Tabla').load('Internos_Tabla.php');
					alertify.success("Eliminado con exito!");
				}else{
					alertify.error("Fallo el servidor");
				}
			}
		});
}
