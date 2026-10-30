<?php
require('multicellmax.php');

$pdf=new PDF();
$pdf->AddPage();
$txt='';
for ($i=1; $i<19; $i++)
	$txt.='all work and no play makes jack a dull boy ';
$pdf->Rect(20,20,20,100);
$pdf->SetXY(20,20);
$pdf->SetFont('Arial','',10);
$txt=$pdf->MultiCell(20,4,$txt,0,'C',0,50);
$pdf->Output();
?>
