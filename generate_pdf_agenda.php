<?php
//include conexionection file
include("./conexion.php");
require ("fpdf/fpdf.php");


class PDF extends FPDF
{
// Page header
function Header()
{
   // Logo
   $this->Image('C:\xampp\htdocs\www\TESIS\Tarifacion\imagenes\UM_logo.jpg',10,10,50);

      $this->SetFont('Arial','B',13);
      // Move to the right
      $this->Cell(100);
      // Title
      //$this->Cell(-15);

      $this->Cell(80,10,'PRECIO',1,0,'C');
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


   $valor2 = isset($_POST['valor2']) ? $_POST['valor2'] : false;
   $fecha = isset($_POST['dateFrom']) ? $_POST['dateFrom'] : false;
   $fecha1 = isset($_POST['dateTo']) ? $_POST['dateTo'] : false;
   $timeFormat = isset($_POST['timeFormat']) ? $_POST['timeFormat'] : false;

   $sql3="";
   $sql3 .="date BETWEEN '$fecha' AND '$fecha1'";
   $display_heading = array('chargeduserid'=>'Interno', 'suscribername'=> 'Nombre', 'date'=> 'Fecha', 'time'=> 'Hora', 'diallednumber'=>'Destino', 'communicationtype'=>'Tipo', 'nombreSede'=>'Sede', 'nombreDepar'=>'Departamento','callduration'=> 'Duracion');
   $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
                communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
                " . $sql3 . ")
                   UNION ALL
         (SELECT chargeduserid,suscribername, date, time, diallednumber,
                communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
                " . $sql3 . ")";
    //echo $sql;
     $result = mysqli_query($conexion,$sql) or die("database error:". mysqli_error($conexion));

     $pdf = new PDF('L');
     //header
     $pdf->AddPage();
     //foter page
     $pdf->AliasNbPages();
     $pdf->SetFont('Arial','B',10);
     foreach($display_heading as $heading) {
       $pdf->Cell(28,9,$heading,1);
     }
     foreach($result as $row) {
     $pdf->SetFont('Arial','',10);
     $pdf->Ln();
     foreach($row as $column)
     $pdf->Cell(28,9,$column,1);
     }

     $pdf->SetFont('Arial','B',12);
     $pdf->Ln();
     $pdf->Cell(28,9,'TOTAL',1);
     $pdf->Cell(28,9,'',1);
     $pdf->Cell(28,9,'',1);
     $pdf->Cell(28,9,'',1);
     $pdf->Cell(28,9,'',1);
     $pdf->Cell(28,9,'',1);
     $pdf->Cell(28,9,'',1);
     $pdf->Cell(28,9,'',1);
     $pdf->Cell(28,9,$timeFormat,1);
     $pdf->Cell(28,9,$valor2,1);


     $pdf->Output();


   ?>
