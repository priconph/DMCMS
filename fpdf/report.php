<?php
if(!isset($_GET['id0'])){
	echo  "<script type='text/javascript'>";
	echo "this.window.close();";
	echo "alert('Choose records to view!');";
	echo "</script>";
}
else{
$max = $_GET['max'];
$array = array($max);
for($x=0;$x<$max;$x++){
	$array[$x] = $_GET['id'.$x];
	// echo "<p id='id_paragraph' style='position:absolute;' hidden></p>";
}

require('multicellmax.php');

$pdf=new PDF();
$pdf->AddPage();
$txt='';
for ($i=1; $i<19; $i++)
	$txt.='all work and no play makes jack a dull boy ';
$pdf->Rect(20, 67, 30, 10, 'D');
$pdf->Rect(50, 67, 25, 10, 'D');
$pdf->Rect(75, 67, 27, 10, 'D');
$pdf->Rect(102, 67, 25, 10, 'D');
$pdf->Rect(127, 67, 25, 10, 'D');
$pdf->Rect(152, 67, 36, 10, 'D');

$pdf->Rect(20, 77, 30, 8, 'D');
$pdf->Rect(50, 77, 25, 8, 'D');
$pdf->Rect(75, 77, 27, 8, 'D');
$pdf->Rect(102, 77, 25, 8, 'D');
$pdf->Rect(127, 77, 25, 8, 'D');
$pdf->Rect(152, 77, 36, 8, 'D');

$pdf->Rect(20, 85, 30, 8, 'D');
$pdf->Rect(50, 85, 25, 8, 'D');
$pdf->Rect(75, 85, 27, 8, 'D');
$pdf->Rect(102, 85, 25, 8, 'D');
$pdf->Rect(127, 85, 25, 8, 'D');
$pdf->Rect(152, 85, 36, 8, 'D');

$pdf->Rect(20, 93, 30, 8, 'D');
$pdf->Rect(50, 93, 25, 8, 'D');
$pdf->Rect(75, 93, 27, 8, 'D');
$pdf->Rect(102, 93, 25, 8, 'D');
$pdf->Rect(127, 93, 25, 8, 'D');
$pdf->Rect(152, 93, 36, 8, 'D');

$pdf->Rect(20, 101, 30, 8, 'D');
$pdf->Rect(50, 101, 25, 8, 'D');
$pdf->Rect(75, 101, 27, 8, 'D');
$pdf->Rect(102, 101, 25, 8, 'D');
$pdf->Rect(127, 101, 25, 8, 'D');
$pdf->Rect(152, 101, 36, 8, 'D');

$pdf->Rect(20, 109, 30, 8, 'D');
$pdf->Rect(50, 109, 25, 8, 'D');
$pdf->Rect(75, 109, 27, 8, 'D');
$pdf->Rect(102, 109, 25, 8, 'D');
$pdf->Rect(127, 109, 25, 8, 'D');
$pdf->Rect(152, 109, 36, 8, 'D');

$pdf->Rect(20, 117, 30, 8, 'D');
$pdf->Rect(50, 117, 25, 8, 'D');
$pdf->Rect(75, 117, 27, 8, 'D');
$pdf->Rect(102, 117, 25, 8, 'D');
$pdf->Rect(127, 117, 25, 8, 'D');
$pdf->Rect(152, 117, 36, 8, 'D');

$pdf->Rect(20, 125, 30, 8, 'D');
$pdf->Rect(50, 125, 25, 8, 'D');
$pdf->Rect(75, 125, 27, 8, 'D');
$pdf->Rect(102, 125, 25, 8, 'D');
$pdf->Rect(127, 125, 25, 8, 'D');
$pdf->Rect(152, 125, 36, 8, 'D');

$pdf->Rect(20, 133, 30, 8, 'D');
$pdf->Rect(50, 133, 25, 8, 'D');
$pdf->Rect(75, 133, 27, 8, 'D');
$pdf->Rect(102, 133, 25, 8, 'D');
$pdf->Rect(127, 133, 25, 8, 'D');
$pdf->Rect(152, 133, 36, 8, 'D');

$pdf->Rect(20, 141, 30, 8, 'D');
$pdf->Rect(50, 141, 25, 8, 'D');
$pdf->Rect(75, 141, 27, 8, 'D');
$pdf->Rect(102, 141, 25, 8, 'D');
$pdf->Rect(127, 141, 25, 8, 'D');
$pdf->Rect(152, 141, 36, 8, 'D');

$pdf->Rect(20, 149, 30, 8, 'D');
$pdf->Rect(50, 149, 25, 8, 'D');
$pdf->Rect(75, 149, 27, 8, 'D');
$pdf->Rect(102, 149, 25, 8, 'D');
$pdf->Rect(127, 149, 25, 8, 'D');
$pdf->Rect(152, 149, 36, 8, 'D');

$pdf->Rect(20, 157, 30, 8, 'D');
$pdf->Rect(50, 157, 25, 8, 'D');
$pdf->Rect(75, 157, 27, 8, 'D');
$pdf->Rect(102, 157, 25, 8, 'D');
$pdf->Rect(127, 157, 25, 8, 'D');
$pdf->Rect(152, 157, 36, 8, 'D');

$pdf->Rect(20, 165, 30, 8, 'D');
$pdf->Rect(50, 165, 25, 8, 'D');
$pdf->Rect(75, 165, 27, 8, 'D');
$pdf->Rect(102, 165, 25, 8, 'D');
$pdf->Rect(127, 165, 25, 8, 'D');
$pdf->Rect(152, 165, 36, 8, 'D');

$pdf->Rect(20, 173, 30, 8, 'D');
$pdf->Rect(50, 173, 25, 8, 'D');
$pdf->Rect(75, 173, 27, 8, 'D');
$pdf->Rect(102, 173, 25, 8, 'D');
$pdf->Rect(127, 173, 25, 8, 'D');
$pdf->Rect(152, 173, 36, 8, 'D');

$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,28,'                                        MOLD MAINTENANCE CYCLE CHECK SHEET');
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,40,'                                        MOLD MAINTENANCE CYCLE CHECK SHEET');
$txt=$pdf->MultiCell(20,4,$txt,0,'C',0,50);
$pdf->Output();

// 
}?>

