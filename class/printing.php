<?php
// require('../media/FPDF/fpdf.php'); //use this if it is in the root directory of your system
require('../../../media/FPDF/fpdf.php');

class PDF extends FPDF
{
	function place_values($value,$font,$ordinate){
		$this->SetAutoPageBreak(false);
		$this->SetFont('Arial', $font['style'], $font['size']);
		$this->SetXY($ordinate['x'], $ordinate['y']);
		$this->write( 0, $value);
		return $ordinate['y'];
	}
	
	function place_values_multiline($label,$value,$font,$ordinate){
		/* breakdown per breakline */
		$array_values = explode("\n",$value);
		$additional_space = 4;

		for($x=0;$x<count($array_values);$x++){
			$this->SetAutoPageBreak(false);
			$this->SetFont('Arial', $font['style'], $font['size']);
			if($x==0){
				if(strpos($array_values[$x],':') !== false){
					$this->SetFont('Arial', 'B', $font['size']);
				}
				$this->SetXY($ordinate['x'], $ordinate['y']);
			}else{
				$this->SetFont('Arial', $font['style'], $font['size']);
				$this->SetXY($ordinate['x'], ($ordinate['y'] + $additional_space) );
				$additional_space += 4;
			}
			if(isset($label[$x]) && $label[$x] != ""){
				$this->cell( 23, 10, $label[$x]." : ",0 );
				if($x==0){
					$this->SetXY( ($ordinate['x']+25), ($ordinate['y']) );
				}else{
					$this->SetXY( ($ordinate['x']+25), ($ordinate['y'] + $additional_space-4) );
				}
				$this->cell( 23, 10, $array_values[$x],0 );
			}else{
				$this->write( 0, $array_values[$x]);
			}
			
		}
		return $ordinate['y'] + $additional_space;
	}
	
	function add_page(){
		$this->AddPage('P');
	}
	
}