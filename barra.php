<?php
include("./conexion.php");
error_reporting(E_ERROR | E_PARSE); //hace que no se muestren los warning

$sedes=array();
$datos=array(); //monto
$datos1=array(); //fecha
$sql = "SELECT nombreSede FROM SEDES";
$result = mysqli_query($conexion,$sql);
$n=0;
while ($ver=mysqli_fetch_row($result)){
  $sedes[$n]= $ver[0];
  $n++;
}
$min=[];
$t=0;
$i=0;
for ($p=0; $p < $n ; $p++) {
  $sql3="";
  $sql3 .="nombreSede LIKE '$sedes[$p]%'";
  $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . ")
  UNION ALL
  (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . ")";

  $result2=mysqli_query($conexion,$sql2);
  while($ver2=mysqli_fetch_row($result2)){
    $valor1[$i]= $ver2[0];
    //echo "VER2 DE ".$sedes[$p]."  =   ".$ver2[0],"\n";
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
    $min[$t]=$valor2;
    //echo $min[$t],"\n";

    unset($timeFormat);
    $t++;
  }
}

$datosX=json_encode($min);
$datosY=json_encode($sedes);

//$datosX=json_encode($valoresX);
//$datosY=json_encode($valoresY);

?>
<div id="graficoBarras"><div>

  <script type="text/javascript">
  function crearCadenaBarras(json){
    var parsed = JSON.parse(json);
    var arr = [];
    for (var x in parsed){
      arr.push(parsed[x]);
    }
    return arr;
  }

</script>

<script type="text/javascript">

datosX = crearCadenaBarras('<?php echo $datosX ?>');
datosY = crearCadenaBarras('<?php echo $datosY ?>');

var data = [
  {
    x: datosY,
    y: datosX,
    type: 'bar'
  }
];

var layout = {
  title: 'Sedes',
  xaxis: {
    tickfont: {
      size: 14,
      color: 'rgb(107, 107, 107)'
    }},
  yaxis: {
    title: 'Tiempo en segundos',
    titlefont: {
      size: 16,
      color: 'rgb(107, 107, 107)'
      }
  },
};


Plotly.newPlot('graficoBarras', data,layout);
</script>
