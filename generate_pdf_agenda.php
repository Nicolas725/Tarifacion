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

    $this->Cell(80,10,'REPORTE',1,0,'C');
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


$tarifa = isset($_POST['tarifa']) ? $_POST['tarifa'] : false;
$fecha = isset($_POST['dateFrom']) ? $_POST['dateFrom'] : false;
$fecha1 = isset($_POST['dateTo']) ? $_POST['dateTo'] : false;
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
$h=0;
$n=0;
$t=0;
$l=0;
$y=0;


$display_heading = array('chargeduserid'=>'Interno', 'suscribername'=> 'Nombre', 'date'=> 'Fecha', 'time'=> 'Hora', 'diallednumber'=>'Destino', 'communicationtype'=>'Tipo', 'nombreSede'=>'Sede', 'nombreDepar'=>'Departamento','callduration'=> 'Duracion','Costo');

$pdf = new PDF('L');
//header
$pdf->AddPage();
//foter page
$pdf->AliasNbPages();
$pdf->SetFont('Arial','B',12);
$pdf->Cell(28,9,'',1);
$pdf->Cell(28,9,'Internacional',1);
$pdf->Cell(28,9,'Nacional',1);
$pdf->Cell(28,9,'Local',1);
$pdf->Cell(28,9,'Celular',1);
$pdf->Ln();
$Tinter="$".$Tinter;
$Tnacion="$".$Tnacion;
$Tlocal="$".$Tlocal;
$Tcel="$".$Tcel;

$pdf->Cell(28,9,'Precio Min',1);
$pdf->SetFont('Arial','',10);
$pdf->Cell(28,9,$Tinter,1);
$pdf->Cell(28,9,$Tnacion,1);
$pdf->Cell(28,9,$Tlocal,1);
$pdf->Cell(28,9,$Tcel,1);
$pdf->Ln();

$pdf->SetFont('Arial','B',12);
$pdf->Cell(28,9,'Total',1);
$pdf->SetFont('Arial','',10);
$pdf->Cell(28,9,$internacional,1);
$pdf->Cell(28,9,$nacional,1);
$pdf->Cell(28,9,$local,1);
$pdf->Cell(28,9,$celular,1);
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();


$pdf->SetFont('Arial','B',10);
foreach($display_heading as $heading) {
  $pdf->Cell(28,9,$heading,1);
}

while ($n<33) {

  $sql3="";
  $sql3 .="date BETWEEN '$fecha' AND '$fecha1' AND diallednumber LIKE '$Prefijo[$n]%'";
  $sql="(SELECT chargeduserid, suscribername, date, time, diallednumber,
  communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing WHERE
  " . $sql3 . ")
  UNION ALL
  (SELECT chargeduserid,suscribername, date, time, diallednumber,
  communicationtype, nombreSede, nombreDepar, callduration FROM tickets_outgoing_transfer WHERE
  " . $sql3 . ")";
  //echo $sql;
  $result = mysqli_query($conexion,$sql) or die("database error:". mysqli_error($conexion));

  foreach($result as $row) {
    $pdf->SetFont('Arial','',10);
    $pdf->Ln();
    foreach($row as $column)
    $pdf->Cell(28,9,$column,1);
    $pdf->Cell(28,9,$individual[$h],1);
    $h++;
  }
  $n++;
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
$pdf->Cell(28,9,$tarifa,1);

$pdf->Output();


?>
