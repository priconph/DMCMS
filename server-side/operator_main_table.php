<?php
	/*
	 * Script:    DataTables server-side script for PHP and MySQL
	 * Copyright: 2010 - Allan Jardine
	 * License:   GPL v2 or BSD (3-point)
	 */
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	// error_reporting(E_ALL);
	// ini_set('display_errors', 1);
	// function convertTime($h) {
	//     return [floor($h), (floor($h * 60) % 60), floor($h * 3600) % 60];
	// }

	function convertTime($dec){
		// start by converting to seconds
		$seconds = ($dec * 3600);
		// we're given hours, so let's get those the easy way
		$hours = floor($dec);
		// since we've "calculated" hours, let's remove them from the seconds variable
		$seconds -= $hours * 3600;
		// calculate minutes left
		$minutes = floor($seconds / 60);
		// remove those from seconds as well
		$seconds -= $minutes * 60;

		$milliseconds = (floor($seconds) / 100) * 1000;

		// return the time formatted HH:MM:SS
		// return lz($hours).":".lz($minutes).":".lz((int)$seconds).":".sprintf("%03d",$milliseconds);
		return lz($hours).":".lz($minutes);
	}

	// lz = leading zero
	function lz($num){
		return (strlen($num) < 2) ? "0{$num}" : $num;
	}

	$db_pps = 'db_pps';
	
	$array_where = array();

	$device_name = $_GET['sr_name'];
	$m_cycle 	 = $_GET['sr_m'];
	$cavty 	 	 = $_GET['sr_c'];
	$drawing 	 = $_GET['sr_d'];
	$l_update 	 = $_GET['sr_l'];	

	if($device_name != ""){
		$device_name_query = ' `v_dmcms_main_page`.`d_name` LIKE "%'.$device_name.'%"';
		$array_where[] = $device_name_query;
	}

	if($m_cycle != ""){
		$m_cycle_query = ' `v_dmcms_main_page`.`dc_maintenance_cycle` = "'.$m_cycle.'"';
		$array_where[] = $m_cycle_query;
	}

	if($cavty != ""){
		$cavty_query = ' `v_dmcms_main_page`.`d_cavty` = "'.$cavty.'"';
		$array_where[] = $cavty_query;
	}

	if($drawing != ""){
		$drawing_query = ' `v_dmcms_main_page`.`d_drawing` = "'.$drawing.'"';
		$array_where[] = $drawing_query;
	}

	if($l_update != ""){
		$l_update_query = ' `v_dmcms_main_page`.`dc_aupdate` = "'.$l_update.'"';
		$array_where[] = $l_update_query;
	}
	
	$aColumns = array( 	'`v_dmcms_main_page`.`d_id`',
					 	'`v_dmcms_main_page`.`d_name`',
						'`v_dmcms_main_page`.`dc_maintenance_cycle`',
						'`v_dmcms_main_page`.`dc_mstatus`',
						'`v_dmcms_main_page`.`d_cavty`',
						'`v_dmcms_main_page`.`d_drawing`',
						'`v_dmcms_main_page`.`d_cycle_time`',
						'`v_dmcms_main_page`.`dc_aupdate`',
						'`v_dmcms_main_page`.`dc_idd`',
						'`v_dmcms_main_page`.`grand_total`',
						'`v_dmcms_main_page`.`ongoing_accumulated_shots`',
						'`v_dmcms_main_page`.`last_actual_cavity`'
						);

	// $array_where[] .= ' ';
	/* used this field for searching data typed in the search box */
	// $array_search = array('empno', 'LastName',
							// 'date', 'date_out');
	// $array_search = array('empno', 'LastName');
							
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "dc_idd";
	
	/* DB table to use */
	$sTable = "`v_dmcms_main_page`";
	
	/* Database connection information */
	$gaSql['user']       = "root";
	$gaSql['password']   = "r@p1d0nl1n3";
	$gaSql['db']         = "db_dmcms";
	$gaSql['server']     = "localhost";
	
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * If you just want to use the basic configuration for DataTables with PHP server-side, there is
	 * no need to edit below this line
	 */
	
	/* 
	 * MySQL connection
	 */
	$gaSql['link'] =  mysql_pconnect( $gaSql['server'], $gaSql['user'], $gaSql['password']  ) or
		die( 'Could not open connection to server' );
	
	mysql_select_db( $gaSql['db'], $gaSql['link'] ) or 
		die( 'Could not select database '. $gaSql['db'] );
	
	
	/* 
	 * Paging
	 */
	$sLimit = "";
	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	{
		$sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
			mysql_real_escape_string( $_GET['iDisplayLength'] );
	}
		
	/*
	 * Ordering
	 */
	if ( isset( $_GET['iSortCol_0'] ) )
	{
		$sOrder = "ORDER BY  ";
		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			{
				$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
				 	".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
			}
		}
		
		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" )
		{
			$sOrder = "";
		}
	}
	
	
	/* 
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
	 
	 /* wag gagalawin yung sWhere default to para sa searchbox ng datatables check mo sa baba kung pano gagawin mo :) */
	$sWhere = "";
	if ( $_GET['sSearch'] != "" )
	{
		$sWhere = "WHERE (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			// if( in_array($aColumns[$i],$array_search) ){
			$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
			// }
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere .= ')';
	}
	
	/* Individual column filtering */
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
		{
			if ( $sWhere == "" )
			{
				$sWhere = "WHERE ";
			}
			else
			{
				$sWhere .= " AND ";
			}
			$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
		}
	}
	
	/* 
		dito ka mag add ng where mo, una check mo kung may laman na yung $sWhere pag wala append mo yung where mo na meron
		kasama where kapag naman may laman na AND na syempre diba :)
	*/
	
	if( count($array_where) !=0 ){
		if($sWhere == ""){
			$sWhere .= "WHERE  ";
		}else{
			$sWhere .= "AND ";
		}
		$sWhere .= implode(' AND ',$array_where);
	}
	


	// $date_start = $_GET['date_start'];
	// $date_end 	= $_GET['date_end'];
	
	// $sWhere .= "((date BETWEEN '" . $date_start . "' AND '" . $date_end . "') OR (date_out BETWEEN '" . $date_start . "' AND '" . $date_end . "')) AND `empno` LIKE '%".$empno."%'";
	
	/*
	 * SQL queries
	 * Get data to display
	 */
	$sQuery = "
		SELECT ".str_replace(" , ", " ", implode(", ", $aColumns))."
		FROM   $sTable
		$sWhere
		$sOrder
		$sLimit
	";
	$rResult = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
	
	$iTotal = mysql_num_rows($rResult);
	
	// echo $sQuery;
	// echo "limit: ".$sLimit." : ".$sQuery."<hr>";
	// $query_used = $sQuery;
	// echo $query_used;
	
	/* Data set length after filtering */
	// $sQuery = "
	// 	SELECT FOUND_ROWS()
	// ";
	// $rResultFilterTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
	// $aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
	// $iFilteredTotal = $aResultFilterTotal[0];
	
	//  Total data set length 
	// $sQuery = "
	// 	SELECT COUNT(".$sIndexColumn.")
	// 	FROM   $sTable
	// ";
	// $rResultTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
	// $aResultTotal = mysql_fetch_array($rResultTotal);
	// $iTotal = $aResultTotal[0];
	
	
	/*
	 * Output
	 */
	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iTotal,
		"aaData" => array()
	);
	
	while ( $aRow = mysql_fetch_array( $rResult ) )
	{
		$maintenance_cycle = (int)$aRow['dc_maintenance_cycle']; 
		$row = array();
		unset($row);
		$zero=0;
		if($maintenance_cycle>=$aRow['ongoing_accumulated_shots']){
			$balance = $maintenance_cycle-$aRow['ongoing_accumulated_shots'];
			if($balance==0){
				$remainng_prodn_time = '<i style="font-size:80%;">N/A</i>';
				$balance = '<i style="font-size:80%;">Maintenance Cycle Reached</i>';
				if($aRow['dc_maintenance_cycle']==''||$aRow['dc_maintenance_cycle']==0){
					$balance = '<i style="color:red;font-size:80%;">Maintenance Cycle Not Set</i>';
				}	
			}
			else{
				if($aRow['d_cycle_time']==''||$aRow['d_cycle_time']==0){
					$remainng_prodn_time = '<i style="color:red;font-size:80%;">Cycle Time Not Set</i>';
				}				
				else{
					if($aRow['ongoing_accumulated_shots']==0){
						$remainng_prodn_time = '<i style="font-size:80%;">Not yet started</i>';
					}
					else{
						$bal_divide_actual_cavity = $balance/(int)$aRow['last_actual_cavity'];
						$bal_divide_actual_cavity_times_cycle_time = $bal_divide_actual_cavity*floatval($aRow['d_cycle_time']);
						$remainng_prodn_time = $bal_divide_actual_cavity_times_cycle_time/3600;
						$remainng_prodn_time = convertTime($remainng_prodn_time);
					}
				}				
				$balance = number_format($balance);
			}
		}
		else{
			$balance = '<i style="font-size:80%;">Over Maintenance Cycle</i>';
			$remainng_prodn_time = '<i style="font-size:80%;">N/A</i>';
		}	

		// if ($aRow['ongoing_accumulated_shots'] >= ($aRow['dc_maintenance_cycle'] * 0.80) && ($aRow['ongoing_accumulated_shots'] < ($aRow['dc_maintenance_cycle'] * 0.9)) && ($aRow['dc_maintenance_cycle'] > $zero)){
		// 	$row_accumulated_shots = '	<span style="height:100%;width:100%;">'.number_format($aRow['ongoing_accumulated_shots']).' <i style="color:#f7c07e;" class="pull-right fa fa-exclamation-triangle"></i></span>';
		// 	$row[] = '<span style="color:#f7c07e;background-color:#f7c07e;">2 &nbsp</span>';
		// } 
		// else 

		if ($aRow['ongoing_accumulated_shots'] >= ($aRow['dc_maintenance_cycle'] * 0.9) && ($aRow['ongoing_accumulated_shots'] < ($aRow['dc_maintenance_cycle'])) && ($aRow['dc_maintenance_cycle'] > $zero)){						
			$row_accumulated_shots = '	<span style="height:100%;width:100%;">'.number_format($aRow['ongoing_accumulated_shots']).' <i style="color:yellow;" class="pull-right fa fa-exclamation-triangle"></i></span>';
			$row[] = '<span style="color:yellow;background-color:yellow;">3 &nbsp</span>';
		} 
		else if ($aRow['ongoing_accumulated_shots'] >= ($aRow['dc_maintenance_cycle']) && $aRow['dc_maintenance_cycle']!='0' && ($aRow['dc_maintenance_cycle'] > $zero)){
			$row_accumulated_shots = '	<span style="height:100%;width:100%;">'.number_format($aRow['ongoing_accumulated_shots']).' <i style="color:red;" class="pull-right fa fa-exclamation-triangle"></i></span>';
			$row[] = '<span style="color:red;background-color:red;">4 &nbsp</span>';
		}
		else if($aRow['dc_maintenance_cycle'] <= $zero){
			$row_accumulated_shots = '	<span style="height:100%;width:100%;"> <i style="color:red;font-size:14px;">Maintenance Cycle Not Set</i></span>';
			$row[] = '<span> &nbsp</span>';
		} 
		else{
			$row_accumulated_shots = '<span>'.number_format($aRow['ongoing_accumulated_shots']).'</span>';
			$row[] = '<span style="color:white;">1 &nbsp</span>';
		}

		$row[] = '<a href="operator_index.php?page=operator.device.php&device_id='.$aRow['d_id'].'">'.$aRow['d_name'].'</a>';
		$row[] = $aRow['d_drawing'];
		$row[] = $aRow['d_cavty'];
		
		if($maintenance_cycle <= $zero){
			$row[] = '	<span style="height:100%;;width:100%;"> <i style="color:red;font-size:14px;">Maintenance Cycle Not Set</i></span>';
			} 
		else{
			$row[] = number_format($aRow['dc_maintenance_cycle']);
		}

		$row[] = $row_accumulated_shots;
		$row[] = $balance;
		$row[] = $aRow['last_actual_cavity'];

		// if($aRow['d_cycle_time']==''||$aRow['d_cycle_time']==0){
		// 	$row[] = '<i style="color:red;font-size:14px;">Not Set</i>';
		// }
		// else{
		// 	$row[] = convertTime(floatval($aRow['d_cycle_time'])*(0.0002777777777777778));
		// }
		
		$row[] = $remainng_prodn_time;
	
		if($aRow['dc_aupdate']=='0000-00-00'||$aRow['dc_aupdate']==''){
			$row[] = '<span> </span>';
		}
		else{
			$row[] = '<span hidden>'.date("Y-m-d", strtotime($aRow['dc_aupdate'])).'</span>'.date("M d, Y ", strtotime($aRow['dc_aupdate'])).'<i style="font-size:11px;color:#7b818c;"> &nbsp'.date("h:i A", strtotime($aRow['dc_aupdate'])).'</i>';
		}
	
		if($aRow['grand_total'] != ''){
			$row[] = number_format($aRow['grand_total']);
		}
		else{
			$row[] = '';
		}	

		array_push($output['aaData'],$row);
	}
	echo json_encode( $output );
?>