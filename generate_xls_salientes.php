<?php
include("./conexion.php");
error_reporting(E_ERROR | E_PARSE); //hace que no se muestren los warning
header('Content-type:application/xls');
header('Content-Disposition: attachment; filename=llamadas_salientes.xls');

$option = isset($_POST['inter']) ? $_POST['inter'] : false;
$fecha = isset($_POST['dateFrom']) ? $_POST['dateFrom'] : false;
$fecha1 = isset($_POST['dateTo']) ? $_POST['dateTo'] : false;
$sede1 = isset($_POST['sede']) ? $_POST['sede'] : false;
$depar1 = isset($_POST['nombreDepar']) ? $_POST['nombreDepar'] : false;
$tarifa = isset($_POST['tarifa']) ? $_POST['tarifa'] : false;
$timeFormat = isset($_POST['timeFormat']) ? $_POST['timeFormat'] : false;
$internacional = isset($_POST['internacional']) ? $_POST['internacional'] : false;
$nacional = isset($_POST['nacional']) ? $_POST['nacional'] : false;
$local = isset($_POST['local']) ? $_POST['local'] : false;
$celular = isset($_POST['celular']) ? $_POST['celular'] : false;
$Tinter = isset($_POST['Tinter']) ? $_POST['Tinter'] : false;
$Tnacion = isset($_POST['Tnacion']) ? $_POST['Tnacion'] : false;
$Tlocal = isset($_POST['Tlocal']) ? $_POST['Tlocal'] : false;
$Tcel = isset($_POST['Tcel']) ? $_POST['Tcel'] : false;
$individual = isset($_POST['costos']) ? $_POST['costos'] : false;


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

?>

<div class="row">
  <div class="col-sm-12">
    <h2>LLamadas Salientes</h2>
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
    La cantidad de tiempo hablado es (hh/mm/ss):
    <?php
    echo $timeFormat;
    ?>
    <br>
    <br>
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
        <td>Costos</td>

      </tr>

      <?php
      if ($option && $fecha && $fecha1 && $sede1 && $depar1){ //UNO
        $n=0;
        $t=0;
        $l=0;
        $y=0;
        $s=0;
        while ($n<1){ //INTERNACIONAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1' AND nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1'
          AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }
      }
      else if ($option && $fecha && $fecha1 && $sede1 ){ //DOS
        $n=0;
        $t=0;
        $l=0;
        $y=0;
        $s=0;
        while ($n<1){ //INTERNACIONAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1'
          AND diallednumber LIKE '$Prefijo[$n]%'";
          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }
      }
      else if ($option && $fecha && $fecha1 && $depar1 ){ //TRES
        $n=0;
        $t=0;
        $l=0;
        $y=0;
        $s=0;
        while ($n<1){ //INTERNACIONAL
          $sql4="";
          $sql4 .="nombreDepar= '$depar1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1'
          AND diallednumber LIKE '$Prefijo[$n]%'";
          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }
      }
      else if ($option && $sede1 && $depar1 ){ //CUATRO
        $n=0;
        $t=0;
        $l=0;
        $y=0;
        $s=0;
        while ($n<1){ //INTERNACIONAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1' AND nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND diallednumber LIKE '$Prefijo[$n]%'";
          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }

        while ($n<28){ //NACIONAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1' AND nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }

        while ($n<32){ //LOCAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1' AND nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }
        while ($n<33){ //CELULAR
          $sql4="";
          $sql4 .="nombreSede= '$sede1' AND nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }
      }
      else if ($fecha && $fecha1 && $sede1 && $depar1 ){ //CINCO
        $n=0;
        $t=0;
        $l=0;
        $y=0;
        $s=0;
        while ($n<1){ //INTERNACIONAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1' AND nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";
          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }

        while ($n<28){ //NACIONAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1' AND nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }

        while ($n<32){ //LOCAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1' AND nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }
        while ($n<33){ //CELULAR
          $sql4="";
          $sql4 .="nombreSede= '$sede1' AND nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }
      }
      else if ($option && $fecha && $fecha1){ //SEIS
        $n=0;
        $t=0;
        $l=0;
        $y=0;
        $s=0;
        while ($n<1){ //INTERNACIONAL
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1'
          AND diallednumber LIKE '$Prefijo[$n]%'";
          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }

        while ($n<28){ //NACIONAL
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1'
          AND diallednumber LIKE '$Prefijo[$n]%'";
          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }

        while ($n<32){ //LOCAL
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1'
          AND diallednumber LIKE '$Prefijo[$n]%'";
          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }
        while ($n<33){ //CELULAR
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1'
          AND diallednumber LIKE '$Prefijo[$n]%'";
          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }
      }
      else if ($option && $sede1){ //SIESTE
        $n=0;
        $t=0;
        $l=0;
        $y=0;
        $s=0;
        while ($n<1){ //INTERNACIONAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND diallednumber LIKE '$Prefijo[$n]%'";
          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }

        while ($n<28){ //NACIONAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }

        while ($n<32){ //LOCAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }
        while ($n<33){ //CELULAR
          $sql4="";
          $sql4 .="nombreSede= '$sede1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }
      }
      else if ($option && $depar1){ //OCHO
        $n=0;
        $t=0;
        $l=0;
        $y=0;
        $s=0;
        while ($n<1){ //INTERNACIONAL
          $sql4="";
          $sql4 .="nombreDepar= '$depar1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND diallednumber LIKE '$Prefijo[$n]%'";
          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }

        while ($n<28){ //NACIONAL
          $sql4="";
          $sql4 .="nombreDepar= '$depar1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }

        while ($n<32){ //LOCAL
          $sql4="";
          $sql4 .="nombreDepar= '$depar1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }
        while ($n<33){ //CELULAR
          $sql4="";
          $sql4 .="nombreDepar= '$depar1'";
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }
      }
      else if ($fecha && $fecha1 && $sede1){ //NUEVE
        $n=0;
        $t=0;
        $l=0;
        $y=0;
        $s=0;
        while ($n<1){ //INTERNACIONAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1'";
          $sql3="";
          $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";
          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }

        while ($n<28){ //NACIONAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1'";
          $sql3="";
          $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }

        while ($n<32){ //LOCAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1'";
          $sql3="";
          $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }
        while ($n<33){ //CELULAR
          $sql4="";
          $sql4 .="nombreSede= '$sede1'";
          $sql3="";
          $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
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
            $i++;
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
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }
      }
      else if ($fecha && $fecha1 && $depar1){ //DIEZ
        $n=0;
        $t=0;
        $l=0;
        $y=0;
        $s=0;
        while ($n<1){ //INTERNACIONAL
          $sql4="";
          $sql4 .="nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";
          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }

        while ($n<28){ //NACIONAL
          $sql4="";
          $sql4 .="nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }

        while ($n<32){ //LOCAL
          $sql4="";
          $sql4 .="nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }
        while ($n<33){ //CELULAR
          $sql4="";
          $sql4 .="nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
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
            $i++;
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
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }
      }
      else if ($sede1 && $depar1 ){ //ONCE
        $n=0;
        $t=0;
        $l=0;
        $y=0;
        $s=0;
        while ($n<1){ //INTERNACIONAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1' AND nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="diallednumber LIKE '$Prefijo[$n]%'";
          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }

        while ($n<28){ //NACIONAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1' AND nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }

        while ($n<32){ //LOCAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1' AND nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }
        while ($n<33){ //CELULAR
          $sql4="";
          $sql4 .="nombreSede= '$sede1' AND nombreDepar='$depar1'";
          $sql3="";
          $sql3 .="diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
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
            $i++;
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
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }
      }
      else if ($option){ //DOCE
        $n=0;
        $t=0;
        $l=0;
        $y=0;
        $s=0;
        while ($n<1){ //INTERNACIONAL
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND diallednumber LIKE '$Prefijo[$n]%'";
          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }

        while ($n<28){ //NACIONAL
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND diallednumber LIKE '$Prefijo[$n]%'";
          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }

        while ($n<32){ //LOCAL
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND diallednumber LIKE '$Prefijo[$n]%'";
          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }
        while ($n<33){ //CELULAR
          $sql3="";
          $sql3 .="chargeduserid=".$option." AND diallednumber LIKE '$Prefijo[$n]%'";
          $i=0;
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
            $i++;
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
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }
      }
      else if ($fecha && $fecha1){ //TRECE
        $n=0;
        $t=0;
        $l=0;
        $y=0;
        $s=0;
        while ($n<1){ //INTERNACIONAL
          $sql3="";
          $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";
          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }

        while ($n<28){ //NACIONAL
          $sql3="";
          $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";
          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }

        while ($n<32){ //LOCAL
          $sql3="";
          $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";
          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }
        while ($n<33){ //CELULAR
          $sql3="";
          $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";
          $i=0;
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
            $i++;
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
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }
      }
      else if ($sede1){ //CATORCE
        $n=0;
        $t=0;
        $l=0;
        $y=0;
        $s=0;
        while ($n<1){ //INTERNACIONAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1'";
          $sql3="";
          $sql3 .="diallednumber LIKE '$Prefijo[$n]%'";
          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }

        while ($n<28){ //NACIONAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1'";
          $sql3="";
          $sql3 .="diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }

        while ($n<32){ //LOCAL
          $sql4="";
          $sql4 .="nombreSede= '$sede1'";
          $sql3="";
          $sql3 .="diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }
        while ($n<33){ //CELULAR
          $sql4="";
          $sql4 .="nombreSede= '$sede1'";
          $sql3="";
          $sql3 .="diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
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
            $i++;
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
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }
      }
      else if ($depar1 ){ //QUINCE
        $n=0;
        $t=0;
        $l=0;
        $y=0;
        $s=0;
        while ($n<1){ //INTERNACIONAL
          $sql4="";
          $sql4 .="nombreDepar= '$depar1'";
          $sql3="";
          $sql3 .="diallednumber LIKE '$Prefijo[$n]%'";
          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }

        while ($n<28){ //NACIONAL
          $sql4="";
          $sql4 .="nombreDepar= '$depar1'";
          $sql3="";
          $sql3 .="diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }

        while ($n<32){ //LOCAL
          $sql4="";
          $sql4 .="nombreDepar= '$depar1'";
          $sql3="";
          $sql3 .="diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
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
            $i++;
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

          $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
          " . $sql3 . " AND " . $sql4 . ")
          UNION ALL
          (SELECT chargeduserid,suscribername, date, time, diallednumber,
          communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
          " . $sql3 . " AND " . $sql4 . ")";

          $result=mysqli_query($conexion,$sql);
          while($ver=mysqli_fetch_row($result)){
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }
        while ($n<33){ //CELULAR
          $sql4="";
          $sql4 .="nombreDepar= '$depar1'";
          $sql3="";
          $sql3 .="diallednumber LIKE '$Prefijo[$n]%'";

          $i=0;
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
            $i++;
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
            $hola[$s] .= "'$ver[4]";
            ?>
            <tr>
              <td><?php echo $ver[0] ?></td>
              <td><?php echo $ver[1] ?></td>
              <td><?php echo $ver[2] ?></td>
              <td><?php echo $ver[3] ?></td>
              <td><?php echo $hola[$s] ?></td>
              <td><?php echo $ver[5] ?></td>
              <td><?php echo $ver[6] ?></td>
              <td><?php echo $ver[7] ?></td>
              <td><?php echo $ver[8] ?></td>
              <td><?php echo $individual[$y] ?></td>

            </tr>
            <?php
            $y++;
            $s++;
          }
          $n++;
        }
      }

      /*$internacional="$".array_sum($internacional);
      $nacional="$".array_sum($nacional);
      $local="$".array_sum($local);
      $celular="$".array_sum($celular);

      $tiempo=array_sum($min);
      $hours = floor($tiempo / 3600);
      $mins = floor($tiempo / 60 % 60);
      $secs = floor($tiempo % 60);
      $timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
      $tarifa="$".array_sum($costo);*/
      ?>
      <td>TOTAL<td><td><td><td><td><td><td><td><?php echo $timeFormat ?><td><?php echo $tarifa ?></td></td>
    </table>
  </div>
</div>
