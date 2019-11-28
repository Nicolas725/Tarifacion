<?php
include("./conexion.php");
error_reporting(E_ERROR | E_PARSE); //hace que no se muestren los warning
$option = isset($_POST['inter']) ? $_POST['inter'] : false;
$fecha = isset($_POST['dateFrom']) ? $_POST['dateFrom'] : false;
$fecha1 = isset($_POST['dateTo']) ? $_POST['dateTo'] : false;
$sede1 = isset($_POST['sede']) ? $_POST['sede'] : false;
$depar1 = isset($_POST['nombreDepar']) ? $_POST['nombreDepar'] : false;

$p=0;
$sql1 = "SELECT Tarifa FROM Costos";
$result1 = mysqli_query($conexion,$sql1);
while ($ver = mysqli_fetch_array($result1)) {
  if ($p==0){
    $Tinter=$ver['Tarifa'];
    $p++;
  }
  else if ($p==1){
    $Tnacion=$ver['Tarifa'];
    $p++;
  }
  else if ($p==2){
    $Tlocal=$ver['Tarifa'];
    $p++;
  }
  else {
    $Tcel=$ver['Tarifa'];
    $p++;
  }
}

$Prefijo=array('00',
'011','0221','0223','0291','03833',
'0351','03783','03722','02965','0343',
'03717','0388','02954','03822','0261',
'03752','0299','02920','0387','0264',
'02652','02966','0342','0341','0385',
'0381','02901',
'3','4','5','6',
'15');
$min=[];
$costo=[];
$internacional=[];
$nacional=[];
$local=[];
$celular=[];
$individual=[];
$individualI=[];
$individualN=[];
$individualL=[];
$individualC=[];

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1, minimum-scale=1.0">
  <title>Software de Tarifacion</title>
  <link rel="stylesheet" href="css/bootstrap.css">
  <script src="jquery-3.2.1.min.js"></script>
  <script src="js/bootstrap.js"></script>
  <link rel="stylesheet" href="css/estilo2.css">
  <link rel="stylesheet" href="icono.min.css">
</head>
<body>

  	<div class="container">
  		<div class="row">
  			<div class="col-sm-12">
  				<table class="table table-hover table-condensed">
  					<tr>

  						<td>
  						</td>

  						<td>
  						</td>

  						<td>
  							<h2>Llamadas Salientes</h2>
  						</td>


  						<td>
  						</td>

  						<td>
  						</td>

  						<td>
  						</td>

  					</tr>
  					<tr>

  						<td>
  						</td>

  						<td>
  						</td>

  						<td>
  							<?php
  							print "<img src=\"imagenes/UM_logo3.jpg\">"
  							?>
  						</td>

  						<td>
  						</td>

  						<td>
  						</td>

  						<td>
  						</td>

  					</tr>
  					<tr>
  						<td>
  							<a href="index.php" class="btn btn-primary">Volver</a>
  						</td>

  						<td>
  						</td>

  						<td>
  						</td>

  						<td>
  						</td>

  						<td>
  							<a href="salir.php" class="btn btn-danger">Cerrar Sesion</a>
  						</td>

  					</tr>


  				</table>
  			</div>
  		</div>
  	</div>

<br><br><br><br>
<br><br><br><br>
<br>
    <table table border="1" class="table table-hover table-condensed table-bordered">
      <tr>
        <td>Interno</td>
        <td>Nombre</td>
        <td>Fecha</td>
        <td>Hora</td>
        <td>Destino</td>
        <td>Tipo</td>
        <td>Sede</td>
        <td>Departamento</td>
        <td>Duration</td>
        <td>Costo</td>

      </tr>

      <?php
      if ($option && $fecha && $fecha1 && $sede1 && $depar1){ //UNO
        $n=0;
        $t=0;
        $l=0;
        $y=0;
        while ($n<1){ //INTERNACIONAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1' AND nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1'
          AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);
          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatI[$i]=$hours1*3600+$mins1*60;
            //COSTO LLAMADA INDIVIDUAL
            $individualI[$h]=$timeFormatI[$i];
            $individualI[$h]="$".($individualI[$h]/60)*$Tinter;
            $individual[$y]=$individualI[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatI){
            $valor2=array_sum($timeFormatI);
            //echo "INTERNACIONAL TIEMPO ",$valor2,"\n";
            $min[$t]=$valor2;
            $valor2=($valor2/60)*$Tinter;
            $costo[$t]=round($valor2,2);
            $internacional[$t]=$costo[$t];
            unset($timeFormatI);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualI[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }

        while ($n<28){ //NACIONAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1' AND nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1'
          AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatN[$i]=$hours1*3600+$mins1*60;
            //COSTO LLAMADA INDIVIDUAL
            $individualN[$h]=$timeFormatN[$i];
            $individualN[$h]="$".($individualN[$h]/60)*$Tnacion;
            $individual[$y]=$individualN[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatN){
            $valor3=array_sum($timeFormatN);
            //echo "NACIONAL TIEMPO ",$valor3,"\n";
            $min[$t]=$valor3;
            $valor3=($valor3/60)*$Tnacion;
            $costo[$t]=round($valor3,2);
            $nacional[$t]=$costo[$t];
            $t++;
            unset($timeFormatN);
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualN[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }

        while ($n<32){ //LOCAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1' AND nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1'
          AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatL[$i]=$hours1*3600+$mins1*60;
            $individualL[$h]=$timeFormatL[$i];
            $individualL[$h]="$".($individualL[$h]/60)*$Tlocal;
            $individual[$y]=$individualL[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatL){
            $valor4=array_sum($timeFormatL);
            //echo "LOCAL TIEMPO ",$valor4,"\n";
            $min[$t]=$valor4;
            $valor4=($valor4/60)*$Tlocal;
            $costo[$t]=round($valor4,2);
            $local[$t]=$costo[$t];
            unset($timeFormatL);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualL[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }
        while ($n<33){ //CELULAR
          $sql4="";
          $sql4 .="nombreSede= '$sede1' AND nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1'
          AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatC[$i]=$hours1*3600+$mins1*60;
            $individualC[$h]=$timeFormatC[$i];
            $individualC[$h]="$".($individualC[$h]/60)*$Tcel;
            $individual[$y]=$individualL[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatC){
            $valor5=array_sum($timeFormatC);
            //echo "CELULAR TIEMPO ",$valor5,"\n";
            $min[$t]=$valor5;
            $valor5=($valor5/60)*$Tcel;
            $costo[$t]=round($valor5,2);
            $celular[$t]=$costo[$t];
            unset($timeFormatC);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          //echo $sql;
          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualC[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }
      }
      else if ($option && $fecha && $fecha1 && $sede1 ){ //DOS
        $n=0;
        $t=0;
        $l=0;
        $y=0;
        while ($n<1){ //INTERNACIONAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1'
          AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);
          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatI[$i]=$hours1*3600+$mins1*60;
            //COSTO LLAMADA INDIVIDUAL
            $individualI[$h]=$timeFormatI[$i];
            $individualI[$h]="$".($individualI[$h]/60)*$Tinter;
            $individual[$y]=$individualI[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatI){
            $valor2=array_sum($timeFormatI);
            //echo "INTERNACIONAL TIEMPO ",$valor2,"\n";
            $min[$t]=$valor2;
            $valor2=($valor2/60)*$Tinter;
            $costo[$t]=round($valor2,2);
            $internacional[$t]=$costo[$t];
            unset($timeFormatI);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualI[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }

        while ($n<28){ //NACIONAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1'
          AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatN[$i]=$hours1*3600+$mins1*60;
            //COSTO LLAMADA INDIVIDUAL
            $individualN[$h]=$timeFormatN[$i];
            $individualN[$h]="$".($individualN[$h]/60)*$Tnacion;
            $individual[$y]=$individualN[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatN){
            $valor3=array_sum($timeFormatN);
            //echo "NACIONAL TIEMPO ",$valor3,"\n";
            $min[$t]=$valor3;
            $valor3=($valor3/60)*$Tnacion;
            $costo[$t]=round($valor3,2);
            $nacional[$t]=$costo[$t];
            $t++;
            unset($timeFormatN);
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualN[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }

        while ($n<32){ //LOCAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1'
          AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatL[$i]=$hours1*3600+$mins1*60;
            $individualL[$h]=$timeFormatL[$i];
            $individualL[$h]="$".($individualL[$h]/60)*$Tlocal;
            $individual[$y]=$individualL[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatL){
            $valor4=array_sum($timeFormatL);
            //echo "LOCAL TIEMPO ",$valor4,"\n";
            $min[$t]=$valor4;
            $valor4=($valor4/60)*$Tlocal;
            $costo[$t]=round($valor4,2);
            $local[$t]=$costo[$t];
            unset($timeFormatL);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualL[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }
        while ($n<33){ //CELULAR
          $sql4="";
          $sql4 .="nombreSede= '$sede1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1'
          AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatC[$i]=$hours1*3600+$mins1*60;
            $individualC[$h]=$timeFormatC[$i];
            $individualC[$h]="$".($individualC[$h]/60)*$Tcel;
            $individual[$y]=$individualL[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatC){
            $valor5=array_sum($timeFormatC);
            //echo "CELULAR TIEMPO ",$valor5,"\n";
            $min[$t]=$valor5;
            $valor5=($valor5/60)*$Tcel;
            $costo[$t]=round($valor5,2);
            $celular[$t]=$costo[$t];
            unset($timeFormatC);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          //echo $sql;
          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualC[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }
      }
      else if ($option && $fecha && $fecha1 && $depar1 ){ //TRES
        $n=0;
        $t=0;
        $l=0;
        $y=0;
        while ($n<1){ //INTERNACIONAL
          $sql4="";
          $sql4 .="nombreDepar= '$depar1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1'
          AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);
          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatI[$i]=$hours1*3600+$mins1*60;
            //COSTO LLAMADA INDIVIDUAL
            $individualI[$h]=$timeFormatI[$i];
            $individualI[$h]="$".($individualI[$h]/60)*$Tinter;
            $individual[$y]=$individualI[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatI){
            $valor2=array_sum($timeFormatI);
            //echo "INTERNACIONAL TIEMPO ",$valor2,"\n";
            $min[$t]=$valor2;
            $valor2=($valor2/60)*$Tinter;
            $costo[$t]=round($valor2,2);
            $internacional[$t]=$costo[$t];
            unset($timeFormatI);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualI[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }

        while ($n<28){ //NACIONAL
          $sql4="";
          $sql4 .="nombreDepar= '$depar1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1'
          AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatN[$i]=$hours1*3600+$mins1*60;
            //COSTO LLAMADA INDIVIDUAL
            $individualN[$h]=$timeFormatN[$i];
            $individualN[$h]="$".($individualN[$h]/60)*$Tnacion;
            $individual[$y]=$individualN[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatN){
            $valor3=array_sum($timeFormatN);
            //echo "NACIONAL TIEMPO ",$valor3,"\n";
            $min[$t]=$valor3;
            $valor3=($valor3/60)*$Tnacion;
            $costo[$t]=round($valor3,2);
            $nacional[$t]=$costo[$t];
            $t++;
            unset($timeFormatN);
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualN[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }

        while ($n<32){ //LOCAL
          $sql4="";
          $sql4 .="nombreDepar= '$depar1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1'
          AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatL[$i]=$hours1*3600+$mins1*60;
            $individualL[$h]=$timeFormatL[$i];
            $individualL[$h]="$".($individualL[$h]/60)*$Tlocal;
            $individual[$y]=$individualL[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatL){
            $valor4=array_sum($timeFormatL);
            //echo "LOCAL TIEMPO ",$valor4,"\n";
            $min[$t]=$valor4;
            $valor4=($valor4/60)*$Tlocal;
            $costo[$t]=round($valor4,2);
            $local[$t]=$costo[$t];
            unset($timeFormatL);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualL[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }
        while ($n<33){ //CELULAR
          $sql4="";
          $sql4 .="nombreDepar= '$depar1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1'
          AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatC[$i]=$hours1*3600+$mins1*60;
            $individualC[$h]=$timeFormatC[$i];
            $individualC[$h]="$".($individualC[$h]/60)*$Tcel;
            $individual[$y]=$individualL[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatC){
            $valor5=array_sum($timeFormatC);
            //echo "CELULAR TIEMPO ",$valor5,"\n";
            $min[$t]=$valor5;
            $valor5=($valor5/60)*$Tcel;
            $costo[$t]=round($valor5,2);
            $celular[$t]=$costo[$t];
            unset($timeFormatC);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          //echo $sql;
          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualC[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }
      }
      else if ($option && $sede1 && $depar1 ){ //CUATRO
        $n=0;
        $t=0;
        $l=0;
        $y=0;
        while ($n<1){ //INTERNACIONAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1' AND nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);
          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatI[$i]=$hours1*3600+$mins1*60;
            //COSTO LLAMADA INDIVIDUAL
            $individualI[$h]=$timeFormatI[$i];
            $individualI[$h]="$".($individualI[$h]/60)*$Tinter;
            $individual[$y]=$individualI[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatI){
            $valor2=array_sum($timeFormatI);
            //echo "INTERNACIONAL TIEMPO ",$valor2,"\n";
            $min[$t]=$valor2;
            $valor2=($valor2/60)*$Tinter;
            $costo[$t]=round($valor2,2);
            $internacional[$t]=$costo[$t];
            unset($timeFormatI);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualI[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }

        while ($n<28){ //NACIONAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1' AND nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND diallednumber LIKE '$Prefijo[$n]%'";
          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatN[$i]=$hours1*3600+$mins1*60;
            //COSTO LLAMADA INDIVIDUAL
            $individualN[$h]=$timeFormatN[$i];
            $individualN[$h]="$".($individualN[$h]/60)*$Tnacion;
            $individual[$y]=$individualN[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatN){
            $valor3=array_sum($timeFormatN);
            //echo "NACIONAL TIEMPO ",$valor3,"\n";
            $min[$t]=$valor3;
            $valor3=($valor3/60)*$Tnacion;
            $costo[$t]=round($valor3,2);
            $nacional[$t]=$costo[$t];
            $t++;
            unset($timeFormatN);
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualN[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }

        while ($n<32){ //LOCAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1' AND nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatL[$i]=$hours1*3600+$mins1*60;
            $individualL[$h]=$timeFormatL[$i];
            $individualL[$h]="$".($individualL[$h]/60)*$Tlocal;
            $individual[$y]=$individualL[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatL){
            $valor4=array_sum($timeFormatL);
            //echo "LOCAL TIEMPO ",$valor4,"\n";
            $min[$t]=$valor4;
            $valor4=($valor4/60)*$Tlocal;
            $costo[$t]=round($valor4,2);
            $local[$t]=$costo[$t];
            unset($timeFormatL);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualL[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }
        while ($n<33){ //CELULAR
          $sql4="";
          $sql4 .="nombreSede= '$sede1' AND nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatC[$i]=$hours1*3600+$mins1*60;
            $individualC[$h]=$timeFormatC[$i];
            $individualC[$h]="$".($individualC[$h]/60)*$Tcel;
            $individual[$y]=$individualL[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatC){
            $valor5=array_sum($timeFormatC);
            //echo "CELULAR TIEMPO ",$valor5,"\n";
            $min[$t]=$valor5;
            $valor5=($valor5/60)*$Tcel;
            $costo[$t]=round($valor5,2);
            $celular[$t]=$costo[$t];
            unset($timeFormatC);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          //echo $sql;
          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualC[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }
      }
      else if ($fecha && $fecha1 && $sede1 && $depar1 ){ //CINCO
        $n=0;
        $t=0;
        $l=0;
        $y=0;
        while ($n<1){ //INTERNACIONAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1' AND nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);
          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatI[$i]=$hours1*3600+$mins1*60;
            //COSTO LLAMADA INDIVIDUAL
            $individualI[$h]=$timeFormatI[$i];
            $individualI[$h]="$".($individualI[$h]/60)*$Tinter;
            $individual[$y]=$individualI[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatI){
            $valor2=array_sum($timeFormatI);
            //echo "INTERNACIONAL TIEMPO ",$valor2,"\n";
            $min[$t]=$valor2;
            $valor2=($valor2/60)*$Tinter;
            $costo[$t]=round($valor2,2);
            $internacional[$t]=$costo[$t];
            unset($timeFormatI);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualI[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }

        while ($n<28){ //NACIONAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1' AND nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatN[$i]=$hours1*3600+$mins1*60;
            //COSTO LLAMADA INDIVIDUAL
            $individualN[$h]=$timeFormatN[$i];
            $individualN[$h]="$".($individualN[$h]/60)*$Tnacion;
            $individual[$y]=$individualN[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatN){
            $valor3=array_sum($timeFormatN);
            //echo "NACIONAL TIEMPO ",$valor3,"\n";
            $min[$t]=$valor3;
            $valor3=($valor3/60)*$Tnacion;
            $costo[$t]=round($valor3,2);
            $nacional[$t]=$costo[$t];
            $t++;
            unset($timeFormatN);
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualN[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }

        while ($n<32){ //LOCAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1' AND nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatL[$i]=$hours1*3600+$mins1*60;
            $individualL[$h]=$timeFormatL[$i];
            $individualL[$h]="$".($individualL[$h]/60)*$Tlocal;
            $individual[$y]=$individualL[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatL){
            $valor4=array_sum($timeFormatL);
            //echo "LOCAL TIEMPO ",$valor4,"\n";
            $min[$t]=$valor4;
            $valor4=($valor4/60)*$Tlocal;
            $costo[$t]=round($valor4,2);
            $local[$t]=$costo[$t];
            unset($timeFormatL);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualL[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }
        while ($n<33){ //CELULAR
          $sql4="";
          $sql4 .="nombreSede= '$sede1' AND nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatC[$i]=$hours1*3600+$mins1*60;
            $individualC[$h]=$timeFormatC[$i];
            $individualC[$h]="$".($individualC[$h]/60)*$Tcel;
            $individual[$y]=$individualL[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatC){
            $valor5=array_sum($timeFormatC);
            //echo "CELULAR TIEMPO ",$valor5,"\n";
            $min[$t]=$valor5;
            $valor5=($valor5/60)*$Tcel;
            $costo[$t]=round($valor5,2);
            $celular[$t]=$costo[$t];
            unset($timeFormatC);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          //echo $sql;
          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualC[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }
      }
      else if ($option && $fecha && $fecha1){ //SEIS
        $n=0;
        $t=0;
        $l=0;
        $y=0;
        while ($n<1){ //INTERNACIONAL
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1'
          AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . ")";

          $result2=mysqli_query($conexion,$sql2);
          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatI[$i]=$hours1*3600+$mins1*60;
            //COSTO LLAMADA INDIVIDUAL
            $individualI[$h]=$timeFormatI[$i];
            $individualI[$h]="$".($individualI[$h]/60)*$Tinter;
            $individual[$y]=$individualI[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatI){
            $valor2=array_sum($timeFormatI);
            //echo "INTERNACIONAL TIEMPO ",$valor2,"\n";
            $min[$t]=$valor2;
            $valor2=($valor2/60)*$Tinter;
            $costo[$t]=round($valor2,2);
            $internacional[$t]=$costo[$t];
            unset($timeFormatI);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualI[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }

        while ($n<28){ //NACIONAL
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1'
          AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatN[$i]=$hours1*3600+$mins1*60;
            //COSTO LLAMADA INDIVIDUAL
            $individualN[$h]=$timeFormatN[$i];
            $individualN[$h]="$".($individualN[$h]/60)*$Tnacion;
            $individual[$y]=$individualN[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatN){
            $valor3=array_sum($timeFormatN);
            //echo "NACIONAL TIEMPO ",$valor3,"\n";
            $min[$t]=$valor3;
            $valor3=($valor3/60)*$Tnacion;
            $costo[$t]=round($valor3,2);
            $nacional[$t]=$costo[$t];
            $t++;
            unset($timeFormatN);
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualN[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }

        while ($n<32){ //LOCAL
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1'
          AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatL[$i]=$hours1*3600+$mins1*60;
            $individualL[$h]=$timeFormatL[$i];
            $individualL[$h]="$".($individualL[$h]/60)*$Tlocal;
            $individual[$y]=$individualL[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatL){
            $valor4=array_sum($timeFormatL);
            //echo "LOCAL TIEMPO ",$valor4,"\n";
            $min[$t]=$valor4;
            $valor4=($valor4/60)*$Tlocal;
            $costo[$t]=round($valor4,2);
            $local[$t]=$costo[$t];
            unset($timeFormatL);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualL[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }
        while ($n<33){ //CELULAR
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1'
          AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatC[$i]=$hours1*3600+$mins1*60;
            $individualC[$h]=$timeFormatC[$i];
            $individualC[$h]="$".($individualC[$h]/60)*$Tcel;
            $individual[$y]=$individualL[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatC){
            $valor5=array_sum($timeFormatC);
            //echo "CELULAR TIEMPO ",$valor5,"\n";
            $min[$t]=$valor5;
            $valor5=($valor5/60)*$Tcel;
            $costo[$t]=round($valor5,2);
            $celular[$t]=$costo[$t];
            unset($timeFormatC);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . ")";

          //echo $sql;
          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualC[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }
      }
      else if ($option && $sede1){ //SIESTE
        $n=0;
        $t=0;
        $l=0;
        $y=0;
        while ($n<1){ //INTERNACIONAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);
          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatI[$i]=$hours1*3600+$mins1*60;
            //COSTO LLAMADA INDIVIDUAL
            $individualI[$h]=$timeFormatI[$i];
            $individualI[$h]="$".($individualI[$h]/60)*$Tinter;
            $individual[$y]=$individualI[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatI){
            $valor2=array_sum($timeFormatI);
            //echo "INTERNACIONAL TIEMPO ",$valor2,"\n";
            $min[$t]=$valor2;
            $valor2=($valor2/60)*$Tinter;
            $costo[$t]=round($valor2,2);
            $internacional[$t]=$costo[$t];
            unset($timeFormatI);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualI[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }

        while ($n<28){ //NACIONAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatN[$i]=$hours1*3600+$mins1*60;
            //COSTO LLAMADA INDIVIDUAL
            $individualN[$h]=$timeFormatN[$i];
            $individualN[$h]="$".($individualN[$h]/60)*$Tnacion;
            $individual[$y]=$individualN[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatN){
            $valor3=array_sum($timeFormatN);
            //echo "NACIONAL TIEMPO ",$valor3,"\n";
            $min[$t]=$valor3;
            $valor3=($valor3/60)*$Tnacion;
            $costo[$t]=round($valor3,2);
            $nacional[$t]=$costo[$t];
            $t++;
            unset($timeFormatN);
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualN[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }

        while ($n<32){ //LOCAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatL[$i]=$hours1*3600+$mins1*60;
            $individualL[$h]=$timeFormatL[$i];
            $individualL[$h]="$".($individualL[$h]/60)*$Tlocal;
            $individual[$y]=$individualL[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatL){
            $valor4=array_sum($timeFormatL);
            //echo "LOCAL TIEMPO ",$valor4,"\n";
            $min[$t]=$valor4;
            $valor4=($valor4/60)*$Tlocal;
            $costo[$t]=round($valor4,2);
            $local[$t]=$costo[$t];
            unset($timeFormatL);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualL[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }
        while ($n<33){ //CELULAR
          $sql4="";
          $sql4 .="nombreSede= '$sede1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatC[$i]=$hours1*3600+$mins1*60;
            $individualC[$h]=$timeFormatC[$i];
            $individualC[$h]="$".($individualC[$h]/60)*$Tcel;
            $individual[$y]=$individualL[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatC){
            $valor5=array_sum($timeFormatC);
            //echo "CELULAR TIEMPO ",$valor5,"\n";
            $min[$t]=$valor5;
            $valor5=($valor5/60)*$Tcel;
            $costo[$t]=round($valor5,2);
            $celular[$t]=$costo[$t];
            unset($timeFormatC);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          //echo $sql;
          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualC[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }
      }
      else if ($option && $depar1){ //OCHO
        $n=0;
        $t=0;
        $l=0;
        $y=0;
        while ($n<1){ //INTERNACIONAL
          $sql4="";
          $sql4 .="nombreDepar= '$depar1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);
          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatI[$i]=$hours1*3600+$mins1*60;
            //COSTO LLAMADA INDIVIDUAL
            $individualI[$h]=$timeFormatI[$i];
            $individualI[$h]="$".($individualI[$h]/60)*$Tinter;
            $individual[$y]=$individualI[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatI){
            $valor2=array_sum($timeFormatI);
            //echo "INTERNACIONAL TIEMPO ",$valor2,"\n";
            $min[$t]=$valor2;
            $valor2=($valor2/60)*$Tinter;
            $costo[$t]=round($valor2,2);
            $internacional[$t]=$costo[$t];
            unset($timeFormatI);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualI[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }

        while ($n<28){ //NACIONAL
          $sql4="";
          $sql4 .="nombreDepar= '$depar1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatN[$i]=$hours1*3600+$mins1*60;
            //COSTO LLAMADA INDIVIDUAL
            $individualN[$h]=$timeFormatN[$i];
            $individualN[$h]="$".($individualN[$h]/60)*$Tnacion;
            $individual[$y]=$individualN[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatN){
            $valor3=array_sum($timeFormatN);
            //echo "NACIONAL TIEMPO ",$valor3,"\n";
            $min[$t]=$valor3;
            $valor3=($valor3/60)*$Tnacion;
            $costo[$t]=round($valor3,2);
            $nacional[$t]=$costo[$t];
            $t++;
            unset($timeFormatN);
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualN[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }

        while ($n<32){ //LOCAL
          $sql4="";
          $sql4 .="nombreDepar= '$depar1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatL[$i]=$hours1*3600+$mins1*60;
            $individualL[$h]=$timeFormatL[$i];
            $individualL[$h]="$".($individualL[$h]/60)*$Tlocal;
            $individual[$y]=$individualL[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatL){
            $valor4=array_sum($timeFormatL);
            //echo "LOCAL TIEMPO ",$valor4,"\n";
            $min[$t]=$valor4;
            $valor4=($valor4/60)*$Tlocal;
            $costo[$t]=round($valor4,2);
            $local[$t]=$costo[$t];
            unset($timeFormatL);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualL[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }
        while ($n<33){ //CELULAR
          $sql4="";
          $sql4 .="nombreDepar= '$depar1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatC[$i]=$hours1*3600+$mins1*60;
            $individualC[$h]=$timeFormatC[$i];
            $individualC[$h]="$".($individualC[$h]/60)*$Tcel;
            $individual[$y]=$individualL[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatC){
            $valor5=array_sum($timeFormatC);
            //echo "CELULAR TIEMPO ",$valor5,"\n";
            $min[$t]=$valor5;
            $valor5=($valor5/60)*$Tcel;
            $costo[$t]=round($valor5,2);
            $celular[$t]=$costo[$t];
            unset($timeFormatC);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          //echo $sql;
          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualC[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }
      }
      else if ($fecha && $fecha1 && $sede1){ //NUEVE
        $n=0;
        $t=0;
        $l=0;
        $y=0;
        while ($n<1){ //INTERNACIONAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1'";
          $sql3="";
          $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);
          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatI[$i]=$hours1*3600+$mins1*60;
            //COSTO LLAMADA INDIVIDUAL
            $individualI[$h]=$timeFormatI[$i];
            $individualI[$h]="$".($individualI[$h]/60)*$Tinter;
            $individual[$y]=$individualI[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatI){
            $valor2=array_sum($timeFormatI);
            //echo "INTERNACIONAL TIEMPO ",$valor2,"\n";
            $min[$t]=$valor2;
            $valor2=($valor2/60)*$Tinter;
            $costo[$t]=round($valor2,2);
            $internacional[$t]=$costo[$t];
            unset($timeFormatI);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualI[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }

        while ($n<28){ //NACIONAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1'";
          $sql3="";
          $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatN[$i]=$hours1*3600+$mins1*60;
            //COSTO LLAMADA INDIVIDUAL
            $individualN[$h]=$timeFormatN[$i];
            $individualN[$h]="$".($individualN[$h]/60)*$Tnacion;
            $individual[$y]=$individualN[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatN){
            $valor3=array_sum($timeFormatN);
            //echo "NACIONAL TIEMPO ",$valor3,"\n";
            $min[$t]=$valor3;
            $valor3=($valor3/60)*$Tnacion;
            $costo[$t]=round($valor3,2);
            $nacional[$t]=$costo[$t];
            $t++;
            unset($timeFormatN);
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualN[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }

        while ($n<32){ //LOCAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1'";
          $sql3="";
          $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatL[$i]=$hours1*3600+$mins1*60;
            $individualL[$h]=$timeFormatL[$i];
            $individualL[$h]="$".($individualL[$h]/60)*$Tlocal;
            $individual[$y]=$individualL[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatL){
            $valor4=array_sum($timeFormatL);
            //echo "LOCAL TIEMPO ",$valor4,"\n";
            $min[$t]=$valor4;
            $valor4=($valor4/60)*$Tlocal;
            $costo[$t]=round($valor4,2);
            $local[$t]=$costo[$t];
            unset($timeFormatL);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualL[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }
        while ($n<33){ //CELULAR
          $sql4="";
          $sql4 .="nombreSede= '$sede1'";
          $sql3="";
          $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatC[$i]=$hours1*3600+$mins1*60;
            $individualC[$h]=$timeFormatC[$i];
            $individualC[$h]="$".($individualC[$h]/60)*$Tcel;
            $individual[$y]=$individualL[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatC){
            $valor5=array_sum($timeFormatC);
            //echo "CELULAR TIEMPO ",$valor5,"\n";
            $min[$t]=$valor5;
            $valor5=($valor5/60)*$Tcel;
            $costo[$t]=round($valor5,2);
            $celular[$t]=$costo[$t];
            unset($timeFormatC);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          //echo $sql;
          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualC[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }
      }
      else if ($fecha && $fecha1 && $depar1){ //DIEZ
        $n=0;
        $t=0;
        $l=0;
        $y=0;
        while ($n<1){ //INTERNACIONAL
          $sql4="";
          $sql4 .="nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);
          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatI[$i]=$hours1*3600+$mins1*60;
            //COSTO LLAMADA INDIVIDUAL
            $individualI[$h]=$timeFormatI[$i];
            $individualI[$h]="$".($individualI[$h]/60)*$Tinter;
            $individual[$y]=$individualI[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatI){
            $valor2=array_sum($timeFormatI);
            //echo "INTERNACIONAL TIEMPO ",$valor2,"\n";
            $min[$t]=$valor2;
            $valor2=($valor2/60)*$Tinter;
            $costo[$t]=round($valor2,2);
            $internacional[$t]=$costo[$t];
            unset($timeFormatI);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualI[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }

        while ($n<28){ //NACIONAL
          $sql4="";
          $sql4 .="nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatN[$i]=$hours1*3600+$mins1*60;
            //COSTO LLAMADA INDIVIDUAL
            $individualN[$h]=$timeFormatN[$i];
            $individualN[$h]="$".($individualN[$h]/60)*$Tnacion;
            $individual[$y]=$individualN[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatN){
            $valor3=array_sum($timeFormatN);
            //echo "NACIONAL TIEMPO ",$valor3,"\n";
            $min[$t]=$valor3;
            $valor3=($valor3/60)*$Tnacion;
            $costo[$t]=round($valor3,2);
            $nacional[$t]=$costo[$t];
            $t++;
            unset($timeFormatN);
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualN[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }

        while ($n<32){ //LOCAL
          $sql4="";
          $sql4 .="nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatL[$i]=$hours1*3600+$mins1*60;
            $individualL[$h]=$timeFormatL[$i];
            $individualL[$h]="$".($individualL[$h]/60)*$Tlocal;
            $individual[$y]=$individualL[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatL){
            $valor4=array_sum($timeFormatL);
            //echo "LOCAL TIEMPO ",$valor4,"\n";
            $min[$t]=$valor4;
            $valor4=($valor4/60)*$Tlocal;
            $costo[$t]=round($valor4,2);
            $local[$t]=$costo[$t];
            unset($timeFormatL);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualL[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }
        while ($n<33){ //CELULAR
          $sql4="";
          $sql4 .="nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatC[$i]=$hours1*3600+$mins1*60;
            $individualC[$h]=$timeFormatC[$i];
            $individualC[$h]="$".($individualC[$h]/60)*$Tcel;
            $individual[$y]=$individualL[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatC){
            $valor5=array_sum($timeFormatC);
            //echo "CELULAR TIEMPO ",$valor5,"\n";
            $min[$t]=$valor5;
            $valor5=($valor5/60)*$Tcel;
            $costo[$t]=round($valor5,2);
            $celular[$t]=$costo[$t];
            unset($timeFormatC);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          //echo $sql;
          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualC[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }
      }
      else if ($sede1 && $depar1 ){ //ONCE
        $n=0;
        $t=0;
        $l=0;
        $y=0;
        while ($n<1){ //INTERNACIONAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1' AND nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);
          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatI[$i]=$hours1*3600+$mins1*60;
            //COSTO LLAMADA INDIVIDUAL
            $individualI[$h]=$timeFormatI[$i];
            $individualI[$h]="$".($individualI[$h]/60)*$Tinter;
            $individual[$y]=$individualI[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatI){
            $valor2=array_sum($timeFormatI);
            //echo "INTERNACIONAL TIEMPO ",$valor2,"\n";
            $min[$t]=$valor2;
            $valor2=($valor2/60)*$Tinter;
            $costo[$t]=round($valor2,2);
            $internacional[$t]=$costo[$t];
            unset($timeFormatI);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualI[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }

        while ($n<28){ //NACIONAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1' AND nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatN[$i]=$hours1*3600+$mins1*60;
            //COSTO LLAMADA INDIVIDUAL
            $individualN[$h]=$timeFormatN[$i];
            $individualN[$h]="$".($individualN[$h]/60)*$Tnacion;
            $individual[$y]=$individualN[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatN){
            $valor3=array_sum($timeFormatN);
            //echo "NACIONAL TIEMPO ",$valor3,"\n";
            $min[$t]=$valor3;
            $valor3=($valor3/60)*$Tnacion;
            $costo[$t]=round($valor3,2);
            $nacional[$t]=$costo[$t];
            $t++;
            unset($timeFormatN);
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualN[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }

        while ($n<32){ //LOCAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1' AND nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatL[$i]=$hours1*3600+$mins1*60;
            $individualL[$h]=$timeFormatL[$i];
            $individualL[$h]="$".($individualL[$h]/60)*$Tlocal;
            $individual[$y]=$individualL[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatL){
            $valor4=array_sum($timeFormatL);
            //echo "LOCAL TIEMPO ",$valor4,"\n";
            $min[$t]=$valor4;
            $valor4=($valor4/60)*$Tlocal;
            $costo[$t]=round($valor4,2);
            $local[$t]=$costo[$t];
            unset($timeFormatL);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualL[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }
        while ($n<33){ //CELULAR
          $sql4="";
          $sql4 .="nombreSede= '$sede1' AND nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatC[$i]=$hours1*3600+$mins1*60;
            $individualC[$h]=$timeFormatC[$i];
            $individualC[$h]="$".($individualC[$h]/60)*$Tcel;
            $individual[$y]=$individualL[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatC){
            $valor5=array_sum($timeFormatC);
            //echo "CELULAR TIEMPO ",$valor5,"\n";
            $min[$t]=$valor5;
            $valor5=($valor5/60)*$Tcel;
            $costo[$t]=round($valor5,2);
            $celular[$t]=$costo[$t];
            unset($timeFormatC);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          //echo $sql;
          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualC[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }
      }
      else if ($option){ //DOCE
        $n=0;
        $t=0;
        $l=0;
        $y=0;
        while ($n<1){ //INTERNACIONAL
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . ")";

          $result2=mysqli_query($conexion,$sql2);
          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatI[$i]=$hours1*3600+$mins1*60;
            //COSTO LLAMADA INDIVIDUAL
            $individualI[$h]=$timeFormatI[$i];
            $individualI[$h]="$".($individualI[$h]/60)*$Tinter;
            $individual[$y]=$individualI[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatI){
            $valor2=array_sum($timeFormatI);
            //echo "INTERNACIONAL TIEMPO ",$valor2,"\n";
            $min[$t]=$valor2;
            $valor2=($valor2/60)*$Tinter;
            $costo[$t]=round($valor2,2);
            $internacional[$t]=$costo[$t];
            unset($timeFormatI);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualI[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }

        while ($n<28){ //NACIONAL
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatN[$i]=$hours1*3600+$mins1*60;
            //COSTO LLAMADA INDIVIDUAL
            $individualN[$h]=$timeFormatN[$i];
            $individualN[$h]="$".($individualN[$h]/60)*$Tnacion;
            $individual[$y]=$individualN[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatN){
            $valor3=array_sum($timeFormatN);
            //echo "NACIONAL TIEMPO ",$valor3,"\n";
            $min[$t]=$valor3;
            $valor3=($valor3/60)*$Tnacion;
            $costo[$t]=round($valor3,2);
            $nacional[$t]=$costo[$t];
            $t++;
            unset($timeFormatN);
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualN[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }

        while ($n<32){ //LOCAL
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatL[$i]=$hours1*3600+$mins1*60;
            $individualL[$h]=$timeFormatL[$i];
            $individualL[$h]="$".($individualL[$h]/60)*$Tlocal;
            $individual[$y]=$individualL[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatL){
            $valor4=array_sum($timeFormatL);
            //echo "LOCAL TIEMPO ",$valor4,"\n";
            $min[$t]=$valor4;
            $valor4=($valor4/60)*$Tlocal;
            $costo[$t]=round($valor4,2);
            $local[$t]=$costo[$t];
            unset($timeFormatL);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualL[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }
        while ($n<33){ //CELULAR
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatC[$i]=$hours1*3600+$mins1*60;
            $individualC[$h]=$timeFormatC[$i];
            $individualC[$h]="$".($individualC[$h]/60)*$Tcel;
            $individual[$y]=$individualL[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatC){
            $valor5=array_sum($timeFormatC);
            //echo "CELULAR TIEMPO ",$valor5,"\n";
            $min[$t]=$valor5;
            $valor5=($valor5/60)*$Tcel;
            $costo[$t]=round($valor5,2);
            $celular[$t]=$costo[$t];
            unset($timeFormatC);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . ")";

          //echo $sql;
          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualC[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }
      }
      else if ($fecha && $fecha1){ //TRECE
        $n=0;
        $t=0;
        $l=0;
        $y=0;
        while ($n<1){ //INTERNACIONAL
          $sql3="";
          $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . ")";

          $result2=mysqli_query($conexion,$sql2);
          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatI[$i]=$hours1*3600+$mins1*60;
            //COSTO LLAMADA INDIVIDUAL
            $individualI[$h]=$timeFormatI[$i];
            $individualI[$h]="$".($individualI[$h]/60)*$Tinter;
            $individual[$y]=$individualI[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatI){
            $valor2=array_sum($timeFormatI);
            //echo "INTERNACIONAL TIEMPO ",$valor2,"\n";
            $min[$t]=$valor2;
            $valor2=($valor2/60)*$Tinter;
            $costo[$t]=round($valor2,2);
            $internacional[$t]=$costo[$t];
            unset($timeFormatI);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualI[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }

        while ($n<28){ //NACIONAL
          $sql3="";
          $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatN[$i]=$hours1*3600+$mins1*60;
            //COSTO LLAMADA INDIVIDUAL
            $individualN[$h]=$timeFormatN[$i];
            $individualN[$h]="$".($individualN[$h]/60)*$Tnacion;
            $individual[$y]=$individualN[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatN){
            $valor3=array_sum($timeFormatN);
            //echo "NACIONAL TIEMPO ",$valor3,"\n";
            $min[$t]=$valor3;
            $valor3=($valor3/60)*$Tnacion;
            $costo[$t]=round($valor3,2);
            $nacional[$t]=$costo[$t];
            $t++;
            unset($timeFormatN);
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualN[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }

        while ($n<32){ //LOCAL
          $sql3="";
          $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatL[$i]=$hours1*3600+$mins1*60;
            $individualL[$h]=$timeFormatL[$i];
            $individualL[$h]="$".($individualL[$h]/60)*$Tlocal;
            $individual[$y]=$individualL[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatL){
            $valor4=array_sum($timeFormatL);
            //echo "LOCAL TIEMPO ",$valor4,"\n";
            $min[$t]=$valor4;
            $valor4=($valor4/60)*$Tlocal;
            $costo[$t]=round($valor4,2);
            $local[$t]=$costo[$t];
            unset($timeFormatL);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualL[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }
        while ($n<33){ //CELULAR
          $sql3="";
          $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatC[$i]=$hours1*3600+$mins1*60;
            $individualC[$h]=$timeFormatC[$i];
            $individualC[$h]="$".($individualC[$h]/60)*$Tcel;
            $individual[$y]=$individualL[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatC){
            $valor5=array_sum($timeFormatC);
            //echo "CELULAR TIEMPO ",$valor5,"\n";
            $min[$t]=$valor5;
            $valor5=($valor5/60)*$Tcel;
            $costo[$t]=round($valor5,2);
            $celular[$t]=$costo[$t];
            unset($timeFormatC);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . ")";

          //echo $sql;
          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualC[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }
      }
      else if ($sede1){ //CATORCE
        $n=0;
        $t=0;
        $l=0;
        $y=0;
        while ($n<1){ //INTERNACIONAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1'";
          $sql3="";
          $sql3 .="diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);
          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatI[$i]=$hours1*3600+$mins1*60;
            //COSTO LLAMADA INDIVIDUAL
            $individualI[$h]=$timeFormatI[$i];
            $individualI[$h]="$".($individualI[$h]/60)*$Tinter;
            $individual[$y]=$individualI[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatI){
            $valor2=array_sum($timeFormatI);
            //echo "INTERNACIONAL TIEMPO ",$valor2,"\n";
            $min[$t]=$valor2;
            $valor2=($valor2/60)*$Tinter;
            $costo[$t]=round($valor2,2);
            $internacional[$t]=$costo[$t];
            unset($timeFormatI);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualI[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }

        while ($n<28){ //NACIONAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1'";
          $sql3="";
          $sql3 .="diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatN[$i]=$hours1*3600+$mins1*60;
            //COSTO LLAMADA INDIVIDUAL
            $individualN[$h]=$timeFormatN[$i];
            $individualN[$h]="$".($individualN[$h]/60)*$Tnacion;
            $individual[$y]=$individualN[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatN){
            $valor3=array_sum($timeFormatN);
            //echo "NACIONAL TIEMPO ",$valor3,"\n";
            $min[$t]=$valor3;
            $valor3=($valor3/60)*$Tnacion;
            $costo[$t]=round($valor3,2);
            $nacional[$t]=$costo[$t];
            $t++;
            unset($timeFormatN);
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualN[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }

        while ($n<32){ //LOCAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1'";
          $sql3="";
          $sql3 .="diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatL[$i]=$hours1*3600+$mins1*60;
            $individualL[$h]=$timeFormatL[$i];
            $individualL[$h]="$".($individualL[$h]/60)*$Tlocal;
            $individual[$y]=$individualL[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatL){
            $valor4=array_sum($timeFormatL);
            //echo "LOCAL TIEMPO ",$valor4,"\n";
            $min[$t]=$valor4;
            $valor4=($valor4/60)*$Tlocal;
            $costo[$t]=round($valor4,2);
            $local[$t]=$costo[$t];
            unset($timeFormatL);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualL[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }
        while ($n<33){ //CELULAR
          $sql4="";
          $sql4 .="nombreSede= '$sede1'";
          $sql3="";
          $sql3 .="diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatC[$i]=$hours1*3600+$mins1*60;
            $individualC[$h]=$timeFormatC[$i];
            $individualC[$h]="$".($individualC[$h]/60)*$Tcel;
            $individual[$y]=$individualL[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatC){
            $valor5=array_sum($timeFormatC);
            //echo "CELULAR TIEMPO ",$valor5,"\n";
            $min[$t]=$valor5;
            $valor5=($valor5/60)*$Tcel;
            $costo[$t]=round($valor5,2);
            $celular[$t]=$costo[$t];
            unset($timeFormatC);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          //echo $sql;
          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualC[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }
      }
      else if ($depar1 ){ //QUINCE
        $n=0;
        $t=0;
        $l=0;
        $y=0;
        while ($n<1){ //INTERNACIONAL
          $sql4="";
          $sql4 .="nombreDepar= '$depar1'";
          $sql3="";
          $sql3 .="diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);
          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatI[$i]=$hours1*3600+$mins1*60;
            //COSTO LLAMADA INDIVIDUAL
            $individualI[$h]=$timeFormatI[$i];
            $individualI[$h]="$".($individualI[$h]/60)*$Tinter;
            $individual[$y]=$individualI[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatI){
            $valor2=array_sum($timeFormatI);
            //echo "INTERNACIONAL TIEMPO ",$valor2,"\n";
            $min[$t]=$valor2;
            $valor2=($valor2/60)*$Tinter;
            $costo[$t]=round($valor2,2);
            $internacional[$t]=$costo[$t];
            unset($timeFormatI);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualI[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }

        while ($n<28){ //NACIONAL
          $sql4="";
          $sql4 .="nombreDepar= '$depar1'";
          $sql3="";
          $sql3 .="diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatN[$i]=$hours1*3600+$mins1*60;
            //COSTO LLAMADA INDIVIDUAL
            $individualN[$h]=$timeFormatN[$i];
            $individualN[$h]="$".($individualN[$h]/60)*$Tnacion;
            $individual[$y]=$individualN[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatN){
            $valor3=array_sum($timeFormatN);
            //echo "NACIONAL TIEMPO ",$valor3,"\n";
            $min[$t]=$valor3;
            $valor3=($valor3/60)*$Tnacion;
            $costo[$t]=round($valor3,2);
            $nacional[$t]=$costo[$t];
            $t++;
            unset($timeFormatN);
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualN[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }

        while ($n<32){ //LOCAL
          $sql4="";
          $sql4 .="nombreDepar= '$depar1'";
          $sql3="";
          $sql3 .="diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatL[$i]=$hours1*3600+$mins1*60;
            $individualL[$h]=$timeFormatL[$i];
            $individualL[$h]="$".($individualL[$h]/60)*$Tlocal;
            $individual[$y]=$individualL[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatL){
            $valor4=array_sum($timeFormatL);
            //echo "LOCAL TIEMPO ",$valor4,"\n";
            $min[$t]=$valor4;
            $valor4=($valor4/60)*$Tlocal;
            $costo[$t]=round($valor4,2);
            $local[$t]=$costo[$t];
            unset($timeFormatL);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualL[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }
        while ($n<33){ //CELULAR
          $sql4="";
          $sql4 .="nombreDepar= '$depar1'";
          $sql3="";
          $sql3 .="diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
          $h=0;
          $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing WHERE " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_outgoing_transfer WHERE " . $sql3 . " AND " . $sql4 . ")";

          $result2=mysqli_query($conexion,$sql2);

          while($ver2=mysqli_fetch_row($result2)){
            $valor1[$i]= $ver2[0];

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
            $timeFormatC[$i]=$hours1*3600+$mins1*60;
            $individualC[$h]=$timeFormatC[$i];
            $individualC[$h]="$".($individualC[$h]/60)*$Tcel;
            $individual[$y]=$individualL[$h];
            $i++;
            $h++;
            $y++;
          }
          if ($timeFormatC){
            $valor5=array_sum($timeFormatC);
            //echo "CELULAR TIEMPO ",$valor5,"\n";
            $min[$t]=$valor5;
            $valor5=($valor5/60)*$Tcel;
            $costo[$t]=round($valor5,2);
            $celular[$t]=$costo[$t];
            unset($timeFormatC);
            $t++;
          }
          $h=0;
          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          //echo $sql;
          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $ver[4] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individualC[$h] ?></td>

            </tr>
            <?php
            $h++;
          }
          $n++;
        }
      }
      ?>
      <?php
      $internacional="$".array_sum($internacional);
      $nacional="$".array_sum($nacional);
      $local="$".array_sum($local);
      $celular="$".array_sum($celular);

      $tiempo=array_sum($min);
      $hours = floor($tiempo / 3600);
      $mins = floor($tiempo / 60 % 60);
      $secs = floor($tiempo % 60);
      $timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
      $tarifa="$".array_sum($costo);
      ?>
      <td>TOTAL<td><td><td><td><td><td><td><td><?php echo $timeFormat ?><td><?php echo $tarifa ?></td></td>
    </table>
  </div>
</div>
<br>
<table border="1">
  <tr>
    <td></td>
    <td>Internacional</td>
    <td>Nacional</td>
    <td>Local</td>
    <td>Celular</td>
  </tr>
  <tr>
    <td>Precio Min</td>
    <td><?php	echo "$".$Tinter ?></td>
    <td><?php	echo "$".$Tnacion ?></td>
    <td><?php	echo "$".$Tlocal ?></td>
    <td><?php	echo "$".$Tcel ?></td>
  </tr>
  <tr>
    <td>Total</td>
    <td><?php	echo $internacional ?></td>
    <td><?php	echo $nacional ?></td>
    <td><?php	echo $local ?></td>
    <td><?php	echo $celular ?></td>
  </tr>
</table>
<br>
El precio total por la cantidad de minutos hablados es:
<?php
echo $tarifa;
?>
<br>
<br>
La cantidad de minutos hablados es:
<?php
echo $timeFormat;
?>
<br>
<br>
<br>
<form class="form-inline" method="post" action="generate_pdf_salientes.php">
  <?php if ($tarifa) {?>
    <input type='hidden' name='tarifa' value='<?php echo "$tarifa";?>'/>
  <?php } if ($timeFormat) {?>
    <input type='hidden' name='timeFormat' value='<?php echo "$timeFormat";?>'/>
  <?php } if ($option) {?>
    <input type='hidden' name='inter' value='<?php echo "$option";?>'/>
  <?php } if ($fecha) {?>
    <input type='hidden' name='dateFrom' value='<?php echo "$fecha";?>'/>
  <?php } if ($fecha1) {?>
    <input type='hidden' name='dateTo' value='<?php echo "$fecha1";?>'/>
  <?php } if ($sede1) {?>
    <input type='hidden' name='sede' value='<?php echo "$sede1";?>'/>
  <?php } if ($depar1) {?>
    <input type='hidden' name='nombreDepar' value='<?php echo "$depar1";?>'/>
  <?php } if ($fecha) {?>
    <input type='hidden' name='dateFrom' value='<?php echo "$fecha";?>'/>
  <?php } if ($fecha1) {?>
    <input type='hidden' name='dateTo' value='<?php echo "$fecha1";?>'/>
  <?php } if ($internacional) {?>
    <input type='hidden' name='internacional' value='<?php echo "$internacional";?>'/>
  <?php } if ($nacional) {?>
    <input type='hidden' name='nacional' value='<?php echo "$nacional";?>'/>
  <?php } if ($local) {?>
    <input type='hidden' name='local' value='<?php echo "$local";?>'/>
  <?php } if ($celular) {?>
    <input type='hidden' name='celular' value='<?php echo "$celular";?>'/>
  <?php } if ($Tinter) {?>
    <input type='hidden' name='Tinter' value='<?php echo "$Tinter";?>'/>
  <?php } if ($Tnacion) {?>
    <input type='hidden' name='Tnacion' value='<?php echo "$Tnacion";?>'/>
  <?php } if ($Tlocal) {?>
    <input type='hidden' name='Tlocal' value='<?php echo "$Tlocal";?>'/>
  <?php } if ($Tcel) {?>
    <input type='hidden' name='Tcel' value='<?php echo "$Tcel";?>'/>
  <?php }	foreach ($individual as $identifier) {
    echo "<input type='hidden' name='costos[]' value='$identifier' />";
  } ?>
  <button type="submit" id="pdf" name="generate_pdf" class="btn btn-primary"><i class="fa fa-pdf" aria-hidden="true"></i>
    Generate PDF</button>
  </form>
  <br>
  <form class="form-inline" method="post" action="generate_xls_salientes.php">
    <?php if ($tarifa) {?>
      <input type='hidden' name='tarifa' value='<?php echo "$tarifa";?>'/>
    <?php } if ($timeFormat) {?>
      <input type='hidden' name='timeFormat' value='<?php echo "$timeFormat";?>'/>
    <?php } if ($option) {?>
      <input type='hidden' name='inter' value='<?php echo "$option";?>'/>
    <?php } if ($fecha) {?>
      <input type='hidden' name='dateFrom' value='<?php echo "$fecha";?>'/>
    <?php } if ($fecha1) {?>
      <input type='hidden' name='dateTo' value='<?php echo "$fecha1";?>'/>
    <?php } if ($sede1) {?>
      <input type='hidden' name='sede' value='<?php echo "$sede1";?>'/>
    <?php } if ($depar1) {?>
      <input type='hidden' name='nombreDepar' value='<?php echo "$depar1";?>'/>
    <?php } if ($fecha) {?>
      <input type='hidden' name='dateFrom' value='<?php echo "$fecha";?>'/>
    <?php } if ($fecha1) {?>
      <input type='hidden' name='dateTo' value='<?php echo "$fecha1";?>'/>
    <?php } if ($internacional) {?>
      <input type='hidden' name='internacional' value='<?php echo "$internacional";?>'/>
    <?php } if ($nacional) {?>
      <input type='hidden' name='nacional' value='<?php echo "$nacional";?>'/>
    <?php } if ($local) {?>
      <input type='hidden' name='local' value='<?php echo "$local";?>'/>
    <?php } if ($celular) {?>
      <input type='hidden' name='celular' value='<?php echo "$celular";?>'/>
    <?php } if ($Tinter) {?>
      <input type='hidden' name='Tinter' value='<?php echo "$Tinter";?>'/>
    <?php } if ($Tnacion) {?>
      <input type='hidden' name='Tnacion' value='<?php echo "$Tnacion";?>'/>
    <?php } if ($Tlocal) {?>
      <input type='hidden' name='Tlocal' value='<?php echo "$Tlocal";?>'/>
    <?php } if ($Tcel) {?>
      <input type='hidden' name='Tcel' value='<?php echo "$Tcel";?>'/>
    <?php }	foreach ($individual as $identifier) {
      echo "<input type='hidden' name='costos[]' value='$identifier' />";
    } ?>
    <button type="submit" id="pdf" name="generate_pdf" class="btn btn-primary"><i class="fa fa-pdf" aria-hidden="true"></i>
      Generate XLS</button>
    </form>
    <br>
    </div>
  </body>
  </html>
