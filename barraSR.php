<?php
include("./conexion.php");
error_reporting(E_ERROR | E_PARSE); //hace que no se muestren los warning
session_start();
$fecha=$_SESSION['dateFrom'];
$fecha1=$_SESSION['dateTo'];

$depar=array();

$sql = "SELECT nombreSede FROM SEDES WHERE nombreSede LIKE 'San Rafael%'";
$result = mysqli_query($conexion,$sql);
$n=0;
while ($ver=mysqli_fetch_row($result)){
  $sedes= $ver[0];
}
$sql1 = "SELECT nombreDepar FROM DEPARTAMENTOS  WHERE nombreDepar LIKE 'SR-%'";
$result1 = mysqli_query($conexion,$sql1);
$n1=0;
while ($ver1=mysqli_fetch_row($result1)){
  $depar[$n1]= $ver1[0];
  $n1++;
}

$min=[];
$t=0;
$i=0;
for ($p=0; $p < $n1 ; $p++) {
  $sql3="";
  $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND nombreSede ='$sedes' AND nombreDepar LIKE '$depar[$p]%' ";
  $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . ")
  UNION ALL
  (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . ")";

  $result2=mysqli_query($conexion,$sql2);
  while($ver2=mysqli_fetch_row($result2)){
    $valor1[$i]= $ver2[0];
    //echo "VER2 DE ".$depar[$p]."  =   ".$ver2[0],"\n";
    $hours1 = floor($valor1[$i] / 3600);
    $mins1 = floor($valor1[$i] / 60 % 60);
    $secs1 = floor($valor1[$i] % 60);

    if ($secs1>=1){
      $mins1=$mins1+1;
      if ($mins1>59){
        $mins1=0;
        $hours1=$hours1+1;
      }
      $secs1=0;
    }

    $timeFormat1 = sprintf('%02d:%02d:%02d', $hours1, $mins1, $secs1);
    ceil($timeFormat1);
    //echo "timeFormat1 =".$timeFormat1,"\n";
    $timeFormat[$i]=$hours1*3600+$mins1*60;
    //echo "timeFormat[".$i."] =".$timeFormat[$i],"\n";
    //$tiempo[$p]=$timeFormat[$i];
    $i++;
  }
  if ($sedes[$p]){
    $valor2=array_sum($timeFormat);
    //echo "TIEMPO ",$valor2,"\n";
    //$min[$t]=$valor2."|".$sedes[$p];
    $min[$t]=$valor2/60;
    //echo $min[$t],"\n";

    unset($timeFormat);
    $t++;
  }
}

$datosX=json_encode($min);
$datosY=json_encode($depar);

//$datosX=json_encode($valoresX);
//$datosY=json_encode($valoresY);

?>
<div id="graficoBarrasSR"><div>

  <script type="text/javascript">
  function crearCadenaBarrasSR(json){
    var parsed = JSON.parse(json);
    var arr = [];
    for (var x in parsed){
      arr.push(parsed[x]);
    }
    return arr;
  }

</script>

<script type="text/javascript">

datosX = crearCadenaBarrasSR('<?php echo $datosX ?>');
datosY = crearCadenaBarrasSR('<?php echo $datosY ?>');

var data = [
  {
    x: datosY,
    y: datosX,
    type: 'bar'
  }
];
var layout = {
  title: 'San Rafael',
  xaxis: {
    title: 'Departamentos',
    tickfont: {
      size: 14,
      color: 'rgb(107, 107, 107)'
    }},
  yaxis: {
    title: 'Tiempo en min',
    titlefont: {
      size: 16,
      color: 'rgb(107, 107, 107)'
      }
  },
};

Plotly.newPlot('graficoBarrasSR', data, layout);
</script>
