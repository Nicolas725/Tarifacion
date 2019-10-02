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
   $this->Image('https://i2.wp.com/tutorialswebsite.com/wp-content/uploads/2016/01/cropped-LOGO-1.png',10,10,50);
   $this->SetFont('Arial','B',13);
   // Move to the right
   $this->Cell(80);
   // Title
   $this->Cell(80,10,'Llamadas entrantes',1,0,'C');
   // Line break
   $this->Ln(20);
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

$sql3="";
$sql2="";
$sql3 .="WHERE chargeduserid=".$option."";
$sql2 .="date BETWEEN '$fecha' AND '$fecha1'";

//$display_heading = array('chargeduserid'=>'Interno', 'suscribername'=> 'Nombre', 'date'=> 'Fecha', 'time'=> 'Hora','callduration'=> 'Duracion', 'diallednumber'=>'Destino', 'communicationtype'=>'Tipo','id'=>'ID','chargedusertype'=>'Usertype','ringingduration'=>'Ring','initialuserid'=>'Initial','trunkid'=>'trunk' );
$display_heading = array('chargeduserid'=>'Interno', 'suscribername'=> 'Nombre', 'date'=> 'Fecha', 'time'=> 'Hora','callduration'=> 'Duracion', 'diallednumber'=>'Destino', 'communicationtype'=>'Tipo');

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

$result = mysqli_query($conexion,$sql) or die("database error:". mysqli_error($conexion));
//$header = mysqli_query($conexion, "SHOW columns FROM tickets_incoming WHERE field != 'created_on'");
//$header = mysqli_query($conexion, "SHOW columns FROM tickets_incoming");

$pdf = new PDF();
//header
$pdf->AddPage();
//foter page
$pdf->AliasNbPages();
$pdf->SetFont('Arial','B',14);
foreach($display_heading as $heading) {
	$pdf->Cell(29,10,$heading,1);
}
foreach($result as $row) {
$pdf->SetFont('Arial','',9);
$pdf->Ln();
foreach($row as $column)
$pdf->Cell(29,10,$column,1);
}
$pdf->Output();
?>
