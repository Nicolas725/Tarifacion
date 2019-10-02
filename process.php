<?php
  include("./conexion.php");
  //$option = isset($_POST['taskOption']) ? $_POST['taskOption'] : false;
  $option = isset($_POST['inter']) ? $_POST['inter'] : false;
  $fecha = isset($_POST['dateFrom']) ? $_POST['dateFrom'] : false;
  $fecha1 = isset($_POST['dateTo']) ? $_POST['dateTo'] : false;
  echo $option;
 ?>

 <div class="row">
 	<div class="col-sm-12">
 	<h2>LLamadas Entrantes</h2>

  <form class="form-inline" method="post" action="generate_pdf.php">
    <input type='hidden' name='inter' value='<?php echo "$option";?>'/>
    <input type='hidden' name='dateFrom' value='<?php echo "$fecha";?>'/>
    <input type='hidden' name='dateTo' value='<?php echo "$fecha1";?>'/>
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
     </tr>

     <?php
     //error_reporting(E_ERROR | E_PARSE); //hace que no se muestren los warning
     //$sql1="";
     //$sql1 .="ORDER BY " . $option . "";
     $sql3="";
     $sql2="";
     $sql3 .="WHERE chargeduserid=".$option."";
     $sql2 .="date BETWEEN '$fecha' AND '$fecha1'";
     //$sql1 .="ORDER BY '" . $option . "'";

     $sql="(SELECT
                 chargeduserid,
                 suscribername,
                 date,
                 time,
                 callduration,
                 diallednumber,
                 communicationtype

                 FROM

                     tickets_incoming
                     " . $sql3 . " AND " . $sql2 . ")
                     UNION
           (SELECT
                       chargeduserid,
                       suscribername,
                       date,
                       time,
                       callduration,
                       diallednumber,
                       communicationtype

                       FROM

                           tickets_incoming_transfer
                           " . $sql3 . " AND " . $sql2 . ")
                                     ";

               echo $sql;

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

     </tr>
     <?php
   }
      ?>
   </table>
   </div>
   </div>
