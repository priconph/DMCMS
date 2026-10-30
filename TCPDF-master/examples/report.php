<?php
if(!isset($_GET['id0'])){
	echo  "<script type='text/javascript'>";
	echo "this.window.close();";
	echo "alert('Choose records to view!');";
	echo "</script>";
}
else{
$max			 	= $_GET['max'];
$dc_id_identifier 	= array($max);
for($s=0;$s<$max;$s++){
	$dc_id_identifier[$s] = $_GET["id$s"];
}

// for($x=0;$x<$max;$x++){
// 	$array[$x] = $_GET['id'.$x];
	// echo "<p id='id_paragraph' style='position:absolute;' hidden></p>";
// Include the main TCPDF library (search for installation path).
require_once('../TCPDF-master/examples/tcpdf_include.php');
// require_once('./fpdf/class/printing.php');

// create new PDF document
require_once('query.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Pocholo Manaig');
$pdf->SetTitle('Report View');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

$pdf->SetHeaderData('','','','');

// remove default header/footer
$pdf->setPrintHeader(true);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
// $pdf->SetMargins(10, PDF_MARGIN_TOP, 10);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'../TCPDF-master/examples/lang/eng.php')) {
	require_once(dirname(__FILE__).'../TCPDF-master/examples/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

$pdf->SetDisplayMode('fullpage', 'SinglePage', 'UseNone');
		$return 		= $_POST;
		$result 		= "";
		$array_fields	= array('*');
		$table 	   		= 't_dieset_cycle';
		$joins 	   		= '';
		$sql_where 		= "WHERE dc_idd='$dc_id_identifier[0]'";
		$sql_order 		= '';
		$sql_limit 		= '';
		$result = Training::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		while($row = mysqli_fetch_array($result)){
			$dc_d_id 	= $row['dc_d_id'];
			$startDate 	= $row['dc_start_date'];
			$endDate	= $row['dc_end_date'];
		}
		$return['result'] = json_encode($result);
		echo json_encode($return);

		$return 		= $_POST;
		$result 		= "";
		$array_fields	= array('*');
		$table 	   		= 't_device';
		$joins 	   		= '';
		$sql_where 		= "WHERE d_id='$dc_d_id'";
		$sql_order 		= '';
		$sql_limit 		= '';
		$result = Training::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		while($row = mysqli_fetch_array($result)){
			$d_name					= $row['d_name'];
			$d_cavty				= $row['d_cavty'];
			$d_drawing				= $row['d_drawing'];
			$d_maintenance_cycle	= $row['d_maintenance_cycle'];
		}
		$return['result'] = json_encode($result);
		echo json_encode($return);

$tablerepo 	= '<table style="padding:5px;width:94%;">
				<thead>
					<tr>
						<td style="height:30px;border: 1px solid black;text-align:center;"><b>DATE</b></td>
						<td style="border: 1px solid black;text-align:center;"><b>SHOT</b></td>
						<td style="width:105px;border: 1px solid black;text-align:center;"><b> SHOT ACCUM.</b></td>
						<td style="width:79px;border: 1px solid black;text-align:center;"><b>MACH NO.</b></td>
						<td style="border: 1px solid black;text-align:center;"><b>OPERATOR</b></td>
						<td style="width:115px;border: 1px solid black;text-align:center;"><b>REMARKS</b></td>
					</tr>
				</thead>
				<tbody>';
		$return 			= $_POST;
		$result				= "";
		$array_fields 		= array('*');
		$table 	   			= 't_shots';
		$joins 	   			= '';
		$sql_where 			= "WHERE s_d_id='$dc_d_id' AND s_date BETWEEN '$startDate' AND '$endDate'";
		$sql_order 			= 'ORDER BY s_date';
		$sql_limit 			= '';
		$result 			= Training::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr 				= 0;
		$accumulated_shots 	= 0;
		while($row = mysqli_fetch_array($result)){
			$accumulated_shots = $accumulated_shots+$row['s_count'];
			$tablerepo .='<tr nobr="true">
						<td style="border: 1px solid black;text-align:center;">'.date("M d, Y", strtotime($row['s_date'])).'</td>
						<td style="border: 1px solid black;text-align:center;">'.$row['s_count'].'</td>
						<td style="width:105px;border: 1px solid black;text-align:center;">'.$accumulated_shots.'</td>
						<td style="width:79px;border: 1px solid black;text-align:center;">'.$row['s_machine_id'].'</td>
						<td style="border: 1px solid black;text-align:center;">Juan Dela Cruz</td>
						<td style="width:115px;border: 1px solid black;text-align:center;">'.$row['s_remarks'].'</td>
					</tr>';
			$ctr++;
			if($ctr==26){break;}
		}
		$return['ctr']	  = $ctr;
		$return['result'] = json_encode($result);
		echo json_encode($return);

		$tablerepo .= '</tbody></table>';

$etct	= 'PPS-7000-036';
$etcb	= 'TSD-MOLD-P-1208-011-1';		
$title 	= 'MOLD MAINTENANCE CYCLE CHECK SHEET';
$device = '<div>DEVICE NAME</div>';
$dval 	= $d_name;
$mainte = '<div>MAINTENANCE CYCLE</div>'; 
$mval	= $d_maintenance_cycle;
$cavty 	= '<div>CAVTY QTY.</div>';
$cval	= $d_cavty;
$draw 	= '<div>DRAWING NO.</div>'; 
$drval	= $d_drawing;
// set font
$font = "times";
// $font = $this->addTTFfont("./css/fonts/arial.ttf");
$pdf->SetFont($font, 'B', 12);
$pdf->AddPage('P', 'A4');
$pdf->writeHTMLCell(0, '', '', '24', $title, 0, 0, 0, true, 'C', true);
$pdf->SetFont($font, 'B', 10);
$pdf->writeHTMLCell(28, '', '19', '42', $device, 0, 0, 0, true, ' ', false);
$pdf->writeHTMLCell(43, '', '19', '55', $mainte, 0, 0, 0, true, ' ', true);
$pdf->writeHTMLCell(28, '', '118', '42', $cavty, 0, 0, 0, true, ' ', true);
$pdf->writeHTMLCell(28, '', '118', '55', $draw, 0, 0, 0, true, ' ', true);
$pdf->SetFont($font, ' ', 10);
$pdf->writeHTMLCell(28, '', '20', '48', $dval, 0, 0, 0, true, ' ', false);
$pdf->writeHTMLCell(43, '', '20', '61', $mval, 0, 0, 0, true, ' ', true);
$pdf->writeHTMLCell(28, '', '119', '48', $cval, 0, 0, 0, true, ' ', true);
$pdf->writeHTMLCell(28, '', '119', '61', $drval, 0, 0, 0, true, ' ', true);
$pdf->SetFont($font, ' ', 8);
$pdf->writeHTMLCell(28, '', '172', '22', $etct, 0, 0, 0, true, ' ', true);
$pdf->Line(20, 52, 91, 52);
$pdf->Line(20, 65, 91, 65);
$pdf->Line(119, 52, 188, 52);
$pdf->Line(119, 65, 188, 65);
$pdf->SetFont($font, ' ', 10);
$pdf->writeHTMLCell(0, '', '19', '68', $tablerepo, 0, 0, 0, true, 'C', true);
$pdf->lastPage();
$pdf->SetFont($font, ' ', 8);
$pdf->writeHTMLCell(48, '', '155', '268', $etcb, 0, 0, 0, true, ' ', true);
// ---------------------------------------------------------
ob_end_clean();
//Close and output PDF document
$pdf->Output('qwe.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
}?>