<?php
include("./conexion.php");
header('Content-type:application/xls');
header('Content-Disposition: attachment; filename=llamadas_entrantes.xls');
$option = isset($_POST['inter']) ? $_POST['inter'] : false;
$fecha = isset($_POST['dateFrom']) ? $_POST['dateFrom'] : false;
$fecha1 = isset($_POST['dateTo']) ? $_POST['dateTo'] : false;
$sede1 = isset($_POST['sede']) ? $_POST['sede'] : false;
$depar1 = isset($_POST['nombreDepar']) ? $_POST['nombreDepar'] : false;
?>

<div class="row">
  <div class="col-sm-12">
    <h2>LLamadas Entrantes</h2>

    <table class="table table-hover table-condensed table-bordered">
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

      </tr>

      <?php
      error_reporting(E_ERROR | E_PARSE); //hace que no se muestren los warning
      if ($option && $fecha && $fecha1 && $sede1 && $depar1){ //UNO
        $s=0;
        $sql3="";
        $sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1' AND nombreSede= '$sede1' AND nombreDepar='$depar1'";
        $i=0;
        //error_reporting(E_ERROR | E_PARSE); //hace que no se muestren los warning
        $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_incoming WHERE " . $sql3 . ")
        UNION ALL
        (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_incoming_transfer WHERE " . $sql3 . ")";
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
          $timeFormat2[$i]=$hours1*3600+$mins1*60;
          $i++;
        }

        $valor2=array_sum($timeFormat2);
        $hours = floor($valor2 / 3600);
        $mins = floor($valor2 / 60 % 60);
        $secs = floor($valor2 % 60);
        $timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
        ?>
        <br>
        La cantidad de tiempo hablado es (hh/mm/ss):
        <?php
        echo $timeFormat;
        ?>
        <br>
        <br>

        <?php
        $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
        communicationtype, nombreSede, nombreDepar, callduration FROM tickets_incoming WHERE
        " . $sql3 . ")
        UNION ALL
        (SELECT chargeduserid,suscribername, date, time, diallednumber,
        communicationtype, nombreSede, nombreDepar, callduration FROM tickets_incoming_transfer WHERE
        " . $sql3 . ") ORDER BY callduration";
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

          </tr>
          <?php
          $s++;
        }
      }
      else if ($option && $fecha && $fecha1 && $sede1 ){ //DOS
        $s=0;
        $sql3="";
        $sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1' AND nombreSede= '$sede1'";
        $i=0;
        //error_reporting(E_ERROR | E_PARSE); //hace que no se muestren los warning
        $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_incoming WHERE " . $sql3 . ")
        UNION ALL
        (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_incoming_transfer WHERE " . $sql3 . ")";
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
          $timeFormat2[$i]=$hours1*3600+$mins1*60;
          $i++;
        }

        $valor2=array_sum($timeFormat2);
        $hours = floor($valor2 / 3600);
        $mins = floor($valor2 / 60 % 60);
        $secs = floor($valor2 % 60);
        $timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
        ?>
        <br>
        La cantidad de tiempo hablado es (hh/mm/ss):
        <?php
        echo $timeFormat;
        ?>
        <br>
        <br>

        <?php
        $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
        communicationtype, nombreSede, nombreDepar, callduration FROM tickets_incoming WHERE
        " . $sql3 . ")
        UNION ALL
        (SELECT chargeduserid,suscribername, date, time, diallednumber,
        communicationtype, nombreSede, nombreDepar, callduration FROM tickets_incoming_transfer WHERE
        " . $sql3 . ") ORDER BY callduration";
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

          </tr>
          <?php
          $s++;
        }
      }
      else if ($option && $fecha && $fecha1 && $depar1 ){ //TRES
        $s=0;
        $sql3="";
        $sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1' AND nombreDepar= '$depar1'";
        $i=0;
        //error_reporting(E_ERROR | E_PARSE); //hace que no se muestren los warning
        $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_incoming WHERE " . $sql3 . ")
        UNION ALL
        (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_incoming_transfer WHERE " . $sql3 . ")";
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
          $timeFormat2[$i]=$hours1*3600+$mins1*60;
          $i++;
        }

        $valor2=array_sum($timeFormat2);
        $hours = floor($valor2 / 3600);
        $mins = floor($valor2 / 60 % 60);
        $secs = floor($valor2 % 60);
        $timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
        ?>
        <br>
        La cantidad de tiempo hablado es (hh/mm/ss):
        <?php
        echo $timeFormat;
        ?>
        <br>
        <br>

        <?php
        $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
        communicationtype, nombreSede, nombreDepar, callduration FROM tickets_incoming WHERE
        " . $sql3 . ")
        UNION ALL
        (SELECT chargeduserid,suscribername, date, time, diallednumber,
        communicationtype, nombreSede, nombreDepar, callduration FROM tickets_incoming_transfer WHERE
        " . $sql3 . ") ORDER BY callduration";
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

          </tr>
          <?php
          $s++;
        }
      }
      else if ($option && $sede1 && $depar1 ){ //CUATRO
        $s=0;
        $sql3="";
        $sql3 .="chargeduserid=".$option." AND nombreSede= '$sede1' AND nombreDepar='$depar1'";
        $i=0;
        //error_reporting(E_ERROR | E_PARSE); //hace que no se muestren los warning
        $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_incoming WHERE " . $sql3 . ")
        UNION ALL
        (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_incoming_transfer WHERE " . $sql3 . ")";
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
          $timeFormat2[$i]=$hours1*3600+$mins1*60;
          $i++;
        }

        $valor2=array_sum($timeFormat2);
        $hours = floor($valor2 / 3600);
        $mins = floor($valor2 / 60 % 60);
        $secs = floor($valor2 % 60);
        $timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
        ?>
        <br>
        La cantidad de tiempo hablado es (hh/mm/ss):
        <?php
        echo $timeFormat;
        ?>
        <br>
        <br>

        <?php
        $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
        communicationtype, nombreSede, nombreDepar, callduration FROM tickets_incoming WHERE
        " . $sql3 . ")
        UNION ALL
        (SELECT chargeduserid,suscribername, date, time, diallednumber,
        communicationtype, nombreSede, nombreDepar, callduration FROM tickets_incoming_transfer WHERE
        " . $sql3 . ") ORDER BY callduration";
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

          </tr>
          <?php
          $s++;
        }
      }
      else if ($fecha && $fecha1 && $sede1 && $depar1 ){ //CINCO
        $s=0;
        $sql3="";
        $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND nombreSede= '$sede1' AND nombreDepar='$depar1'";
        $i=0;
        //error_reporting(E_ERROR | E_PARSE); //hace que no se muestren los warning
        $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_incoming WHERE " . $sql3 . ")
        UNION ALL
        (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_incoming_transfer WHERE " . $sql3 . ")";
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
          $timeFormat2[$i]=$hours1*3600+$mins1*60;
          $i++;
        }

        $valor2=array_sum($timeFormat2);
        $hours = floor($valor2 / 3600);
        $mins = floor($valor2 / 60 % 60);
        $secs = floor($valor2 % 60);
        $timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
        ?>
        <br>
        La cantidad de tiempo hablado es (hh/mm/ss):
        <?php
        echo $timeFormat;
        ?>
        <br>
        <br>

        <?php
        $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
        communicationtype, nombreSede, nombreDepar, callduration FROM tickets_incoming WHERE
        " . $sql3 . ")
        UNION ALL
        (SELECT chargeduserid,suscribername, date, time, diallednumber,
        communicationtype, nombreSede, nombreDepar, callduration FROM tickets_incoming_transfer WHERE
        " . $sql3 . ") ORDER BY callduration";
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

          </tr>
          <?php
          $s++;
        }
      }
      else if ($option && $fecha && $fecha1){ //SEIS
        $s=0;
        $sql3="";
        $sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1'";
        $i=0;
        //error_reporting(E_ERROR | E_PARSE); //hace que no se muestren los warning
        $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_incoming WHERE " . $sql3 . ")
        UNION ALL
        (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_incoming_transfer WHERE " . $sql3 . ")";
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
          $timeFormat2[$i]=$hours1*3600+$mins1*60;
          $i++;
        }

        $valor2=array_sum($timeFormat2);
        $hours = floor($valor2 / 3600);
        $mins = floor($valor2 / 60 % 60);
        $secs = floor($valor2 % 60);
        $timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
        ?>
        <br>
        La cantidad de tiempo hablado es (hh/mm/ss):
        <?php
        echo $timeFormat;
        ?>
        <br>
        <br>

        <?php
        $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
        communicationtype, nombreSede, nombreDepar, callduration FROM tickets_incoming WHERE
        " . $sql3 . ")
        UNION ALL
        (SELECT chargeduserid,suscribername, date, time, diallednumber,
        communicationtype, nombreSede, nombreDepar, callduration FROM tickets_incoming_transfer WHERE
        " . $sql3 . ") ORDER BY callduration";
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

          </tr>
          <?php
          $s++;
        }
      }
      else if ($option && $sede1){ //SIETE
        $s=0;
        $sql3="";
        $sql3 .="chargeduserid=".$option." AND nombreSede= '$sede1'";
        $i=0;
        //error_reporting(E_ERROR | E_PARSE); //hace que no se muestren los warning
        $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_incoming WHERE " . $sql3 . ")
        UNION ALL
        (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_incoming_transfer WHERE " . $sql3 . ")";
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
          $timeFormat2[$i]=$hours1*3600+$mins1*60;
          $i++;
        }

        $valor2=array_sum($timeFormat2);
        $hours = floor($valor2 / 3600);
        $mins = floor($valor2 / 60 % 60);
        $secs = floor($valor2 % 60);
        $timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
        ?>
        <br>
        La cantidad de tiempo hablado es (hh/mm/ss):
        <?php
        echo $timeFormat;
        ?>
        <br>
        <br>

        <?php
        $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
        communicationtype, nombreSede, nombreDepar, callduration FROM tickets_incoming WHERE
        " . $sql3 . ")
        UNION ALL
        (SELECT chargeduserid,suscribername, date, time, diallednumber,
        communicationtype, nombreSede, nombreDepar, callduration FROM tickets_incoming_transfer WHERE
        " . $sql3 . ") ORDER BY callduration";
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

          </tr>
          <?php
          $s++;
        }
      }
      else if ($option && $depar1){ //OCHO
        $s=0;
        $sql3="";
        $sql3 .="chargeduserid=".$option." AND nombreDepar= '$depar1'";
        $i=0;
        //error_reporting(E_ERROR | E_PARSE); //hace que no se muestren los warning
        $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_incoming WHERE " . $sql3 . ")
        UNION ALL
        (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_incoming_transfer WHERE " . $sql3 . ")";
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
          $timeFormat2[$i]=$hours1*3600+$mins1*60;
          $i++;
        }

        $valor2=array_sum($timeFormat2);
        $hours = floor($valor2 / 3600);
        $mins = floor($valor2 / 60 % 60);
        $secs = floor($valor2 % 60);
        $timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
        ?>
        <br>
        La cantidad de tiempo hablado es (hh/mm/ss):
        <?php
        echo $timeFormat;
        ?>
        <br>
        <br>

        <?php
        $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
        communicationtype, nombreSede, nombreDepar, callduration FROM tickets_incoming WHERE
        " . $sql3 . ")
        UNION ALL
        (SELECT chargeduserid,suscribername, date, time, diallednumber,
        communicationtype, nombreSede, nombreDepar, callduration FROM tickets_incoming_transfer WHERE
        " . $sql3 . ") ORDER BY callduration";
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

          </tr>
          <?php
          $s++;
        }
      }
      else if ($fecha && $fecha1 && $sede1){ //NUEVE
        $s=0;
        $sql3="";
        $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND nombreSede= '$sede1'";
        $i=0;
        //error_reporting(E_ERROR | E_PARSE); //hace que no se muestren los warning
        $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_incoming WHERE " . $sql3 . ")
        UNION ALL
        (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_incoming_transfer WHERE " . $sql3 . ")";
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
          $timeFormat2[$i]=$hours1*3600+$mins1*60;
          $i++;
        }

        $valor2=array_sum($timeFormat2);
        $hours = floor($valor2 / 3600);
        $mins = floor($valor2 / 60 % 60);
        $secs = floor($valor2 % 60);
        $timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
        ?>
        <br>
        La cantidad de tiempo hablado es (hh/mm/ss):
        <?php
        echo $timeFormat;
        ?>
        <br>
        <br>

        <?php
        $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
        communicationtype, nombreSede, nombreDepar, callduration FROM tickets_incoming WHERE
        " . $sql3 . ")
        UNION ALL
        (SELECT chargeduserid,suscribername, date, time, diallednumber,
        communicationtype, nombreSede, nombreDepar, callduration FROM tickets_incoming_transfer WHERE
        " . $sql3 . ") ORDER BY callduration";
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

          </tr>
          <?php
          $s++;
        }
      }
      else if ($fecha && $fecha1 && $depar1){ //DIEZ
        $s=0;
        $sql3="";
        $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND nombreDepar= '$depar1'";
        $i=0;
        //error_reporting(E_ERROR | E_PARSE); //hace que no se muestren los warning
        $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_incoming WHERE " . $sql3 . ")
        UNION ALL
        (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_incoming_transfer WHERE " . $sql3 . ")";
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
          $timeFormat2[$i]=$hours1*3600+$mins1*60;
          $i++;
        }

        $valor2=array_sum($timeFormat2);
        $hours = floor($valor2 / 3600);
        $mins = floor($valor2 / 60 % 60);
        $secs = floor($valor2 % 60);
        $timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
        ?>
        <br>
        La cantidad de tiempo hablado es (hh/mm/ss):
        <?php
        echo $timeFormat;
        ?>
        <br>
        <br>

        <?php
        $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
        communicationtype, nombreSede, nombreDepar, callduration FROM tickets_incoming WHERE
        " . $sql3 . ")
        UNION ALL
        (SELECT chargeduserid,suscribername, date, time, diallednumber,
        communicationtype, nombreSede, nombreDepar, callduration FROM tickets_incoming_transfer WHERE
        " . $sql3 . ") ORDER BY callduration";
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

          </tr>
          <?php
          $s++;
        }
      }
      else if ($sede1 && $depar1 ){ //ONCE
        $s=0;
        $sql3="";
        $sql3 .="nombreSede= '$sede1' AND nombreDepar= '$depar1'";
        $i=0;
        //error_reporting(E_ERROR | E_PARSE); //hace que no se muestren los warning
        $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_incoming WHERE " . $sql3 . ")
        UNION ALL
        (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_incoming_transfer WHERE " . $sql3 . ")";
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
          $timeFormat2[$i]=$hours1*3600+$mins1*60;
          $i++;
        }

        $valor2=array_sum($timeFormat2);
        $hours = floor($valor2 / 3600);
        $mins = floor($valor2 / 60 % 60);
        $secs = floor($valor2 % 60);
        $timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
        ?>
        <br>
        La cantidad de tiempo hablado es (hh/mm/ss):
        <?php
        echo $timeFormat;
        ?>
        <br>
        <br>

        <?php
        $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
        communicationtype, nombreSede, nombreDepar, callduration FROM tickets_incoming WHERE
        " . $sql3 . ")
        UNION ALL
        (SELECT chargeduserid,suscribername, date, time, diallednumber,
        communicationtype, nombreSede, nombreDepar, callduration FROM tickets_incoming_transfer WHERE
        " . $sql3 . ") ORDER BY callduration";
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

          </tr>
          <?php
          $s++;
        }
      }
      else if ($option){ //DOCE
        $s=0;
        $sql3="";
        $sql3 .="chargeduserid=".$option."";
        $i=0;
        //error_reporting(E_ERROR | E_PARSE); //hace que no se muestren los warning
        $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_incoming WHERE " . $sql3 . ")
        UNION ALL
        (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_incoming_transfer WHERE " . $sql3 . ")";
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
          $timeFormat2[$i]=$hours1*3600+$mins1*60;
          $i++;
        }

        $valor2=array_sum($timeFormat2);
        $hours = floor($valor2 / 3600);
        $mins = floor($valor2 / 60 % 60);
        $secs = floor($valor2 % 60);
        $timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
        ?>
        <br>
        La cantidad de tiempo hablado es (hh/mm/ss):
        <?php
        echo $timeFormat;
        ?>
        <br>
        <br>

        <?php
        $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
        communicationtype, nombreSede, nombreDepar, callduration FROM tickets_incoming WHERE
        " . $sql3 . ")
        UNION ALL
        (SELECT chargeduserid,suscribername, date, time, diallednumber,
        communicationtype, nombreSede, nombreDepar, callduration FROM tickets_incoming_transfer WHERE
        " . $sql3 . ") ORDER BY callduration";
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

          </tr>
          <?php
          $s++;
        }
      }
      else if ($fecha && $fecha1){ //TRECE
        $s=0;
        $sql3="";
        $sql3 .="date BETWEEN '$fecha' AND '$fecha1'";
        $i=0;
        //error_reporting(E_ERROR | E_PARSE); //hace que no se muestren los warning
        $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_incoming WHERE " . $sql3 . ")
        UNION ALL
        (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_incoming_transfer WHERE " . $sql3 . ")";
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
          $timeFormat2[$i]=$hours1*3600+$mins1*60;
          $i++;
        }

        $valor2=array_sum($timeFormat2);
        $hours = floor($valor2 / 3600);
        $mins = floor($valor2 / 60 % 60);
        $secs = floor($valor2 % 60);
        $timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
        ?>
        <br>
        La cantidad de tiempo hablado es (hh/mm/ss):
        <?php
        echo $timeFormat;
        ?>
        <br>
        <br>

        <?php
        $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
        communicationtype, nombreSede, nombreDepar, callduration FROM tickets_incoming WHERE
        " . $sql3 . ")
        UNION ALL
        (SELECT chargeduserid,suscribername, date, time, diallednumber,
        communicationtype, nombreSede, nombreDepar, callduration FROM tickets_incoming_transfer WHERE
        " . $sql3 . ") ORDER BY callduration";
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

          </tr>
          <?php
          $s++;
        }
      }
      else if ($sede1){ //CATORCE
        $s=0;
        $sql3="";
        $sql3 .="nombreSede= '$sede1'";
        $i=0;
        //error_reporting(E_ERROR | E_PARSE); //hace que no se muestren los warning
        $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_incoming WHERE " . $sql3 . ")
        UNION ALL
        (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_incoming_transfer WHERE " . $sql3 . ")";
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
          $timeFormat2[$i]=$hours1*3600+$mins1*60;
          $i++;
        }

        $valor2=array_sum($timeFormat2);
        $hours = floor($valor2 / 3600);
        $mins = floor($valor2 / 60 % 60);
        $secs = floor($valor2 % 60);
        $timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
        ?>
        <br>
        La cantidad de tiempo hablado es (hh/mm/ss):
        <?php
        echo $timeFormat;
        ?>
        <br>
        <br>

        <?php
        $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
        communicationtype, nombreSede, nombreDepar, callduration FROM tickets_incoming WHERE
        " . $sql3 . ")
        UNION ALL
        (SELECT chargeduserid,suscribername, date, time, diallednumber,
        communicationtype, nombreSede, nombreDepar, callduration FROM tickets_incoming_transfer WHERE
        " . $sql3 . ") ORDER BY callduration";
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

          </tr>
          <?php
          $s++;
        }
      }
      else if ($depar1 ){ //QUINCE
        $s=0;
        $sql3="";
        $sql3 .="nombreDepar= '$depar1'";
        $i=0;
        //error_reporting(E_ERROR | E_PARSE); //hace que no se muestren los warning
        $sql2="(SELECT TIME_TO_SEC((`callduration`)) FROM tickets_incoming WHERE " . $sql3 . ")
        UNION ALL
        (SELECT TIME_TO_SEC((`callduration`)) FROM tickets_incoming_transfer WHERE " . $sql3 . ")";
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
          $timeFormat2[$i]=$hours1*3600+$mins1*60;
          $i++;
        }

        $valor2=array_sum($timeFormat2);
        $hours = floor($valor2 / 3600);
        $mins = floor($valor2 / 60 % 60);
        $secs = floor($valor2 % 60);
        $timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
        ?>
        <br>
        La cantidad de tiempo hablado es (hh/mm/ss):
        <?php
        echo $timeFormat;
        ?>
        <br>
        <br>

        <?php
        $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
        communicationtype, nombreSede, nombreDepar, callduration FROM tickets_incoming WHERE
        " . $sql3 . ")
        UNION ALL
        (SELECT chargeduserid,suscribername, date, time, diallednumber,
        communicationtype, nombreSede, nombreDepar, callduration FROM tickets_incoming_transfer WHERE
        " . $sql3 . ") ORDER BY callduration";
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

          </tr>
          <?php
          $s++;
        }
      }
      ?>
      <td>TOTAL<td><td><td><td><td><td><td><td><?php echo $timeFormat ?><td><td></td></td>
    </table>
  </div>
</div>
