<?php
//include conexionection file
include("./conexion.php");
require "/usr/share/php/fpdf/fpdf.php";


class PDF extends FPDF
{
// Page header
function Header()
{
   // Logo
   $this->Image('/var/www/html/PAGINA_1/Tarifacion/imagenes/UM_logo.jpg',10,10,50);

   $this->SetFont('Arial','B',13);
   // Move to the right
   $this->Cell(80);
   // Title
   $this->Cell(-15);

   $this->Cell(80,10,'Llamadas salientes',1,0,'C');
   // Line break
   $this->Ln(55);
}

// Page footer
function Footer()
{
   // Position at 1.5 cm from bottom
   $this->SetY(-15);
   // Arial italic 8
   $this->SetFont('Arial','I',8);
   // Page number
   $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}


$option = isset($_POST['inter']) ? $_POST['inter'] : false;
$fecha = isset($_POST['dateFrom']) ? $_POST['dateFrom'] : false;
$fecha1 = isset($_POST['dateTo']) ? $_POST['dateTo'] : false;
$sede1 = isset($_POST['sede']) ? $_POST['sede'] : false;
$depar1 = isset($_POST['nombreDepar']) ? $_POST['nombreDepar'] : false;
//echo $option;
//echo $fecha;
//echo $fecha1;
//echo $sede1;
//echo $depar1;


if ($option && $fecha && $fecha1 && $sede1 && $depar1){ //UNO

  $sql3="";
  $sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1' AND nombreSede= '$sede1' AND nombreDepar='$depar1'";
  $display_heading = array('chargeduserid'=>'Interno', 'suscribername'=> 'Nombre', 'date'=> 'Fecha', 'time'=> 'Hora','callduration'=> 'Duracion', 'diallednumber'=>'Destino', 'communicationtype'=>'Tipo', 'nombreSede'=>'Sede', 'nombreDepar'=>'Departamento');
  $sql="(SELECT chargeduserid, suscribername, date, time, callduration, diallednumber,
               communicationtype, nombreSede, nombreDepar FROM tickets_outgoing WHERE
               " . $sql3 . ")
                  UNION
        (SELECT chargeduserid,suscribername, date, time, callduration, diallednumber,
               communicationtype, nombreSede, nombreDepar FROM tickets_outgoing_transfer WHERE
               " . $sql3 . ")";
   //echo $sql;
    $result = mysqli_query($conexion,$sql) or die("database error:". mysqli_error($conexion));

    $pdf = new PDF();
    //header
    $pdf->AddPage();
    //foter page
    $pdf->AliasNbPages();
    $pdf->SetFont('Arial','B',9);
    foreach($display_heading as $heading) {
      $pdf->Cell(21.5,9,$heading,1);
    }
    foreach($result as $row) {
    $pdf->SetFont('Arial','',8);
    $pdf->Ln();
    foreach($row as $column)
    $pdf->Cell(21.5,9,$column,1);
    }
    $pdf->Output();

} else if ($option && $fecha && $fecha1 && $sede1 ){ //DOS

$sql3="";
$sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1' AND nombreSede= '$sede1'";
$display_heading = array('chargeduserid'=>'Interno', 'suscribername'=> 'Nombre', 'date'=> 'Fecha', 'time'=> 'Hora','callduration'=> 'Duracion', 'diallednumber'=>'Destino', 'communicationtype'=>'Tipo', 'nombreSede'=>'Sede', 'nombreDepar'=>'Departamento');
$sql="(SELECT chargeduserid, suscribername, date, time, callduration, diallednumber,
             communicationtype, nombreSede, nombreDepar FROM tickets_outgoing WHERE
             " . $sql3 . ")
                UNION
      (SELECT chargeduserid,suscribername, date, time, callduration, diallednumber,
             communicationtype, nombreSede, nombreDepar FROM tickets_outgoing_transfer WHERE
             " . $sql3 . ")";
 //echo $sql;
  $result = mysqli_query($conexion,$sql) or die("database error:". mysqli_error($conexion));

  $pdf = new PDF();
  //header
  $pdf->AddPage();
  //foter page
  $pdf->AliasNbPages();
  $pdf->SetFont('Arial','B',9);
  foreach($display_heading as $heading) {
    $pdf->Cell(21.5,9,$heading,1);
  }
  foreach($result as $row) {
  $pdf->SetFont('Arial','',8);
  $pdf->Ln();
  foreach($row as $column)
  $pdf->Cell(21.5,9,$column,1);
  }
  $pdf->Output();

} else if ($option && $fecha && $fecha1 && $depar1 ){ //TRES

$sql3="";
$sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1' AND nombreDepar= '$depar1'";
$display_heading = array('chargeduserid'=>'Interno', 'suscribername'=> 'Nombre', 'date'=> 'Fecha', 'time'=> 'Hora','callduration'=> 'Duracion', 'diallednumber'=>'Destino', 'communicationtype'=>'Tipo', 'nombreSede'=>'Sede', 'nombreDepar'=>'Departamento');
$sql="(SELECT chargeduserid, suscribername, date, time, callduration, diallednumber,
            communicationtype, nombreSede, nombreDepar FROM tickets_outgoing WHERE
            " . $sql3 . ")
               UNION
     (SELECT chargeduserid,suscribername, date, time, callduration, diallednumber,
            communicationtype, nombreSede, nombreDepar FROM tickets_outgoing_transfer WHERE
            " . $sql3 . ")";
//echo $sql;
 $result = mysqli_query($conexion,$sql) or die("database error:". mysqli_error($conexion));

 $pdf = new PDF();
 //header
 $pdf->AddPage();
 //foter page
 $pdf->AliasNbPages();
 $pdf->SetFont('Arial','B',9);
 foreach($display_heading as $heading) {
   $pdf->Cell(21.5,9,$heading,1);
 }
 foreach($result as $row) {
 $pdf->SetFont('Arial','',8);
 $pdf->Ln();
 foreach($row as $column)
 $pdf->Cell(21.5,9,$column,1);
 }
 $pdf->Output();

} else if ($option && $sede1 && $depar1 ){ //CUATRO

$sql3="";
$sql3 .="chargeduserid=".$option." AND nombreSede= '$sede1' AND nombreDepar='$depar1'";
$display_heading = array('chargeduserid'=>'Interno', 'suscribername'=> 'Nombre', 'date'=> 'Fecha', 'time'=> 'Hora','callduration'=> 'Duracion', 'diallednumber'=>'Destino', 'communicationtype'=>'Tipo', 'nombreSede'=>'Sede', 'nombreDepar'=>'Departamento');
$sql="(SELECT chargeduserid, suscribername, date, time, callduration, diallednumber,
            communicationtype, nombreSede, nombreDepar FROM tickets_outgoing WHERE
            " . $sql3 . ")
               UNION
     (SELECT chargeduserid,suscribername, date, time, callduration, diallednumber,
            communicationtype, nombreSede, nombreDepar FROM tickets_outgoing_transfer WHERE
            " . $sql3 . ")";
//echo $sql;
 $result = mysqli_query($conexion,$sql) or die("database error:". mysqli_error($conexion));

 $pdf = new PDF();
 //header
 $pdf->AddPage();
 //foter page
 $pdf->AliasNbPages();
 $pdf->SetFont('Arial','B',9);
 foreach($display_heading as $heading) {
   $pdf->Cell(21.5,9,$heading,1);
 }
 foreach($result as $row) {
 $pdf->SetFont('Arial','',8);
 $pdf->Ln();
 foreach($row as $column)
 $pdf->Cell(21.5,9,$column,1);
 }
 $pdf->Output();

} else if ($fecha && $fecha1 && $sede1 && $depar1 ){ //CINCO

$sql3="";
$sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND nombreSede= '$sede1' AND nombreDepar='$depar1'";
$display_heading = array('chargeduserid'=>'Interno', 'suscribername'=> 'Nombre', 'date'=> 'Fecha', 'time'=> 'Hora','callduration'=> 'Duracion', 'diallednumber'=>'Destino', 'communicationtype'=>'Tipo', 'nombreSede'=>'Sede', 'nombreDepar'=>'Departamento');
$sql="(SELECT chargeduserid, suscribername, date, time, callduration, diallednumber,
            communicationtype, nombreSede, nombreDepar FROM tickets_outgoing WHERE
            " . $sql3 . ")
               UNION
     (SELECT chargeduserid,suscribername, date, time, callduration, diallednumber,
            communicationtype, nombreSede, nombreDepar FROM tickets_outgoing_transfer WHERE
            " . $sql3 . ")";
//echo $sql;
 $result = mysqli_query($conexion,$sql) or die("database error:". mysqli_error($conexion));

 $pdf = new PDF();
 //header
 $pdf->AddPage();
 //foter page
 $pdf->AliasNbPages();
 $pdf->SetFont('Arial','B',9);
 foreach($display_heading as $heading) {
   $pdf->Cell(21.5,9,$heading,1);
 }
 foreach($result as $row) {
 $pdf->SetFont('Arial','',8);
 $pdf->Ln();
 foreach($row as $column)
 $pdf->Cell(21.5,9,$column,1);
 }
 $pdf->Output();

} else if ($option && $fecha && $fecha1){ //SEIS

$sql3="";
$sql3 .="chargeduserid=".$option." AND date BETWEEN '$fecha' AND '$fecha1'";
$display_heading = array('chargeduserid'=>'Interno', 'suscribername'=> 'Nombre', 'date'=> 'Fecha', 'time'=> 'Hora','callduration'=> 'Duracion', 'diallednumber'=>'Destino', 'communicationtype'=>'Tipo', 'nombreSede'=>'Sede', 'nombreDepar'=>'Departamento');
$sql="(SELECT chargeduserid, suscribername, date, time, callduration, diallednumber,
            communicationtype, nombreSede, nombreDepar FROM tickets_outgoing WHERE
            " . $sql3 . ")
               UNION
     (SELECT chargeduserid,suscribername, date, time, callduration, diallednumber,
            communicationtype, nombreSede, nombreDepar FROM tickets_outgoing_transfer WHERE
            " . $sql3 . ")";
//echo $sql;
 $result = mysqli_query($conexion,$sql) or die("database error:". mysqli_error($conexion));

  $pdf = new PDF();
  //header
  $pdf->AddPage();
  //foter page
  $pdf->AliasNbPages();
  $pdf->SetFont('Arial','B',9);
  foreach($display_heading as $heading) {
    $pdf->Cell(21.5,9,$heading,1);
  }
  foreach($result as $row) {
  $pdf->SetFont('Arial','',8);
  $pdf->Ln();
  foreach($row as $column)
  $pdf->Cell(21.5,9,$column,1);
  }
  $pdf->Output();

} else if ($option && $sede1){ //SIETE

$sql3="";
$sql3 .="chargeduserid=".$option." AND nombreSede= '$sede1'";
$display_heading = array('chargeduserid'=>'Interno', 'suscribername'=> 'Nombre', 'date'=> 'Fecha', 'time'=> 'Hora','callduration'=> 'Duracion', 'diallednumber'=>'Destino', 'communicationtype'=>'Tipo', 'nombreSede'=>'Sede', 'nombreDepar'=>'Departamento');
$sql="(SELECT chargeduserid, suscribername, date, time, callduration, diallednumber,
            communicationtype, nombreSede, nombreDepar FROM tickets_outgoing WHERE
            " . $sql3 . ")
               UNION
     (SELECT chargeduserid,suscribername, date, time, callduration, diallednumber,
            communicationtype, nombreSede, nombreDepar FROM tickets_outgoing_transfer WHERE
            " . $sql3 . ")";
//echo $sql;
 $result = mysqli_query($conexion,$sql) or die("database error:". mysqli_error($conexion));

  $pdf = new PDF();
  //header
  $pdf->AddPage();
  //foter page
  $pdf->AliasNbPages();
  $pdf->SetFont('Arial','B',9);
  foreach($display_heading as $heading) {
    $pdf->Cell(21.5,9,$heading,1);
  }
  foreach($result as $row) {
  $pdf->SetFont('Arial','',8);
  $pdf->Ln();
  foreach($row as $column)
  $pdf->Cell(21.5,9,$column,1);
  }
  $pdf->Output();

} else if ($option && $depar1){ //OCHO

$sql3="";
$sql3 .="chargeduserid=".$option." AND nombreDepar= '$depar1'";
$display_heading = array('chargeduserid'=>'Interno', 'suscribername'=> 'Nombre', 'date'=> 'Fecha', 'time'=> 'Hora','callduration'=> 'Duracion', 'diallednumber'=>'Destino', 'communicationtype'=>'Tipo', 'nombreSede'=>'Sede', 'nombreDepar'=>'Departamento');
$sql="(SELECT chargeduserid, suscribername, date, time, callduration, diallednumber,
            communicationtype, nombreSede, nombreDepar FROM tickets_outgoing WHERE
            " . $sql3 . ")
               UNION
     (SELECT chargeduserid,suscribername, date, time, callduration, diallednumber,
            communicationtype, nombreSede, nombreDepar FROM tickets_outgoing_transfer WHERE
            " . $sql3 . ")";
//echo $sql;
 $result = mysqli_query($conexion,$sql) or die("database error:". mysqli_error($conexion));

  $pdf = new PDF();
  //header
  $pdf->AddPage();
  //foter page
  $pdf->AliasNbPages();
  $pdf->SetFont('Arial','B',9);
  foreach($display_heading as $heading) {
    $pdf->Cell(21.5,9,$heading,1);
  }
  foreach($result as $row) {
  $pdf->SetFont('Arial','',8);
  $pdf->Ln();
  foreach($row as $column)
  $pdf->Cell(21.5,9,$column,1);
  }
  $pdf->Output();

} else if ($fecha && $fecha1 && $sede1){ //NUEVE

$sql3="";
$sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND nombreSede= '$sede1'";
$display_heading = array('chargeduserid'=>'Interno', 'suscribername'=> 'Nombre', 'date'=> 'Fecha', 'time'=> 'Hora','callduration'=> 'Duracion', 'diallednumber'=>'Destino', 'communicationtype'=>'Tipo', 'nombreSede'=>'Sede', 'nombreDepar'=>'Departamento');
$sql="(SELECT chargeduserid, suscribername, date, time, callduration, diallednumber,
            communicationtype, nombreSede, nombreDepar FROM tickets_outgoing WHERE
            " . $sql3 . ")
               UNION
     (SELECT chargeduserid,suscribername, date, time, callduration, diallednumber,
            communicationtype, nombreSede, nombreDepar FROM tickets_outgoing_transfer WHERE
            " . $sql3 . ")";
//echo $sql;
 $result = mysqli_query($conexion,$sql) or die("database error:". mysqli_error($conexion));

  $pdf = new PDF();
  //header
  $pdf->AddPage();
  //foter page
  $pdf->AliasNbPages();
  $pdf->SetFont('Arial','B',9);
  foreach($display_heading as $heading) {
    $pdf->Cell(21.5,9,$heading,1);
  }
  foreach($result as $row) {
  $pdf->SetFont('Arial','',8);
  $pdf->Ln();
  foreach($row as $column)
  $pdf->Cell(21.5,9,$column,1);
  }
  $pdf->Output();

} else if ($fecha && $fecha1 && $depar1){ //DIEZ

$sql3="";
$sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND nombreDepar= '$depar1'";
$display_heading = array('chargeduserid'=>'Interno', 'suscribername'=> 'Nombre', 'date'=> 'Fecha', 'time'=> 'Hora','callduration'=> 'Duracion', 'diallednumber'=>'Destino', 'communicationtype'=>'Tipo', 'nombreSede'=>'Sede', 'nombreDepar'=>'Departamento');
$sql="(SELECT chargeduserid, suscribername, date, time, callduration, diallednumber,
            communicationtype, nombreSede, nombreDepar FROM tickets_outgoing WHERE
            " . $sql3 . ")
               UNION
     (SELECT chargeduserid,suscribername, date, time, callduration, diallednumber,
            communicationtype, nombreSede, nombreDepar FROM tickets_outgoing_transfer WHERE
            " . $sql3 . ")";
//echo $sql;
 $result = mysqli_query($conexion,$sql) or die("database error:". mysqli_error($conexion));

  $pdf = new PDF();
  //header
  $pdf->AddPage();
  //foter page
  $pdf->AliasNbPages();
  $pdf->SetFont('Arial','B',9);
  foreach($display_heading as $heading) {
    $pdf->Cell(21.5,9,$heading,1);
  }
  foreach($result as $row) {
  $pdf->SetFont('Arial','',8);
  $pdf->Ln();
  foreach($row as $column)
  $pdf->Cell(21.5,9,$column,1);
  }
  $pdf->Output();

} else if ($sede1 && $depar1 ){ //ONCE

$sql3="";
$sql3 .="nombreSede= '$sede1' AND nombreDepar= '$depar1'";
$display_heading = array('chargeduserid'=>'Interno', 'suscribername'=> 'Nombre', 'date'=> 'Fecha', 'time'=> 'Hora','callduration'=> 'Duracion', 'diallednumber'=>'Destino', 'communicationtype'=>'Tipo', 'nombreSede'=>'Sede', 'nombreDepar'=>'Departamento');
$sql="(SELECT chargeduserid, suscribername, date, time, callduration, diallednumber,
            communicationtype, nombreSede, nombreDepar FROM tickets_outgoing WHERE
            " . $sql3 . ")
               UNION
     (SELECT chargeduserid,suscribername, date, time, callduration, diallednumber,
            communicationtype, nombreSede, nombreDepar FROM tickets_outgoing_transfer WHERE
            " . $sql3 . ")";
//echo $sql;
 $result = mysqli_query($conexion,$sql) or die("database error:". mysqli_error($conexion));

  $pdf = new PDF();
  //header
  $pdf->AddPage();
  //foter page
  $pdf->AliasNbPages();
  $pdf->SetFont('Arial','B',9);
  foreach($display_heading as $heading) {
    $pdf->Cell(21.5,9,$heading,1);
  }
  foreach($result as $row) {
  $pdf->SetFont('Arial','',8);
  $pdf->Ln();
  foreach($row as $column)
  $pdf->Cell(21.5,9,$column,1);
  }
  $pdf->Output();

} else if ($option){ //DOCE

$sql3="";
$sql3 .="chargeduserid=".$option."";
$display_heading = array('chargeduserid'=>'Interno', 'suscribername'=> 'Nombre', 'date'=> 'Fecha', 'time'=> 'Hora','callduration'=> 'Duracion', 'diallednumber'=>'Destino', 'communicationtype'=>'Tipo', 'nombreSede'=>'Sede', 'nombreDepar'=>'Departamento');
$sql="(SELECT chargeduserid, suscribername, date, time, callduration, diallednumber,
            communicationtype, nombreSede, nombreDepar FROM tickets_outgoing WHERE
            " . $sql3 . ")
               UNION
     (SELECT chargeduserid,suscribername, date, time, callduration, diallednumber,
            communicationtype, nombreSede, nombreDepar FROM tickets_outgoing_transfer WHERE
            " . $sql3 . ")";
//echo $sql;
 $result = mysqli_query($conexion,$sql) or die("database error:". mysqli_error($conexion));

  $pdf = new PDF();
  //header
  $pdf->AddPage();
  //foter page
  $pdf->AliasNbPages();
  $pdf->SetFont('Arial','B',9);
  foreach($display_heading as $heading) {
    $pdf->Cell(21.5,9,$heading,1);
  }
  foreach($result as $row) {
  $pdf->SetFont('Arial','',8);
  $pdf->Ln();
  foreach($row as $column)
  $pdf->Cell(21.5,9,$column,1);
  }
  $pdf->Output();

} else if ($fecha && $fecha1){ //TRECE

$sql3="";
$sql3 .="date BETWEEN '$fecha' AND '$fecha1'";
$display_heading = array('chargeduserid'=>'Interno', 'suscribername'=> 'Nombre', 'date'=> 'Fecha', 'time'=> 'Hora','callduration'=> 'Duracion', 'diallednumber'=>'Destino', 'communicationtype'=>'Tipo', 'nombreSede'=>'Sede', 'nombreDepar'=>'Departamento');
$sql="(SELECT chargeduserid, suscribername, date, time, callduration, diallednumber,
            communicationtype, nombreSede, nombreDepar FROM tickets_outgoing WHERE
            " . $sql3 . ")
               UNION
     (SELECT chargeduserid,suscribername, date, time, callduration, diallednumber,
            communicationtype, nombreSede, nombreDepar FROM tickets_outgoing_transfer WHERE
            " . $sql3 . ")";
//echo $sql;
 $result = mysqli_query($conexion,$sql) or die("database error:". mysqli_error($conexion));

  $pdf = new PDF();
  //header
  $pdf->AddPage();
  //foter page
  $pdf->AliasNbPages();
  $pdf->SetFont('Arial','B',9);
  foreach($display_heading as $heading) {
    $pdf->Cell(21.5,9,$heading,1);
  }
  foreach($result as $row) {
  $pdf->SetFont('Arial','',8);
  $pdf->Ln();
  foreach($row as $column)
  $pdf->Cell(21.5,9,$column,1);
  }
  $pdf->Output();

} else if ($sede1){ //CATORCE

$sql3="";
$sql3 .="nombreSede= '$sede1'";
$display_heading = array('chargeduserid'=>'Interno', 'suscribername'=> 'Nombre', 'date'=> 'Fecha', 'time'=> 'Hora','callduration'=> 'Duracion', 'diallednumber'=>'Destino', 'communicationtype'=>'Tipo', 'nombreSede'=>'Sede', 'nombreDepar'=>'Departamento');
$sql="(SELECT chargeduserid, suscribername, date, time, callduration, diallednumber,
            communicationtype, nombreSede, nombreDepar FROM tickets_outgoing WHERE
            " . $sql3 . ")
               UNION
     (SELECT chargeduserid,suscribername, date, time, callduration, diallednumber,
            communicationtype, nombreSede, nombreDepar FROM tickets_outgoing_transfer WHERE
            " . $sql3 . ")";
//echo $sql;
 $result = mysqli_query($conexion,$sql) or die("database error:". mysqli_error($conexion));

  $pdf = new PDF();
  //header
  $pdf->AddPage();
  //foter page
  $pdf->AliasNbPages();
  $pdf->SetFont('Arial','B',9);
  foreach($display_heading as $heading) {
    $pdf->Cell(21.5,9,$heading,1);
  }
  foreach($result as $row) {
  $pdf->SetFont('Arial','',8);
  $pdf->Ln();
  foreach($row as $column)
  $pdf->Cell(21.5,9,$column,1);
  }
  $pdf->Output();

} else if ($depar1 ){ //QUINCE

$sql3="";
$sql3 .="nombreDepar= '$depar1'";
$display_heading = array('chargeduserid'=>'Interno', 'suscribername'=> 'Nombre', 'date'=> 'Fecha', 'time'=> 'Hora','callduration'=> 'Duracion', 'diallednumber'=>'Destino', 'communicationtype'=>'Tipo', 'nombreSede'=>'Sede', 'nombreDepar'=>'Departamento');
$sql="(SELECT chargeduserid, suscribername, date, time, callduration, diallednumber,
            communicationtype, nombreSede, nombreDepar FROM tickets_outgoing WHERE
            " . $sql3 . ")
               UNION
     (SELECT chargeduserid,suscribername, date, time, callduration, diallednumber,
            communicationtype, nombreSede, nombreDepar FROM tickets_outgoing_transfer WHERE
            " . $sql3 . ")";
//echo $sql;
 $result = mysqli_query($conexion,$sql) or die("database error:". mysqli_error($conexion));

  $pdf = new PDF();
  //header
  $pdf->AddPage();
  //foter page
  $pdf->AliasNbPages();
  $pdf->SetFont('Arial','B',9);
  foreach($display_heading as $heading) {
    $pdf->Cell(21.5,9,$heading,1);
  }
  foreach($result as $row) {
  $pdf->SetFont('Arial','',8);
  $pdf->Ln();
  foreach($row as $column)
  $pdf->Cell(21.5,9,$column,1);
  }
  $pdf->Output();
}
?>
