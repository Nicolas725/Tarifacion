//INTERNOS
function agregaformT(datosT){
	d=datosT.split('||');

	$('#id_T').val(d[0]);
	$('#Tarifa').val(d[1]);
	$('#Fromf').val(d[2]);
	$('#Tof').val(d[3]);
}

function actualizaDatosT(){


	id=$('#id_T').val();
	Tarifa=$('#Tarifa').val();
	Fromf=$('#Fromf').val();
	Tof=$('#Tof').val();

	cadena="id=" + id +
	"&Tarifa=" + Tarifa +
	"&Fromf=" + Fromf +
	"&Tof=" + Tof;

	$.ajax({
		type:"POST",
		url:"Costos_act.php",
		data:cadena,
		success:function(r){

			if(r==1){
				$('#Costos_Tabla').load('Costos_Tabla.php');
				alertify.success("Actualizado con exito");
			}else{
				alertify.error("Los campos no están completos");
			}
		}
	});

}
