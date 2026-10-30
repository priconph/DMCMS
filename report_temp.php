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

$default_rows=26;
// for($x=0;$x<$max;$x++){
// 	$array[$x] = $_GET['id'.$x];
	// echo "<p id='id_paragraph' style='position:absolute;' hidden></p>";
// Include the main TCPDF library (search for installation path).
require_once('./TCPDF-master/examples/tcpdf_include.php');
// require_once('./fpdf/class/printing.php');

// create new PDF document
require_once('query.php');
ob_end_clean();

// $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
// $pdf->SetCreator(PDF_CREATOR);
// $pdf->SetAuthor('Pocholo Manaig');
// $pdf->SetTitle('Report View');
// $pdf->SetSubject('TCPDF Tutorial');
// $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

class MYPDF extends TCPDF {
	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(279);
		// Set font
		$this->SetFont('times', ' ', 8);
		// Page number
		$this->Cell(0, 10, 'TSD-MOLD-P-1208-011-1                 ', 0, false, 'R', 0, '', 0, false, 'T', 'M');
	}
}
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, $pageDimension, true, 'UTF-8', false);

$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// remove default header/footer
$pdf->setPrintHeader(false); 
// $pdf->setPrintFooter(false);
// $pdf->Footer(); 
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set margins
// $pdf->SetMargins(10, PDF_MARGIN_TOP, 10);


// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'../TCPDF-master/examples/lang/eng.php')) {
	require_once(dirname(__FILE__).'../TCPDF-master/examples/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

$pdf->SetDisplayMode('fullpage', 'SinglePage', 'UseNone');
$qwerty = 0;
while($qwerty<$max){
		$return 		= $_POST;
		$result 		= "";
		$array_fields	= array('*');
		$table 	   		= 't_dieset_cycle';
		$joins 	   		= '';
		$sql_where 		= "WHERE dc_idd='$dc_id_identifier[$qwerty]'";
		$sql_order 		= '';
		$sql_limit 		= '';
		$result = dmcms::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		while($row = mysqli_fetch_array($result)){
			$dc_id   	= $row['dc_idd'];
			$dc_d_id 	= $row['dc_d_id'];
			$startDate 	= $row['dc_start_date'];
			$endDate	= $row['dc_end_date'];
		}

		$return 		= $_POST;
		$result 		= "";
		$array_fields	= array('*');
		$table 	   		= 't_device';
		$joins 	   		= '';
		$sql_where 		= "WHERE d_id='$dc_d_id'";
		$sql_order 		= '';
		$sql_limit 		= '';
		$result = pps::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		while($row = mysqli_fetch_array($result)){
			$d_name					= $row['d_name'];
			$d_cavty				= $row['d_cavty'];
			$d_drawing				= $row['d_drawing'];
			$d_maintenance_cycle	= $row['d_maintenance'];
		}

		$return 		= $_POST;
		$result 		= "";
		$array_fields	= array('*');
		$table 	   		= 't_change_cycle';
		$joins 	   		= '';
		$sql_where 		= "WHERE `c_dc_id`='$dc_id_identifier[$qwerty]'";
		$sql_order 		= '';
		$sql_limit 		= '';
		$result = dmcms::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr 			= 0;
		while($row = mysqli_fetch_array($result)){
			$c_new_cycle[$ctr]			= $row['c_new_cycle'];
			$c_date[$ctr]				= $row['c_date'];
			$c_reason[$ctr]				= $row['c_reason'];

			$ctr++;
		}
		$change_row_count = $result->num_rows;

$tablerepo 	= '<table style="padding:5px;width:94%;">
				<thead>
					<tr>
						<td style="height:30px;border: 1px solid black;text-align:center;"><b>PRODUCTION DATE</b></td>
						<td style="border: 1px solid black;text-align:center;"><b>SHOT</b></td>
						<td style="width:105px;border: 1px solid black;text-align:center;"><b> SHOT ACCUM.</b></td>
						<td style="width:79px;border: 1px solid black;text-align:center;"><b>MACHINE NO.</b></td>
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
		$sql_where 			= "WHERE s_d_id='$dc_d_id' AND s_dc_id='$dc_id' AND logdel=0";
		$sql_order 			= 'ORDER BY s_id';
		$sql_limit 			= '';
		$result 			= dmcms::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr 				= 0;
		$accumulated_shots 	= 0;
		while($row = mysqli_fetch_array($result)){
			$accumulated_shots 			= $accumulated_shots+$row['s_count'];
			$s_date[$ctr]				= $row['s_date'];
			if($row['s_count']=='0'){
				$s_count[$ctr] 			= " ";
			}
			else{
				$s_count[$ctr]			= $row['s_count'];
			}			
			$s_accumulated_shots[$ctr]	= $accumulated_shots;
			$s_machine_id[$ctr]			= $row['s_machine'];
			$s_remakrs[$ctr]			= $row['s_remarks'];
			$s_operator[$ctr]			= $row['s_operator'];
			
			$ctr++;
		}
		$rowcount=$result->num_rows;
		for($x=0;$x<$default_rows;$x++){
			if($rowcount<=$x){
				$tablerepo .='<tr nobr="true">
						<td style="height:5px;border: 1px solid black;text-align:center;"></td>
						<td style="border: 1px solid black;text-align:center;"></td>
						<td style="width:105px;border: 1px solid black;text-align:center;"></td>
						<td style="width:79px;border: 1px solid black;text-align:center;"></td>
						<td style="border: 1px solid black;text-align:center;"></td>
						<td style="width:115px;border: 1px solid black;text-align:center;"></td>
					</tr>';				
			}
			else if($rowcount==$x+1){
					$tablerepo .='<tr nobr="true">
						<td style="border: 1px solid black;text-align:center;">'.date("M d, Y", strtotime($s_date[$x])).'</td>
						<td style="border: 1px solid black;text-align:left;">'.number_format($s_count[$x]).'</td>
						<td style="width:105px;border: 1px solid black;text-align:left;"><b>'.number_format($s_accumulated_shots[$x]).'</b></td>
						<td style="width:79px;border: 1px solid black;text-align:left;">'.$s_machine_id[$x].'</td>
						<td style="border: 1px solid black;text-align:left;font-size:11px;">'.$s_operator[$x].'</td>
						<td style="font-size:8px;width:115px;border: 1px solid black;text-align:left;">'.$s_remakrs[$x].'</td>
					</tr>';	
			}
			else{
				$tablerepo .='<tr nobr="true">
						<td style="border: 1px solid black;text-align:center;">'.date("M d, Y", strtotime($s_date[$x])).'</td>
						<td style="border: 1px solid black;text-align:left;">'.number_format($s_count[$x]).'</td>
						<td style="width:105px;border: 1px solid black;text-align:left;">'.number_format($s_accumulated_shots[$x]).'</td>
						<td style="width:79px;border: 1px solid black;text-align:left;">'.$s_machine_id[$x].'</td>
						<td style="border: 1px solid black;text-align:left;font-size:11px;">'.$s_operator[$x].'</td>
						<td style="font-size:8px;width:115px;border: 1px solid black;text-align:left;">'.$s_remakrs[$x].'</td>
					</tr>';
			}	
		}
					
		$tablerepo .= '</tbody></table>';

		$etct	= 'PPS-7000-036';
		$etcb	= 'TSD-MOLD-P-1208-011-1';		
		$title 	= 'MOLD MAINTENANCE CYCLE CHECK SHEET';
		$device = '<div>DEVICE NAME</div>';
		$dval 	= $d_name;
		$mainte = '<div>MAINTENANCE CYCLE</div>'; 
		$mval	= $d_maintenance_cycle;
		$mval 	= number_format($mval);
		$cavty 	= '<div>CAVTY QTY.</div>';
		$cval	= $d_cavty;
		$draw 	= '<div>DRAWING NO.</div>'; 
		$drval	= $d_drawing;
		// set font
		$font = "times";
		// $font = $this->addTTFfont("./css/fonts/arial.ttf");

		if($rowcount>=26){
			// set auto page breaks
			$pdf->SetAutoPageBreak(TRUE, 25);
		}
		else{
			// set auto page breaks
			$pdf->SetAutoPageBreak(TRUE, 25);
		}

		$pdf->SetFont($font, 'B', 12);
		$pdf->AddPage('P', 'A4');
		$pdf->writeHTMLCell(0, '', '', '24', $title, 0, 0, 0, true, 'C', true);
		$pdf->SetFont($font, 'B', 10);
		$pdf->writeHTMLCell(28, '', '19', '42', $device, 0, 0, 0, true, ' ', false);
		$pdf->writeHTMLCell(43, '', '19', '55', $mainte, 0, 0, 0, true, ' ', true);
		$pdf->writeHTMLCell(28, '', '118', '42', $cavty, 0, 0, 0, true, ' ', true);
		$pdf->writeHTMLCell(28, '', '118', '55', $draw, 0, 0, 0, true, ' ', true);
		$pdf->SetFont($font, ' ', 10);
		$pdf->writeHTMLCell(70, '', '20', '48', $dval, 0, 0, 0, true, ' ', false);
		$pdf->writeHTMLCell(43, '', '20', '61', '<b>'.$mval.'</b>', 0, 0, 0, true, ' ', true);
		if($change_row_count>0){$pdf->writeHTMLCell(48, '', '43', '61', '*('.number_format($c_new_cycle[$change_row_count-1]).' - '.date("M d, Y", strtotime($c_date[$change_row_count-1])).')', 0, 0, 0, true, ' ', true);}
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

		// Position at 15 mm from bottom
		$pdf->SetY(-15);
		// $pdf->writeHTMLCell(0, '', '155', '268', $etcb, 0, 0, 0, true, ' ', true);

while($rowcount>$default_rows){	
			$tablerepo2	= '<table style="padding:5px;width:94%;">
				<thead>
					<tr>
						<td style="height:30px;border: 1px solid black;text-align:center;"><b>PRODUCTION DATE</b></td>
						<td style="border: 1px solid black;text-align:center;"><b>SHOT</b></td>
						<td style="width:105px;border: 1px solid black;text-align:center;"><b> SHOT ACCUM.</b></td>
						<td style="width:79px;border: 1px solid black;text-align:center;"><b>MACH NO.</b></td>
						<td style="border: 1px solid black;text-align:center;"><b>OPERATOR</b></td>
						<td style="width:115px;border: 1px solid black;text-align:center;"><b>REMARKS</b></td>
					</tr>
				</thead>
				<tbody>';	
		for($x=$default_rows;$x<$default_rows+26;$x++){
			if($rowcount<=$x){
				$tablerepo2 .='<tr nobr="true">
						<td style="border: 1px solid black;text-align:center;"></td>
						<td style="border: 1px solid black;text-align:center;"></td>
						<td style="width:105px;border: 1px solid black;text-align:center;"></td>
						<td style="width:79px;border: 1px solid black;text-align:center;"></td>
						<td style="border: 1px solid black;text-align:center;"></td>
						<td style="width:115px;border: 1px solid black;text-align:center;"></td>
					</tr>';				
			}
			else if($rowcount==$x+1){
				$tablerepo2 .='<tr nobr="true">
						<td style="border: 1px solid black;text-align:center;">'.date("M d, Y", strtotime($s_date[$x])).'</td>
						<td style="border: 1px solid black;text-align:left;">'.number_format($s_count[$x]).'</td>
						<td style="width:105px;border: 1px solid black;text-align:left;"><b>'.number_format($s_accumulated_shots[$x]).'</b></td>
						<td style="width:79px;border: 1px solid black;text-align:left;">'.$s_machine_id[$x].'</td>
						<td style="border: 1px solid black;text-align:left;font-size:11px;">'.$s_operator[$x].'</td>
						<td style="font-size:10px;width:115px;border: 1px solid black;text-align:left;">'.$s_remakrs[$x].'</td>
					</tr>';	
			}
			else{
				$tablerepo2 .='<tr nobr="true">
						<td style="border: 1px solid black;text-align:center;">'.date("M d, Y", strtotime($s_date[$x])).'</td>
						<td style="border: 1px solid black;text-align:left;">'.number_format($s_count[$x]).'</td>
						<td style="width:105px;border: 1px solid black;text-align:left;">'.number_format($s_accumulated_shots[$x]).'</td>
						<td style="width:79px;border: 1px solid black;text-align:left;">'.$s_machine_id[$x].'</td>
						<td style="border: 1px solid black;text-align:left;font-size:11px;">'.$s_operator[$x].'</td>
						<td style="font-size:10px;width:115px;border: 1px solid black;text-align:left;">'.$s_remakrs[$x].'</td>
					</tr>';
			}	
		}

		$tablerepo2 .= '</tbody></table>';
			$pdf->SetFont($font, 'B', 12);
		$pdf->AddPage('P', 'A4');
		$pdf->writeHTMLCell(0, '', '', '24', $title, 0, 0, 0, true, 'C', true);
		$pdf->SetFont($font, 'B', 10);
		$pdf->writeHTMLCell(28, '', '19', '42', $device, 0, 0, 0, true, ' ', false);
		$pdf->writeHTMLCell(43, '', '19', '55', $mainte, 0, 0, 0, true, ' ', true);
		$pdf->writeHTMLCell(28, '', '118', '42', $cavty, 0, 0, 0, true, ' ', true);
		$pdf->writeHTMLCell(28, '', '118', '55', $draw, 0, 0, 0, true, ' ', true);
		$pdf->SetFont($font, ' ', 10);
		$pdf->writeHTMLCell(70, '', '20', '48', $dval, 0, 0, 0, true, ' ', false);
		$pdf->writeHTMLCell(43, '', '20', '61', $mval, 0, 0, 0, true, ' ', true);
		if($change_row_count>0){$pdf->writeHTMLCell(48, '', '58', '61', '*'.number_format($c_new_cycle[$change_row_count-1]).' - '.date("M d, Y", strtotime($c_date[$change_row_count-1])), 0, 0, 0, true, ' ', true);}
		$pdf->writeHTMLCell(28, '', '119', '61', $drval, 0, 0, 0, true, ' ', true);
		$pdf->SetFont($font, ' ', 8);
		$pdf->writeHTMLCell(28, '', '172', '22', $etct, 0, 0, 0, true, ' ', true);
		$pdf->Line(20, 52, 91, 52);
		$pdf->Line(20, 65, 91, 65);
		$pdf->Line(119, 52, 188, 52);
		$pdf->Line(119, 65, 188, 65);
		$pdf->SetFont($font, ' ', 10);
		$pdf->writeHTMLCell(0, '', '19', '68', $tablerepo2, 0, 0, 0, true, 'C', true);
		$pdf->lastPage();
		$pdf->SetFont($font, ' ', 8);
		// $pdf->writeHTMLCell(48, '', '155', '268', $etcb, 0, 0, 0, true, ' ', true);
		$default_rows=$default_rows+$default_rows;
	}
$qwerty = $qwerty+1;
$default_rows = 26;
}
// ---------------------------------------------------------
ob_end_clean();
//Close and output PDF document
$pdf->Output('DMCMSReport('.date("Y-m-d").').pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
}?>