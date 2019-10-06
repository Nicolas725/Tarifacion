<?php
  include("./conexion.php");
  //$option = isset($_POST['taskOption']) ? $_POST['taskOption'] : false;
  $option = isset($_POST['inter']) ? $_POST['inter'] : false;
  $fecha = isset($_POST['dateFrom']) ? $_POST['dateFrom'] : false;
  $fecha1 = isset($_POST['dateTo']) ? $_POST['dateTo'] : false;
  $sede1 = isset($_POST['sede']) ? $_POST['sede'] : false;
  $depar1 = isset($_POST['nombreDepar']) ? $_POST['nombreDepar'] : false;
  echo $option,"\n";
  echo $sede1,"\n";
  echo $depar1,"\n";
 ?>

 <div class="row">
 	<div class="col-sm-12">
 	<h2>LLamadas Entrantes</h2>
  <form class="form-inline" method="post" action="generate_pdf_salientes.php">
    <?php if ($option) {?>
    <input type='hidden' name='inter' value='<?php echo "$option";?>'/>
    <?php } if ($fecha) {?>
    <input type='hidden' name='dateFrom' value='<?php echo "$fecha";?>'/>
    <?php } if ($fecha1) {?>
    <input type='hidden' name='dateTo' value='<?php echo "$fecha1";?>'/>
  <?php } if ($sede1) {?>
    <input type='hidden' name='sede' value='<?php echo "$sede1";?>'/>
  <?php } if ($depar1) {?>
    <input type='hidden' name='nombreDepar' value='<?php echo "$depar1";?>'/>
    <?php }?>
    <button type="submit" id="pdf" name="generate_pdf" class="btn btn-primary"><i class="fa fa-pdf" aria-hidden="true"></i>
    Generate PDF</button>
  </form>

   <table class="table table-hover table-condensed table-bordered">
     <tr>
       <td>Interno</td>
       <td>Nombre</td>
       <td>Fecha</td>
       <td>Hora</td>
       <td>Duration</td>
       <td>Destino</td>
       <td>Tipo</td>
       <td>Sede</td>
       <td>Departamento</td>
     </tr>

     <?php
     //error_reporting(E_ERROR | E_PARSE); //hace que no se muestren los warning
     if ($option && $fecha && $fecha1 && $sede1 && $depar1){ //UNO
       $sql3="";
       $sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1' AND nombreSede= '$sede1' AND nombreDepar='$depar1'";
       $sql="(SELECT chargeduserid, suscribername, date, time, callduration, diallednumber,
                    communicationtype, nombreSede, nombreDepar FROM tickets_outgoing WHERE
                    " . $sql3 . ")
                       UNION
             (SELECT chargeduserid,suscribername, date, time, callduration, diallednumber,
                    communicationtype, nombreSede, nombreDepar FROM tickets_outgoing_transfer WHERE
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

     </tr>
     <?php
   }
 } else if ($option && $fecha && $fecha1 && $sede1 ){ //DOS
     $sql3="";
     $sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1' AND nombreSede= '$sede1'";
     $sql="(SELECT chargeduserid, suscribername, date, time, callduration, diallednumber,
                  communicationtype, nombreSede, nombreDepar FROM tickets_outgoing WHERE
                  " . $sql3 . ")
                     UNION
           (SELECT chargeduserid,suscribername, date, time, callduration, diallednumber,
                  communicationtype, nombreSede, nombreDepar FROM tickets_outgoing_transfer WHERE
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

   </tr>
   <?php
 }
} else if ($option && $fecha && $fecha1 && $depar1 ){ //TRES
    $sql3="";
    $sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1' AND nombreDepar= '$depar1'";
    $sql="(SELECT chargeduserid, suscribername, date, time, callduration, diallednumber,
                 communicationtype, nombreSede, nombreDepar FROM tickets_outgoing WHERE
                 " . $sql3 . ")
                    UNION
          (SELECT chargeduserid,suscribername, date, time, callduration, diallednumber,
                 communicationtype, nombreSede, nombreDepar FROM tickets_outgoing_transfer WHERE
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

  </tr>
  <?php
}
} else if ($option && $sede1 && $depar1 ){ //CUATRO
    $sql3="";
    $sql3 .="chargeduserid=".$option." AND nombreSede= '$sede1' AND nombreDepar='$depar1'";
    $sql="(SELECT chargeduserid, suscribername, date, time, callduration, diallednumber,
                 communicationtype, nombreSede, nombreDepar FROM tickets_outgoing WHERE
                 " . $sql3 . ")
                    UNION
          (SELECT chargeduserid,suscribername, date, time, callduration, diallednumber,
                 communicationtype, nombreSede, nombreDepar FROM tickets_outgoing_transfer WHERE
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

  </tr>
  <?php
}
} else if ($fecha && $fecha1 && $sede1 && $depar1 ){ //CINCO
    $sql3="";
    $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND nombreSede= '$sede1' AND nombreDepar='$depar1'";
    $sql="(SELECT chargeduserid, suscribername, date, time, callduration, diallednumber,
                 communicationtype, nombreSede, nombreDepar FROM tickets_outgoing WHERE
                 " . $sql3 . ")
                    UNION
          (SELECT chargeduserid,suscribername, date, time, callduration, diallednumber,
                 communicationtype, nombreSede, nombreDepar FROM tickets_outgoing_transfer WHERE
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

  </tr>
  <?php
}
} else if ($option && $fecha && $fecha1){ //SEIS
    $sql3="";
    $sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1'";
    $sql="(SELECT chargeduserid, suscribername, date, time, callduration, diallednumber,
                 communicationtype, nombreSede, nombreDepar FROM tickets_outgoing WHERE
                 " . $sql3 . ")
                    UNION
          (SELECT chargeduserid,suscribername, date, time, callduration, diallednumber,
                 communicationtype, nombreSede, nombreDepar FROM tickets_outgoing_transfer WHERE
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

  </tr>
  <?php
}
} else if ($option && $sede1){ //SIESTE
    $sql3="";
    $sql3 .="chargeduserid=".$option." AND nombreSede= '$sede1'";
    $sql="(SELECT chargeduserid, suscribername, date, time, callduration, diallednumber,
                 communicationtype, nombreSede, nombreDepar FROM tickets_outgoing WHERE
                 " . $sql3 . ")
                    UNION
          (SELECT chargeduserid,suscribername, date, time, callduration, diallednumber,
                 communicationtype, nombreSede, nombreDepar FROM tickets_outgoing_transfer WHERE
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

  </tr>
  <?php
}
} else if ($option && $depar1){ //OCHO
    $sql3="";
    $sql3 .="chargeduserid=".$option." AND nombreDepar= '$depar1'";
    $sql="(SELECT chargeduserid, suscribername, date, time, callduration, diallednumber,
                 communicationtype, nombreSede, nombreDepar FROM tickets_outgoing WHERE
                 " . $sql3 . ")
                    UNION
          (SELECT chargeduserid,suscribername, date, time, callduration, diallednumber,
                 communicationtype, nombreSede, nombreDepar FROM tickets_outgoing_transfer WHERE
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

  </tr>
  <?php
}
} else if ($fecha && $fecha1 && $sede1){ //NUEVE
    $sql3="";
    $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND nombreSede= '$sede1'";
    $sql="(SELECT chargeduserid, suscribername, date, time, callduration, diallednumber,
                 communicationtype, nombreSede, nombreDepar FROM tickets_outgoing WHERE
                 " . $sql3 . ")
                    UNION
          (SELECT chargeduserid,suscribername, date, time, callduration, diallednumber,
                 communicationtype, nombreSede, nombreDepar FROM tickets_outgoing_transfer WHERE
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

  </tr>
  <?php
}
} else if ($fecha && $fecha1 && $depar1){ //DIEZ
    $sql3="";
    $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND nombreDepar= '$depar1'";
    $sql="(SELECT chargeduserid, suscribername, date, time, callduration, diallednumber,
                 communicationtype, nombreSede, nombreDepar FROM tickets_outgoing WHERE
                 " . $sql3 . ")
                    UNION
          (SELECT chargeduserid,suscribername, date, time, callduration, diallednumber,
                 communicationtype, nombreSede, nombreDepar FROM tickets_outgoing_transfer WHERE
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

  </tr>
  <?php
}
} else if ($sede1 && $depar1 ){ //ONCE
    $sql3="";
    $sql3 .="nombreSede= '$sede1' AND nombreDepar= '$depar1'";
    $sql="(SELECT chargeduserid, suscribername, date, time, callduration, diallednumber,
                 communicationtype, nombreSede, nombreDepar FROM tickets_outgoing WHERE
                 " . $sql3 . ")
                    UNION
          (SELECT chargeduserid,suscribername, date, time, callduration, diallednumber,
                 communicationtype, nombreSede, nombreDepar FROM tickets_outgoing_transfer WHERE
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

  </tr>
  <?php
}
} else if ($option){ //DOCE
    $sql3="";
    $sql3 .="chargeduserid=".$option."";
    $sql="(SELECT chargeduserid, suscribername, date, time, callduration, diallednumber,
                 communicationtype, nombreSede, nombreDepar FROM tickets_outgoing WHERE
                 " . $sql3 . ")
                    UNION
          (SELECT chargeduserid,suscribername, date, time, callduration, diallednumber,
                 communicationtype, nombreSede, nombreDepar FROM tickets_outgoing_transfer WHERE
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

  </tr>
  <?php
}
} else if ($fecha && $fecha1){ //TRECE
    $sql3="";
    $sql3 .="date BETWEEN '$fecha' AND '$fecha1'";
    $sql="(SELECT chargeduserid, suscribername, date, time, callduration, diallednumber,
                 communicationtype, nombreSede, nombreDepar FROM tickets_outgoing WHERE
                 " . $sql3 . ")
                    UNION
          (SELECT chargeduserid,suscribername, date, time, callduration, diallednumber,
                 communicationtype, nombreSede, nombreDepar FROM tickets_outgoing_transfer WHERE
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

  </tr>
  <?php
}
} else if ($sede1){ //CATORCE
    $sql3="";
    $sql3 .="nombreSede= '$sede1'";
    $sql="(SELECT chargeduserid, suscribername, date, time, callduration, diallednumber,
                 communicationtype, nombreSede, nombreDepar FROM tickets_outgoing WHERE
                 " . $sql3 . ")
                    UNION
          (SELECT chargeduserid,suscribername, date, time, callduration, diallednumber,
                 communicationtype, nombreSede, nombreDepar FROM tickets_outgoing_transfer WHERE
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

  </tr>
  <?php
}
} else if ($depar1 ){ //QUINCE
    $sql3="";
    $sql3 .="nombreDepar= '$depar1'";
    $sql="(SELECT chargeduserid, suscribername, date, time, callduration, diallednumber,
                 communicationtype, nombreSede, nombreDepar FROM tickets_outgoing WHERE
                 " . $sql3 . ")
                    UNION
          (SELECT chargeduserid,suscribername, date, time, callduration, diallednumber,
                 communicationtype, nombreSede, nombreDepar FROM tickets_outgoing_transfer WHERE
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

  </tr>
  <?php
}
}
 ?>
   </table>
   </div>
   </div>
