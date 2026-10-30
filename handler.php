<?php

	if(is_ajax()) {
		$d_id_identifier = $_POST["d_id_identifier"];
		if(isset($_POST["action"]) && !empty($_POST["action"])) {
			$action = $_POST["action"];
			switch($action) {
				case "load_devices" 			: load_devices(); break; //switch case, in using ajax an action must be set in order to choose the specific function to use	
				case "load_machine_no" 			: load_machine_no(); break; //switch case, in using ajax an action must be set in order to choose the specific function to use	
				case "load_maintenance" 		: load_maintenance(); break; //switch case, in using ajax an action must be set in order to choose the specific function to use	
				case "load_cavty" 				: load_cavty(); break; //switch case, in using ajax an action must be set in order to choose the specific function to use	
				case "load_drawing" 			: load_drawing(); break; //switch case, in using ajax an action must be set in order to choose the specific function to use						
				case "update_shots" 			: update_shots(); break; //switch case, in using ajax an action must be set in order to choose the specific function to use
				case "load_ongoing_device" 		: load_ongoing_device(); break; //switch case, in using ajax an action must be set in order to choose the specific function to use
				case "update_ongoing" 			: update_ongoing(); break; //switch case, in using ajax an action must be set in order to choose the specific function to use
				case "load_device_details" 		: load_device_details(); break; //switch case, in using ajax an action must be set in order to choose the specific function to use
				case "load_alert" 				: load_alert(); break; //switch case, in using ajax an action must be set in order to choose the specific function to use				
				case "load_report_table"		: load_report_table(); break;
				case "load_dieset_cycle"		: load_dieset_cycle(); break;
				case "reset_shots"				: reset_shots(); break;
				case "save_other_act"			: save_other_act(); break;
				case "change_cycle"				: change_cycle(); break;
				case "cycle_mstatus"			: cycle_mstatus(); break;
				case "load_users"				: load_users(); break;
				case "save_past_shots"	   		: save_past_shots(); break;
				case "save_other_by_head" 		: save_other_by_head(); break;
				case "load_check_mcycle" 		: load_check_mcycle(); break;
				case "load_new_cycle"      		: load_new_cycle(); break;
				case "load_new_devices" 		: load_new_devices(); break;
				case "update_dmcms"				: update_dmcms(); break;
			}
		}
	}

	function is_ajax() {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}

	function update_dmcms(){
		require_once('query.php');
		$return = $_POST; 
		$result = "";
		$table        = "t_shots";
		$array_fields = array('s_d_id');
		$array_values = array($return['new_pkid']);
		$pkid = $return['old_id'];
		$id = 's_d_id';
		$result = dmcms::getInstance()->update_dmcms($table,$array_fields,$array_values,$id,$pkid);

		$result = "";
		$table        = "t_dieset_cycle";
		$array_fields = array('dc_d_id');
		$array_values = array($return['new_pkid']);
		$pkid = $return['old_id'];
		$id = 'dc_d_id';
		$result = dmcms::getInstance()->update_dmcms($table,$array_fields,$array_values,$id,$pkid);


		$return = $_POST; 
		$result = "";
		$table        = "t_shots";
		$array_fields = array('s_d_id');
		$array_values = array($return['new_pkid']);
		$pkid = $return['old_id'];
		$id = 's_d_id';
		$result = sanda_dmcms::getInstance()->update_dmcms($table,$array_fields,$array_values,$id,$pkid);

		$result = "";
		$table        = "t_dieset_cycle";
		$array_fields = array('dc_d_id');
		$array_values = array($return['new_pkid']);
		$pkid = $return['old_id'];
		$id = 'dc_d_id';
		$result = sanda_dmcms::getInstance()->update_dmcms($table,$array_fields,$array_values,$id,$pkid);

		$result = "";
		$table        = "t_sanda_devices";
		$array_fields = array('sd_d_id');
		$array_values = array($return['new_pkid']);
		$pkid = $return['old_id'];
		$id = 'sd_d_id';
		$result = sanda_dmcms::getInstance()->update_dmcms($table,$array_fields,$array_values,$id,$pkid);
		
		$return["result"] = json_encode($result); 
		echo json_encode($return);
	}

	function load_new_cycle(){
		require_once('query.php');
		$d_id_para				= $_POST['id_para']; 
		$new_mcycle_para 		= $_POST['new_mcycle_para'];

		// if(($ashots_para >= (.80*$new_mcycle_para)) && ($ashots_para < (.9*$new_mcycle_para))){
		// 	$dc_mstatus = 2;	
		// }
		// else 
		if(!empty($_POST['ashots_para'])){
			$ashots_para 			= $_POST['ashots_para'];
			if (($ashots_para >= (.9*$new_mcycle_para)) && ($ashots_para < $new_mcycle_para)) {
				$dc_mstatus = 3;		
			}
			else if ($ashots_para >= $new_mcycle_para) {
				$dc_mstatus = 4;	
			}
			else{
				$dc_mstatus = 1;
			}

			$return = $_POST; 
			$result = "";
			$table        = "t_dieset_cycle";
			$array_fields = array('dc_maintenance_cycle','dc_mstatus');
			$array_values = array($new_mcycle_para,$dc_mstatus);
			$pkid = $d_id_para;
			$result = dmcms::getInstance()->update_query_real_cycle($table,$array_fields,$array_values,$pkid);
			// $query = dmcms::getInstance()->update_query_real_cycle_query($table,$array_fields,$array_values,$pkid);
			$return["result"] = json_encode($result); //return the the serialize values
		}
		else{			
			$return = $_POST; 
			$result = "";
			$table        = "t_dieset_cycle";
			$array_fields = array('dc_maintenance_cycle');
			$array_values = array($new_mcycle_para);
			$pkid = $d_id_para;
			$result = dmcms::getInstance()->update_query_real_cycle($table,$array_fields,$array_values,$pkid);
			// $query = dmcms::getInstance()->update_query_real_cycle_query($table,$array_fields,$array_values,$pkid);
			$return["result"] = json_encode($result); //return the the serialize values
		}
		
		// $return["query"] = $query;
		echo json_encode($return); //json encode since I used a dataType json before passing the serialized values here
	}
	
	function load_check_mcycle(){
		require_once('query.php'); //OOP Path
		$return = $_POST;
		$id_para= $return['id_para'];
		$result = "";
		$array_fields = array('*');
		$table 	   	= 't_device';
		$joins 	   	= 'INNER JOIN `db_dmcms`.`t_dieset_cycle` ON `db_pps`.`t_device`.`d_id`=`db_dmcms`.`t_dieset_cycle`.`dc_d_id`';
		$sql_where 	= "WHERE d_id='$id_para'";
		$sql_order 	= '';
		$sql_limit 	= '';
		$result = pps::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);

		while($row = mysqli_fetch_array($result)){
			$return['real_mcycle']= $row['d_maintenance']; 
			$return['old_mcycle']= $row['dc_maintenance_cycle']; 
		}
	
	
		$return['result'] = json_encode($result);
		echo json_encode($return);
	}

	function save_other_by_head(){
		require_once('query.php'); //OOP Path
		$date_now				= date("Y-m-d H:i:s");
		$dc_id_device			= $_POST['dc_id_device'];
		$dc_ashots 				= $_POST['dc_ashots'];
		$dc_start_date_para		= $_POST['dc_start_date_para'];
		$dc_end_date_para 		= $_POST['dc_end_date_para'];
		$d_id_para				= $_POST['d_id_para'];
		$dc_mcycle_para			= $_POST['dc_mcycle_para'];				
		$user_name				= $_POST['user_name'];
		$return 				= $_POST; 
		$result 				= "";
		$dc_mstatus 			= 0;
		$dc_accumulated_shots 	= $dc_ashots+$return['txt_shots'];

		$table   				= "t_shots"; //table name
		$array_fields	 		= array('s_id','s_d_id','s_date','s_count','s_machine','s_remarks','s_status','s_dc_id','s_actual_cavty','s_adate','s_operator','s_accum_shots'); //table fields
		$array_values 			= array('',$d_id_para,date("Y-m-d"),$return['txt_shots'],'',$return['txt_activity'],'otherbyhea',$dc_id_device,'',$date_now,$user_name,$dc_accumulated_shots); //values to be saved
		$result 				= dmcms::getInstance()->insert_query($table,$array_fields,$array_values);
		$return["result"] 		= json_encode($result); //return the the serialize values	
		
		$dc_accumulated_shots 		= $dc_ashots+$return['txt_shots'];	

		// if(($dc_accumulated_shots >= (.80*$dc_mcycle_para)) && ($dc_accumulated_shots < (.9*$dc_mcycle_para))){
		// 	$dc_mstatus = 2;	
		// }
		// else 

		if (($dc_accumulated_shots >= (.9*$dc_mcycle_para)) && ($dc_accumulated_shots < $dc_mcycle_para)) {
			$dc_mstatus = 3;		
		}
		else if ($dc_accumulated_shots >= $dc_mcycle_para) {
			$dc_mstatus = 4;	
		}
		else{
			$dc_mstatus = 1;
		}

		$table   					= "t_dieset_cycle"; //table name
		if($dc_start_date_para 		== '0000-00-00' || $dc_start_date_para = ''){			
			$array_fields 			= array('dc_accumulated_shots','dc_start_date','dc_end_date','dc_mstatus','dc_aupdate');
			$array_values 			= array($dc_accumulated_shots,$date_now,$date_now,$dc_mstatus,$date_now);
		
		}
		else{
			$array_fields 			= array('dc_accumulated_shots','dc_end_date','dc_mstatus','dc_aupdate');
			$array_values 			= array($dc_accumulated_shots,$date_now,$dc_mstatus,$date_now);
		}
		$pkid 						= $dc_id_device;
		$result 					= dmcms::getInstance()->update_query_save_other($table,$array_fields,$array_values,$pkid);
		$return["result"] 			= json_encode($result); //return the the serialize values

		echo json_encode($return);
	}

	function save_past_shots(){
		require_once('query.php'); //OOP Path
		$date_now				= date("Y-m-d H:i:s");
		$dc_id_device			= $_POST['dc_id_device'];
		$dc_ashots 				= $_POST['dc_ashots'];
		$dc_start_date_para		= $_POST['dc_start_date_para'];
		$dc_end_date_para 		= $_POST['dc_end_date_para'];
		$d_id_para				= $_POST['d_id_para'];
		$dc_mcycle_para			= $_POST['dc_mcycle_para'];				
		$user_name				= $_POST['user_name'];
		$return 				= $_POST; 
		$result 				= "";
		$dc_mstatus 			= 0;

		$table   				= "t_shots"; //table name
		$array_fields	 		= array('s_id','s_d_id','s_date','s_count','s_machine','s_remarks','s_status','s_dc_id','s_actual_cavty','s_adate','s_operator'); //table fields
		$array_values 			= array('',$d_id_para,date("Y-m-d", strtotime($return['txt_past_date'])),$return['txt_past_shots'],'','','past','','',$date_now,$user_name); //values to be saved
		$result 				= dmcms::getInstance()->insert_query($table,$array_fields,$array_values);
		$return["result"] 		= json_encode($result); //return the the serialize values	

		echo json_encode($return);
	}

	function cycle_mstatus(){
		require_once('query.php'); //OOP Path
		$return = $_POST;
		$result = "";
		
		$array_fields = array('*');
		// $table 	   	= 't_dieset_cycle';
		// $joins 	   	= 'INNER JOIN '.dmcms::getInstance()->db_pps.'.t_device ON t_dieset_cycle.dc_d_id=t_device.d_id';
		// $sql_where 	= "WHERE (SELECT SUM(s_count) FROM `t_shots` WHERE `t_shots`.`s_d_id`=`t_dieset_cycle`.`dc_d_id` AND `t_shots`.`s_status`='ongoing') >= (dc_maintenance_cycle*0.8) AND ((SELECT SUM(s_count) FROM `t_shots` WHERE `t_shots`.`s_d_id`=`t_dieset_cycle`.`dc_d_id` AND `t_shots`.`s_status`='ongoing') < (dc_maintenance_cycle*0.9)) AND (dc_maintenance_cycle > 0) AND dc_status='ongoing' AND logdel=0";
		// $sql_order 	= '';
		// $sql_limit 	= '';
		// $result 	= dmcms::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		// $x2			= $result->num_rows;
		// $ctr2 = 0;
		// while($row = mysqli_fetch_array($result)){
		// 	$return['d_id2'][$ctr2] 				= $row['d_id'];
		// 	$return['d_name2'][$ctr2] 				= $row['d_name'];
		// 	$return['d_cavty2'][$ctr2] 				= $row['d_cavty'];
		// 	$return['d_drawing2'][$ctr2] 			= $row['d_drawing'];
		// 	$return['d_maintenance_cycle2'][$ctr2] 	= $row['d_maintenance_cycle'];
		// 	$return['dc_idd2'][$ctr2] 				= $row['dc_idd'];
		// 	$return['dc_d_id2'][$ctr2] 				= $row['dc_d_id'];
		// 	$return['dc_accumulated_shots2'][$ctr2] = $row['dc_accumulated_shots'];
		// 	if($row['dc_start_date']=='0000-00-00'){
		// 		$return['dc_start_date2'][$ctr2] 	= '';
		// 	}
		// 	else{
		// 		$return['dc_start_date2'][$ctr2] 	= date("M d, Y", strtotime($row['dc_start_date']));
		// 	}
		// 	if($row['dc_end_date']=='0000-00-00'){
		// 		$return['dc_end_date2'][$ctr2] 	= '';
		// 	}
		// 	else{
		// 		$return['dc_end_date2'][$ctr2] 			= date("M d, Y", strtotime($row['dc_end_date']));
		// 	}
		// 	$return['dc_maintenance_cycle2'][$ctr2] = $row['dc_maintenance_cycle'];
		// 	$return['dc_status2'][$ctr2] 			= $row['dc_status'];
		// 	$return['dc_mstatus2'][$ctr2] 			= $row['dc_mstatus'];
		// 	$ctr2++;
		// }
		// $return['ctr2']	  	= $ctr2;
		// $return['count82']	= $x2;
		// $return['result'] 	= json_encode($result);

		$table 	   	= 't_dieset_cycle';
		$joins 	   	= 'INNER JOIN '.dmcms::getInstance()->db_pps.'.t_device ON t_dieset_cycle.dc_d_id=t_device.d_id';
		$sql_where 	= "WHERE (SELECT SUM(s_count) FROM `t_shots` WHERE `t_shots`.`s_d_id`=`t_dieset_cycle`.`dc_d_id` AND `t_shots`.`s_status`='ongoing') >= dc_maintenance_cycle*0.9 AND (SELECT SUM(s_count) FROM `t_shots` WHERE `t_shots`.`s_d_id`=`t_dieset_cycle`.`dc_d_id` AND `t_shots`.`s_status`='ongoing')<dc_maintenance_cycle AND (dc_maintenance_cycle>0) AND dc_status='ongoing' AND logdel=0";
		$sql_order 	= '';
		$sql_limit 	= '';
		$result 	= dmcms::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$return['sd'] 	= dmcms::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$x3			= $result->num_rows;
		$ctr3 = 0;
		while($row = mysqli_fetch_array($result)){
			$return['d_id3'][$ctr3] 				= $row['d_id'];
			$return['d_name3'][$ctr3] 				= $row['d_name'];
			$return['d_cavty3'][$ctr3] 				= $row['d_cavty'];
			$return['d_drawing3'][$ctr3] 			= $row['d_drawing'];
			$return['d_maintenance_cycle3'][$ctr3] 	= $row['d_maintenance_cycle'];
			$return['dc_idd3'][$ctr3] 				= $row['dc_idd'];
			$return['dc_d_id3'][$ctr3] 				= $row['dc_d_id'];
			$return['dc_accumulated_shots3'][$ctr3] = $row['dc_accumulated_shots'];
			if($row['dc_start_date']=='0000-00-00'){
				$return['dc_start_date3'][$ctr3] 	= '';
			}
			else{
				$return['dc_start_date3'][$ctr3] 	= date("M d, Y", strtotime($row['dc_start_date']));
			}
			if($row['dc_end_date']=='0000-00-00'){
				$return['dc_end_date3'][$ctr3] 	= '';
			}
			else{
				$return['dc_end_date3'][$ctr3] 			= date("M d, Y", strtotime($row['dc_end_date']));
			}
			$return['dc_maintenance_cycle3'][$ctr3] = $row['dc_maintenance_cycle'];
			$return['dc_status3'][$ctr3] 			= $row['dc_status'];
			$return['dc_mstatus3'][$ctr3] 			= $row['dc_mstatus'];
			$ctr3++;
		}
		$return['ctr3']	  	= $ctr3;
		$return['count90']	= $x3;
		$return['result'] 	= json_encode($result);

		$table 	   	= 't_dieset_cycle';
		$joins 	   	= 'INNER JOIN '.dmcms::getInstance()->db_pps.'.t_device ON t_dieset_cycle.dc_d_id=t_device.d_id';
		$sql_where 	= "WHERE (SELECT SUM(s_count) FROM `t_shots` WHERE `t_shots`.`s_d_id`=`t_dieset_cycle`.`dc_d_id` AND `t_shots`.`s_status`='ongoing') >= (dc_maintenance_cycle) AND dc_maintenance_cycle!='0' AND (dc_maintenance_cycle > 0) AND dc_status='ongoing' AND logdel=0";
		$sql_order 	= '';
		$sql_limit 	= '';
		$result 	= dmcms::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$x4			= $result->num_rows;
		$ctr4 = 0;
		while($row = mysqli_fetch_array($result)){
			$return['d_id4'][$ctr4] 				= $row['d_id'];
			$return['d_name4'][$ctr4] 				= $row['d_name'];
			$return['d_cavty4'][$ctr4] 				= $row['d_cavty'];
			$return['d_drawing4'][$ctr4] 			= $row['d_drawing'];
			$return['d_maintenance_cycle4'][$ctr4] 	= $row['d_maintenance_cycle'];
			$return['dc_idd4'][$ctr4] 				= $row['dc_idd'];
			$return['dc_d_id4'][$ctr4] 				= $row['dc_d_id'];
			$return['dc_accumulated_shots4'][$ctr4] = $row['dc_accumulated_shots'];
			if($row['dc_start_date']=='0000-00-00'){
				$return['dc_start_date4'][$ctr4] 	= '';
			}
			else{
				$return['dc_start_date4'][$ctr4] 	= date("M d, Y", strtotime($row['dc_start_date']));
			}
			if($row['dc_end_date']=='0000-00-00'){
				$return['dc_end_date4'][$ctr4] 	= '';
			}
			else{
				$return['dc_end_date4'][$ctr4] 			= date("M d, Y", strtotime($row['dc_end_date']));
			}
			$return['dc_maintenance_cycle4'][$ctr4] = $row['dc_maintenance_cycle'];
			$return['dc_status4'][$ctr4] 			= $row['dc_status'];
			$return['dc_mstatus4'][$ctr4] 			= $row['dc_mstatus'];
			$ctr4++;
		}
		$return['ctr4']	  	= $ctr4;
		$return['count100']	= $x4;
		$return['result'] 	= json_encode($result);
		echo json_encode($return);
	}

	function change_cycle(){
		require_once('query.php'); //OOP Path
		$return 				= $_POST; 
		$result 				= "";
		$dc_id_device			= $_POST['dc_id_device'];
		$d_id_device			= $_POST['d_id_device'];		
		$user_name				= $_POST['user_name'];
		$dc_ashots				= $_POST['dc_ashots'];

		if($dc_ashots>=(.80*$return['txt_new_cycle'])&&$dc_ashots<(.90*$return['txt_new_cycle'])){
			$mstatus = 2;
		}
		else if($dc_ashots>=(.90*$return['txt_new_cycle'])&&$dc_ashots<$return['txt_new_cycle']){
			$mstatus = 3;
		}
		else if($dc_ashots>=$return['txt_new_cycle']){
			$mstatus = 4;
		}
		else{
			$mstatus = 1;
		}

		$table        			= "t_dieset_cycle";
		$array_fields 			= array('dc_maintenance_cycle','dc_mstatus');
		$array_values 			= array($return['txt_new_cycle'],$mstatus);
		$pkid 					= $dc_id_device;
		$result 				= dmcms::getInstance()->update_query_dccycle($table,$array_fields,$array_values,$pkid);
		$return["result"] 		= json_encode($result); //return the the serialize values

		$table   				= "t_change_cycle"; //table name
		$array_fields	 		= array('c_id','c_new_cycle','c_date','c_dc_id','c_reason','c_d_id','c_s_head'); //table fields
		$array_values 			= array('',$return['txt_new_cycle'],date("Y-m-d"),$dc_id_device,$return['txt_reason'],$d_id_device,$user_name); //values to be saved
		$result 				= dmcms::getInstance()->insert_query($table,$array_fields,$array_values);
		$return["result"] 		= json_encode($result); //return the the serialize values

		echo json_encode($return); //json encode since I used a dataType json before passing the serialized values here
	}

	function save_other_act(){
		require_once('query.php'); //OOP Path
		$date_now				= date("Y-m-d H:i:s");
		$dc_id_device			= $_POST['dc_id_device'];
		$dc_ashots 				= $_POST['dc_ashots'];
		$dc_start_date_para		= $_POST['dc_start_date_para'];
		$dc_end_date_para 		= $_POST['dc_end_date_para'];
		$d_id_para				= $_POST['d_id_para'];
		$dc_mcycle_para			= $_POST['dc_mcycle_para'];				
		$user_name				= $_POST['user_name'];
		$return 				= $_POST; 
		$result 				= "";
		$dc_mstatus 			= 0;
		$dc_accumulated_shots 	= $dc_ashots+$return['txt_shots'];	

		$table   				= "t_shots"; //table name
		$array_fields	 		= array('s_id','s_d_id','s_date','s_count','s_machine','s_remarks','s_status','s_dc_id','s_actual_cavty','s_adate','s_operator','s_accum_shots'); //table fields
		$array_values 			= array('',$d_id_para,date("Y-m-d"),$return['txt_shots'],'',$return['txt_activity'],'other',$dc_id_device,'',$date_now,$user_name,$dc_accumulated_shots); //values to be saved
		$result 				= dmcms::getInstance()->insert_query($table,$array_fields,$array_values);
		$return["result"] 		= json_encode($result); //return the the serialize values	

		$array_fields = array('*');
		$table 	   	= 't_device';
		$joins 	   	= '';
		$sql_where 	= "WHERE d_id='$device_id'";
		$sql_order 	= '';
		$sql_limit 	= '';
		$result = pps::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		while($row = mysqli_fetch_array($result)){
			$d_name 				= $row['d_name'];
		}
		
		if(($dc_accumulated_shots >= (.80*$dc_mcycle_para)) && ($dc_accumulated_shots < (.9*$dc_mcycle_para))){
			$dc_mstatus = 2;
		}
		else if (($dc_accumulated_shots >= (.9*$dc_mcycle_para)) && ($dc_mcycle_para < $dc_mcycle_para)) {
			$dc_mstatus = 3;
		}
		else if ($dc_accumulated_shots >= $dc_mcycle_para) {
			$dc_mstatus = 4;
		}
		else{
			$dc_mstatus = 1;
		}

		$table   					= "t_dieset_cycle"; //table name
		if($dc_start_date_para 		== '0000-00-00' || $dc_start_date_para = ''){			
			$array_fields 			= array('dc_accumulated_shots','dc_start_date','dc_end_date','dc_mstatus','dc_aupdate');
			$array_values 			= array($dc_accumulated_shots,$date_now,$date_now,$dc_mstatus,$date_now);
		
		}
		else{
			$array_fields 			= array('dc_accumulated_shots','dc_end_date','dc_mstatus','dc_aupdate');
			$array_values 			= array($dc_accumulated_shots,$date_now,$dc_mstatus,$date_now);
		}
		$pkid 						= $dc_id_device;
		$result 					= dmcms::getInstance()->update_query_save_other($table,$array_fields,$array_values,$pkid);
		$return["result"] 			= json_encode($result); //return the the serialize values



		echo json_encode($return);
	}
	
	/* function for displaying devices name */
	function load_devices(){
		require_once('query.php'); //OOP Path
		$return = $_POST;
		$result = "";
		$array_fields = array('DISTINCT d_name');
		$table 	   	= 't_device';
		$joins 	   	= '';
		$sql_where 	= '';
		$sql_order 	= 'ORDER BY d_name';
		$sql_limit 	= '';
		$result = pps::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr = 0;
		$html = '';
		while($row = mysqli_fetch_array($result)){
			$html.= '<option data-value="'.$row['d_id'].'" value="'.$row['d_name'].'"></option>';
		}
		$return['html']	  = $html;
 		$return['result'] = json_encode($result);
		echo json_encode($return);
	}

	/* function for displaying maintenance no details */
	function load_maintenance(){
		require_once('query.php'); //OOP Path
		$return = $_POST;
		$result = "";
		$array_fields = array('*');
		$table 	   	= 't_device';
		$joins 	   	= '';
		$sql_where 	= '';
		$sql_order 	= 'ORDER BY `d_name`';
		$sql_limit 	= '';
		$result = pps::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr = 0;
		$html = '';
		while($row = mysqli_fetch_array($result)){
			$html.= '<option data-value="'.$row['d_id'].'" value="'.$row['d_maintenance_cycle'].'"></option>';
		}
		$return['html']	  = $html;
		$return['ctr']	  = $ctr;
		$return['result'] = json_encode($result);
		echo json_encode($return);
	}

	/* function for displaying maintenance no details */
	function load_cavty(){
		require_once('query.php'); //OOP Path
		$return = $_POST;
		$result = "";
		$array_fields = array('DISTINCT d_cavty');
		$table 	   	= 't_device';
		$joins 	   	= '';
		$sql_where 	= '';
		$sql_order 	= 'ORDER BY `d_name`';
		$sql_limit 	= '';
		$result = pps::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr = 0;
		$html = '';
		while($row = mysqli_fetch_array($result)){
			$html.= '<option data-value="'.$row['d_id'].'" value="'.$row['d_cavty'].'"></option>';
		}
		$return['html']	  = $html;
		$return['ctr']	  = $ctr;
		$return['result'] = json_encode($result);
		echo json_encode($return);
	}

	/* function for displaying maintenance no details */
	function load_drawing(){
		require_once('query.php'); //OOP Path
		$return = $_POST;
		$result = "";
		$array_fields = array('DISTINCT d_drawing');
		$table 	   	= 't_device';
		$joins 	   	= '';
		$sql_where 	= '';
		$sql_order 	= 'ORDER BY `d_name`';
		$sql_limit 	= '';
		$result = pps::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr = 0;
		$html = '';
		while($row = mysqli_fetch_array($result)){
			$html.= '<option data-value="'.$row['d_id'].'" value="'.$row['d_drawing'].'"></option>';
		}
		$return['html']	  = $html;
		$return['ctr']	  = $ctr;
		$return['result'] = json_encode($result);
		echo json_encode($return);
	}

	function load_new_devices(){
		require_once('query.php'); //OOP Path
		$return = $_POST;
		$result = "";
		$array_fields = array('*');
		$table 	   	= 't_device';
		$joins 	   	= '';
		$sql_where 	= 'WHERE `d_id` NOT IN (SELECT `dc_d_id` FROM `db_dmcms`.`t_dieset_cycle`)';
		$sql_order 	= '';
		$sql_limit 	= '';
		$result 	= pps::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$return['sd'] 	= pps::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$num_rows 	= $result->num_rows;
		if($num_rows>0){
			$result = "";
			$table   		= "t_dieset_cycle";
			$array_fields 	= array('dc_d_id','dc_accumulated_shots','dc_start_date','dc_end_date','dc_maintenance_cycle','dc_status'); //table fields
			$select 		= "SELECT `d_id`,'0', '0000-00-00','0000-00-00',`d_maintenance`,'ongoing' FROM ".dmcms::getInstance()->db_pps.".t_device WHERE `d_id` NOT IN (SELECT `dc_d_id` FROM `db_dmcms`.`t_dieset_cycle`)";
			$result 		= dmcms::getInstance()->insert_selected_query($table,$array_fields,$select);
			// $queryshit 		= dmcms::getInstance()->query_insert_selected_query($table,$array_fields,$select);
			// $return['queryshit'] = $queryshit;
			$return['reload'] = 1;
		}
		else{
			$return['reload'] = 0;
		}

		$return['result']	= json_encode($result);
		echo json_encode($return);
	}

	function load_dieset_cycle(){
		require_once('query.php'); //OOP Path
		$id_device = $_POST['id_device'];
		$return = $_POST;
		$result = "";
		$array_fields = array('*','(SELECT SUM(s_count) FROM `t_shots` WHERE `t_shots`.`s_d_id`=`t_dieset_cycle`.`dc_d_id` AND `t_shots`.`s_status`="ongoing") AS ongoing_accumulated_shots','(SELECT SUM(s_count) FROM `t_shots` WHERE `t_shots`.`s_d_id`=`t_dieset_cycle`.`dc_d_id`) AS grand_total');
		$table 	   	= 't_dieset_cycle';
		$joins 	   	= '';
		$sql_where 	= "WHERE dc_d_id='$id_device' AND logdel=0";
		$sql_order 	= 'ORDER BY `dc_idd` DESC';
		$sql_limit 	= '';
		$result = dmcms::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($row = mysqli_fetch_array($result)){
			$return['dc_idd']					= $row['dc_idd'];
			$return['dc_d_id'] 					= $row['dc_d_id'];
			$return['dc_accumulated_shots'] 	= $row['ongoing_accumulated_shots'];
			$return['dc_start_date'] 			= $row['dc_start_date'];
			$return['dc_end_date']				= $row['dc_end_date'];
			$return['dc_maintenance_cycle'] 	= $row['dc_maintenance_cycle'];
			$return['dc_status']				= $row['dc_status'];
			$return['dc_mstatus'] 				= $row['dc_mstatus'];
			$return['grand_total'] 				= $row['grand_total'];
		}
		$return['result'] = json_encode($result);
		echo json_encode($return);
	}

	/* function for displaying main table*/
	function load_report_table(){
		require_once('query.php'); //OOP Path

		$id_para = $_POST['id_para'];
		$return  = $_POST;
		$result  = "";
		$array_fields = array('*','(SELECT SUM(s_count) FROM `t_shots` WHERE `t_shots`.`s_d_id`=`t_dieset_cycle`.`dc_d_id` AND `t_shots`.`s_status`="ongoing") AS ongoing_accumulated_shots');
		$table 	   	= 't_dieset_cycle';

		if($_POST['sr_m'] != '' || $_POST['sr_c'] != '' ||  $_POST['sr_d'] != '' || $_POST['sr_l'] != '' || $_POST['sr_l2'] != '')
		{
			$sql_where 	= '';
			$sr_m 		= '`dc_maintenance_cycle` LIKE "%'.$_POST['sr_m'].'%"';
			$sr_c		= '`d_cavty` LIKE "%'.$_POST['sr_c'].'%"';
			$sr_d 		= '`d_drawing` LIKE "%'.$_POST['sr_d'].'%"';
			
			$array_where = array($sr_m,$sr_c,$sr_d);

			if(isset($_POST['sr_l']) || $_POST['sr_l'] != ""){
				$date_range = explode(' - ',$_POST['sr_l']);
				$date_start = date("Y-m-d", strtotime($date_range[0]));
				$date_end 	= date("Y-m-d", strtotime($date_range[1]));
				$sr_l 			= '`dc_start_date` BETWEEN "'.$date_start.'" AND "'.$date_end.'"';
				$array_where 	= array($sr_m,$sr_c,$sr_d,$sr_l);
			}

		
			$joins 	   	= "INNER JOIN ".dmcms::getInstance()->db_pps.".t_device ON t_dieset_cycle.dc_d_id=t_device.d_id";
			if( count($array_where) != 0){
				$sql_where 	= "WHERE ".implode(' AND ', $array_where). " AND (dc_d_id='$id_para') AND `db_dmcms`.`t_dieset_cycle`.`logdel`=0" ;
			}else{
				
			}
		}else{
			/* place default where dito */
			$sql_where 	= "WHERE (dc_d_id='$id_para') AND `db_dmcms`.`t_dieset_cycle`.`logdel`=0";
			

			$joins 	   	= "INNER JOIN ".dmcms::getInstance()->db_pps.".t_device ON t_dieset_cycle.dc_d_id=t_device.d_id";
		}		
		$sql_order 			= 'ORDER BY dc_idd';
		$sql_limit 			= '';
		$result 			= dmcms::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$return['query'] 	= dmcms::getInstance()->select_query_test($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		
		$ctr = 0;
		while($row = mysqli_fetch_array($result)){
			if($row['dc_start_date']=='0000-00-00'){$start_date='-------------------';}
			else{$start_date=date("M d, Y", strtotime($row['dc_start_date']));}
			if($row['dc_end_date']=='0000-00-00'){$end_date='-------------------';}	
			else{$end_date=date("M d, Y", strtotime($row['dc_end_date']));}
			$return['dc_idd'][$ctr] 					= $row['dc_idd'];
			$return['dc_d_id'][$ctr] 					= $row['dc_d_id'];
			$return['dc_accumulated_shots'][$ctr] 		= $row['dc_accumulated_shots'];
			$return['dc_start_date'][$ctr] 				= $start_date;
			$return['dc_end_date'][$ctr] 				= $end_date;
			$return['dc_maintenance_cycle'][$ctr] 		= $row['dc_maintenance_cycle'];
			$return['d_cavty'][$ctr] 					= $row['d_cavty'];
			$return['d_drawing'][$ctr] 					= $row['d_drawing'];
			$return['dc_status'][$ctr] 					= $row['dc_status'];
			$ctr++;
		}
		$return['ctr']	  = $ctr;
		$return['result'] = json_encode($result);
		$return['test'] 	= $_POST;
		echo json_encode($return);
	}
	/* function for displaying devices table(shots)*/
	function load_ongoing_device(){
		require_once('query.php'); //OOP Path
		$id_device 			= $_POST['id_device'];
		
		$return = $_POST;
		$result = "";
		$array_fields = array('*','(SELECT SUM(s_count) FROM `t_shots` WHERE `t_shots`.`s_d_id`=`t_dieset_cycle`.`dc_d_id` AND `t_shots`.`s_status`="ongoing") AS ongoing_accumulated_shots');
		$table 	   	= 't_dieset_cycle';
		$joins 	   	= '';
		$sql_where 	= "WHERE dc_d_id='$id_device' AND dc_status='ongoing' AND `db_dmcms`.`t_dieset_cycle`.`logdel`=0";
		$sql_order 	= 'ORDER BY `dc_idd` DESC';
		$sql_limit 	= '';
		$result = dmcms::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($row = mysqli_fetch_array($result)){
			$start_date								= $row['dc_start_date'];
			$end_date								= $row['dc_end_date'];
			$return['dc_maintenance_cycle'] 		= $row['dc_maintenance_cycle'];
			if(($row['ongoing_accumulated_shots'] >= (.80*$row['dc_maintenance_cycle'])) && ($row['ongoing_accumulated_shots'] < (.9*$row['dc_maintenance_cycle']))){
				$return['dc_mstatus']  = 2;	
			}
			else if (($row['ongoing_accumulated_shots'] >= (.9*$row['dc_maintenance_cycle'])) && ($row['ongoing_accumulated_shots'] < $row['dc_maintenance_cycle'])) {
				$return['dc_mstatus']  = 3;		
			}
			else if ($row['ongoing_accumulated_shots'] >= $row['dc_maintenance_cycle']) {
				$return['dc_mstatus']  = 4;
			}
			else{
				$return['dc_mstatus']  = 1;
			}
		}
		if($end_date!='0000-00-00'){
			$end_date=date("Y-m-d",strtotime("+1 day",strtotime($end_date)));
		}
		else{
			$end_date=date("Y-m-d",strtotime("+1 day",strtotime(date("Y-m-d"))));
		}
		$start=date("Y-m-d",strtotime("-3 month",strtotime($end_date)));

		$return['result'] = json_encode($result);	

		$array_fields = array('*');
		$table 	   	= 't_shots';

		if($_POST['sr_mc'] != '' || $_POST['sr_o'] != '' || $_POST['sr_l'] != ''){
			$sql_where 	= '';
			$sr_mc 		= '`s_machine` LIKE "%'.$_POST['sr_mc'].'%"';
			$sr_o 		= '`s_operator` LIKE "%'.$_POST['sr_o'].'%"';			
			$array_where = array($sr_mc,$sr_o);


			if(isset($_POST['sr_l']) || $_POST['sr_l'] != ""){
				$date_range = explode(' - ',$_POST['sr_l']);
				$date_start = date("Y-m-d", strtotime($date_range[0]));
				$date_end 	= date("Y-m-d", strtotime($date_range[1]));
				$sr_l 			= '`s_date` BETWEEN "'.$date_start.'" AND "'.$date_end.'"';
				$array_where 	= array($sr_mc,$sr_o,$sr_l);
			}
			$joins 	   	= "INNER JOIN ".dmcms::getInstance()->db_pps.".t_device ON t_shots.`s_d_id`=t_device.`d_id`";
			if( count($array_where) != 0){
				$sql_where 	= "WHERE ".implode(' AND ', $array_where). " AND (s_d_id='$id_device') AND (logdel='0')" ;
			}else{
				
			}
		}else{
			/* place default where dito */
			$sql_where 	= "WHERE (s_d_id='$id_device') AND (logdel='0')";
			

			$joins 	   	= "INNER JOIN ".dmcms::getInstance()->db_pps.".t_device ON t_shots.`s_d_id`=t_device.`d_id`";
		}		
		
		$sql_order 			= 'ORDER BY s_date ASC, s_adate ASC';
		$sql_limit 			= '';
		$result 			= dmcms::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$script 			= dmcms::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);

		$ctr = 0;
		$accumulated_shots=0;
		while($row = mysqli_fetch_array($result)){
			$s_status = $row['s_status'];	
			$return['s_id'][$ctr] 					= $row['s_id'];
			$return['s_d_id'][$ctr] 				= $row['s_d_id'];
			$return['s_date'][$ctr] 				= date("M d, Y", strtotime($row['s_date']));
			$return['s_date_for_order'][$ctr] 		= date("Y-m-d", strtotime($row['s_date'])).'-'.date("Y-m-d H:i:s", strtotime($row['s_adate']));
			$return['s_count'][$ctr] 				= $row['s_count'];

			if($row['s_adate']==null){
				$return['s_adate'][$ctr] 				= date("M d, Y", strtotime($row['s_date']));
			}
			else{
				$return['s_adate'][$ctr] 				= date("M d, Y", strtotime($row['s_adate'])).'<i style="font-size:11px;color:#7b818c;"> &nbsp'.date("h:i A", strtotime($row['s_adate'])).'</i>';
			}

			$return['s_operator'][$ctr] 			= $row['s_operator'];
			$return['s_actual_cavty'][$ctr] 		= $row['s_actual_cavty'];
			$return['s_accum'][$ctr]				= $accumulated_shots;
			$return['s_machine'][$ctr] 				= $row['s_machine'];
			$return['s_remarks'][$ctr] 				= $row['s_remarks'];
			$return['s_status'][$ctr] 				= $row['s_status'];
			$return['pkid'][$ctr] 					= $row['pkid'];
			$return['d_id'][$ctr] 					= $row['d_id'];
			$return['d_name'][$ctr] 				= $row['d_name'];
			$return['d_cavty'][$ctr] 				= $row['d_cavty'];
			$return['d_drawing'][$ctr] 				= $row['d_drawing'];
			$return['d_accumulated_shots'][$ctr] 	= $row['d_accumulated_shots'];
			$return['d_last_updated'][$ctr] 		= $row['d_last_updated'];
			$return['d_maintenance_cycle'][$ctr] 	= $row['d_maintenance_cycle'];
			
			$ctr++;
			

		}
		$return['ctr']		= $ctr;
		$return['script']	= $script;
		$return['result']	= json_encode($result);
		$return['test'] 	= $_POST;
		echo json_encode($return);
	}

	/* function for displaying devices table(shots)*/
	function load_device_details(){
		require_once('query.php'); //OOP Path
		$id_device = $_POST['id_device'];

		$return = $_POST;
		$result = "";
		$array_fields = array('*');
		$table 	   	= 't_dieset_cycle';
		$joins 	   	= 'INNER JOIN '.dmcms::getInstance()->db_pps.'.t_device ON t_dieset_cycle.dc_d_id=t_device.d_id';
		$sql_where 	= "WHERE dc_d_id='$id_device' AND dc_status='ongoing' AND `db_dmcms`.`t_dieset_cycle`.`logdel`=0";
		$sql_order 	= '';
		$sql_limit 	= '';
		$result = dmcms::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$return['sd'] = dmcms::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr = 0;
		while($row = mysqli_fetch_array($result)){
			$return['d_id'][$ctr] 					= $row['d_id'];
			$return['d_name'][$ctr] 				= $row['d_name'];
			$return['d_cavty'][$ctr] 				= $row['d_cavty'];
			$return['d_drawing'][$ctr] 				= $row['d_drawing'];
			$return['d_accumulated_shots'][$ctr] 	= $row['d_accumulated_shots'];
			$return['d_last_updated'][$ctr] 		= $row['d_last_updated'];
			$return['d_maintenance_cycle'][$ctr] 	= $row['d_maintenance_cycle'];
			$return['dc_maintenance_cycle'][$ctr] 	= $row['dc_maintenance_cycle'];
			$ctr++;
		}
		$return['ctr']	  = $ctr;
		$return['result'] = json_encode($result);
		echo json_encode($return);
	}

	/* function for update_shots */
	function update_shots(){
		require_once('query.php'); //OOP Path
		$return = $_POST;
		$date_now					= date("Y-m-d H:i:s");
		$dc_id_device				= $_POST['dc_id_device'];
		$dc_ashots 					= $_POST['dc_ashots'];
		$dc_start_date_para			= $_POST['dc_start_date_para'];
		$dc_end_date_para 			= $_POST['dc_end_date_para'];
		$dc_maintenance_cycle_para 	= $_POST['dc_maintenance_cycle_para'];
		$user_name					= $_POST['user_name'];
		$dc_accumulated_shots 		= $return['txt_shots']+$dc_ashots;	

		$return 					= $_POST; 
		$result 					= "";
		$table   					= "t_shots"; //table name
		$array_fields	 			= array('s_id','s_d_id','s_date','s_count','s_machine','s_remarks','s_status','s_dc_id','s_actual_cavty','s_adate','s_operator','s_accum_shots'); //table fields
		$array_values 				= array('',$return['d_id'],date("Y-m-d", strtotime($return['txt_date'])),$return['txt_shots'],$return['txt_machine_no'],$return['txt_remarks'],'ongoing',$dc_id_device,$return['txt_Cavty'],$date_now,$user_name,$dc_accumulated_shots); //values to be saved
		$result 					= dmcms::getInstance()->insert_query($table,$array_fields,$array_values);
		$return["result"] 			= json_encode($result); //return the the serialize values
		$device_id 					= $return['d_id'];

		$return = $_POST;
		$result = "";
		$array_fields = array('*');
		$table 	   	= 't_device';
		$joins 	   	= '';
		$sql_where 	= "WHERE d_id='$device_id'";
		$sql_order 	= '';
		$sql_limit 	= '';
		$result = pps::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		while($row = mysqli_fetch_array($result)){
			$d_name 				= $row['d_name'];
		}

		$return = $_POST;
		$result = "";
		$array_fields = array('EmpNo','lastname','firstname','email_add');
		$table 	   	= 'db_hris.vw_EmpInfo_Rapid';
		$joins 	   	= '';
		$sql_where 	= "WHERE username='".$return['username']."'";
		$sql_order 	= '';
		$sql_limit 	= '';
		$result = SYS1_HRIS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($result->num_rows == 0) {
			$array_fields2 = array('empno','name');
			$table2 	   	= 'db_rapid.tbl_useraccounts';
			$joins2 	   	= '';
			$sql_where2 	= "WHERE username='".$return['username']."'";
			$sql_order2 	= '';
			$sql_limit2 	= '';
			$result2 = dmcms::getInstance()->select_query($array_fields2,$table2,$joins2,$sql_where2,$sql_order2,$sql_limit2);
			if($row = mysqli_fetch_array($result2)){
				$return['EmpNo']					= $row['empno'];
				$return['emp_name']					= $row['name'];
				$email 				 				= $row['email_addd'];
			}
		} 
		else {
			if($row = mysqli_fetch_array($result)){
				$return['EmpNo']					= $row['EmpNo'];
				$return['emp_name']					= $row['firstname'] . ' ' . $row['lastname'];
				$email  							= $row['email_addd'];
			}
		}

		$return = $_POST;
		$result = "";
		$array_fields = array('username');
		$table 	   	= 'db_rapid.vw_module_per_user';
		$joins 	   	= '';
		$sql_where 	= 'WHERE moduleName like "%DMCMS - Maintenance In-charge%" OR moduleName like "%DMCMS - Section Head%"';
		$sql_order 	= '';
		$sql_limit 	= '';
		$result = dmcms::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$email_query = dmcms::getInstance()->select_query_test($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr = 0;
			while($row = mysqli_fetch_array($result)){
				$return['username'][$ctr] 				= $row['username'];
				$ctr++;
			}
	
		// $return['username_check'] = $return['username'];
		// $return['email_query'] = $email_query;

		$result = "";
		$array_fields = array('email_add');
		$table 	   	= 'db_hris.vw_EmpInfo_Rapid';
		$joins 	   	= '';
		$sql_where 	= "WHERE username IN ('".implode("','",$return['username'])."')";
		$sql_order 	= '';
		$sql_limit 	= '';
		$result = SYS1_HRIS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$script_email = SYS1_HRIS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr = 0;
			while($row = mysqli_fetch_array($result)){
				$return['email_add'][$ctr] 				= $row['email_add'];
				$ctr++;
			}
		// $return['email_add_check'] = $return['email_add'];
		// $return['email_script'] = $script_email;

		// if(($dc_accumulated_shots >= (.80*$dc_maintenance_cycle_para)) && ($dc_accumulated_shots < (.9*$dc_maintenance_cycle_para))){
		// 	$dc_mstatus = 2;
		// 	$return['action_alert']='beige';
		// 	require_once('send_email.php');
		// 	$php_mailer = new email();
		// 	$to 		= implode(",",$return['email_add']);
		// 	// $to 		= 'ojtsoftware@pricon.ph';
		// 	$from 		= 'issinfoservice@pricon.ph';
		// 	$cc			= '';
		// 	$subject 	= 'Device '.$d_name.' reached the 80%';
		// 	$body 		= 'The device '.$d_name.' has reached the 80% of the maintenance cycle. Encoded by: '.$user_name;
		// 	$attachment = '';
        //           $msg = $php_mailer->send_email($to, $from, $cc, $subject, $body,$attachment);			
		// }
		// else 

		if (($dc_accumulated_shots >= (.9*$dc_maintenance_cycle_para)) && ($dc_accumulated_shots < $dc_maintenance_cycle_para)) {
			$dc_mstatus = 3;
			$return['action_alert']='yellow';
			require_once('send_email.php');
			$php_mailer = new email();
			// $to 		= 'ojtsoftware@pricon.ph';
			$to 		= implode(",",$return['email_add']);
			$from 		= 'issinfoservice@pricon.ph';
			$cc			= '';
			$subject 	= 'Device '.$d_name.' reached the 90%';
			$body 		= 'The device '.$d_name.' has reached the 90% of the maintenance cycle. Encoded by: '.$user_name;
			$attachment = '';
            $msg = $php_mailer->send_email($to, $from, $cc, $subject, $body,$attachment);			
		}
		else if ($dc_accumulated_shots >= $dc_maintenance_cycle_para) {
			$dc_mstatus = 4;
			$return['action_alert']='red';
			require_once('send_email.php');
			$php_mailer = new email();
			// $to 		= 'ojtsoftware@pricon.ph';
			$to 		= implode(",",$return['email_add']);
			$from 		= 'issinfoservice@pricon.ph';
			$cc			= '';
			$subject 	= 'Device '.$d_name.' reached the 100%'; 
			$body 		= 'The device '.$d_name.' has reached the 100% of the maintenance cycle. Encoded by: '.$user_name;
			$attachment = '';
            $msg = $php_mailer->send_email($to, $from, $cc, $subject, $body,$attachment);	
		}
		else{
			$dc_mstatus = 1;
			$return['action_alert']='none';
		}

		$table   					= "t_dieset_cycle"; //table name
		if($dc_start_date_para 		== '0000-00-00' || $dc_start_date_para = ''){			
			$array_fields 			= array('dc_accumulated_shots','dc_start_date','dc_end_date','dc_mstatus','dc_aupdate');
			$array_values 			= array($dc_accumulated_shots,date("Y-m-d", strtotime($return['txt_date'])),date("Y-m-d", strtotime($return['txt_date'])),$dc_mstatus,$date_now);
		
		}
		else{
			$array_fields 			= array('dc_accumulated_shots','dc_end_date','dc_mstatus','dc_aupdate');
			$array_values 			= array($dc_accumulated_shots,date("Y-m-d", strtotime($return['txt_date'])),$dc_mstatus,$date_now);
		}
		$pkid 						= $return['d_id'];
		$result 					= dmcms::getInstance()->update_query_ongoing($table,$array_fields,$array_values,$pkid);
		$return["result"] 			= json_encode($result); //return the the serialize values


		echo json_encode($return);
	}

	/* function for reset_shots */
	function reset_shots(){
		// load_users();
		require_once('query.php'); //OOP Path
		$date_now				= date("Y-m-d H:i:s");
		$dc_id_device			= $_POST['dc_id_device'];
		$dc_ashots 				= $_POST['dc_ashots'];
		$dc_start_date_para		= $_POST['dc_start_date_para'];
		$dc_end_date_para 		= $_POST['dc_end_date_para'];
		$d_id_para				= $_POST['d_id_para'];
		$d_mcycle_para			= $_POST['d_mcycle_para'];		
		$user_name				= $_POST['user_name'];
		$username				= $_POST['username'];
		$return 				= $_POST; 
		
		$result = "";
		$array_fields = array('*');
		$table 	   	= 't_device';
		$joins 	   	= '';
		$sql_where 	= "WHERE d_id='$d_id_para'";
		$sql_order 	= '';
		$sql_limit 	= '';
		$result = pps::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($row = mysqli_fetch_array($result)){
			$d_name 				= $row['d_name'];
			$true_mcycle 			= $row['d_maintenance'];
		}

		$result 				= "";
		$table   				= "t_shots"; //table name
		$array_fields	 		= array('s_id','s_d_id','s_date','s_count','s_machine','s_remarks','s_status','s_dc_id','s_actual_cavty','s_adate','s_operator'); //table fields
		$array_values 			= array('',$d_id_para,date("Y-m-d"),'','1',$return['txt_remarks'],'reset',$dc_id_device,'',$date_now,$user_name); //values to be saved
		$result 				= dmcms::getInstance()->insert_query($table,$array_fields,$array_values);

		$table   				= "t_dieset_cycle"; //table name
		$array_fields	 		= array('dc_status'); //table fields
		$array_values 			= array("finished"); //values to be saved
		$pkid 					= $d_id_para;
		$result 				= dmcms::getInstance()->update_query_reset_tbl_dieset($table,$array_fields,$array_values,$pkid);

		$table   				= "t_shots"; //table name
		$array_fields	 		= array('s_status'); //table fields
		$array_values 			= array("finished"); //values to be saved
		$pkid 					= $d_id_para;
		$result 				= dmcms::getInstance()->update_query_reset_tbl_shots($table,$array_fields,$array_values,$pkid);

		$table   				= "t_dieset_cycle"; //table name
		$array_fields	 		= array('dc_idd','dc_d_id','dc_accumulated_shots','dc_start_date','dc_end_date','dc_maintenance_cycle','dc_status'); //table fields
		$array_values 			= array('',$d_id_para,'','','',$true_mcycle,"ongoing"); //values to be saved
		$result 				= dmcms::getInstance()->insert_query($table,$array_fields,$array_values);
		$device_id 				= $return['d_id'];

	
		$result = "";
		$array_fields = array('username');
		$table 	   	= 'db_rapid.vw_module_per_user';
		$joins 	   	= '';
		$sql_where 	= 'WHERE moduleName LIKE "%DMCMS - Section Head%"';
		$sql_order 	= '';
		$sql_limit 	= '';
		// $result = dmcms::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$result = dmcms::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		// $return['sd'] = dmcms::getInstance()->select_query_test($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr = 0;
		while($row = mysqli_fetch_array($result)){
			$sect_head_usernames[$ctr] = $row['username'];
			$ctr++;
		}
	
		$result = "";
		$array_fields = array('email_add');
		$table 	   	= 'db_hris.vw_EmpInfo_Rapid';
		$joins 	   	= '';
		$sql_where 	= "WHERE username IN ('".implode("','",$sect_head_usernames)."')";
		$sql_order 	= '';
		$sql_limit 	= '';
		$result = SYS1_HRIS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		// $return['sa'] = SYS1_HRIS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr = 0;
		while($row = mysqli_fetch_array($result)){
			$sect_head_emails[$ctr] 				= $row['email_add'];
			$ctr++;
		}

		$result = "";
		$array_fields = array('email_add');
		$table 	   	= 'db_hris.vw_EmpInfo_Rapid';
		$joins 	   	= '';
		$sql_where 	= "WHERE username='".$username."'";
		$sql_order 	= '';
		$sql_limit 	= '';
		$result = SYS1_HRIS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		// $return['sb'] = SYS1_HRIS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($row = mysqli_fetch_array($result)){
			$from_email	= $row['email_add'];			
		}	

		require_once('send_email.php');
		$php_mailer = new email();
		// $to 		= 'ojtsoftware@pricon.ph';
		$to 		= implode(",",$sect_head_emails);
		$from 		= $from_email;
		$cc 		= '';		
		$subject 	= 'Maintenance Cycle Notification'; 
		$body 		= 'The device ' .$d_name.'\'s maintenance cycle has been reset. Reset by: '.$user_name.'.';
		$attachment = '';
        $msg = $php_mailer->send_email($to, $from, $cc, $subject, $body,$attachment);	

		$return['result'] = json_encode($result);
		echo json_encode($return);
	}

	function load_users(){
		require_once('query.php');
		// Get user name and information to HRIS database. If not found, get to Rapid database
		$return = $_POST;
		$result = "";
		$array_fields = array('EmpNo','lastname','firstname','email_add');
		$table 	   	= 'db_hris.vw_EmpInfo_Rapid';
		$joins 	   	= '';
		$sql_where 	= "WHERE username='".$return['username']."'";
		$sql_order 	= '';
		$sql_limit 	= '';
		$result = SYS1_HRIS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($result->num_rows == 0) {
			$array_fields2 = array('empno','name');
			$table2 	   	= 'db_rapid.tbl_useraccounts';
			$joins2 	   	= '';
			$sql_where2 	= "WHERE username='".$return['username']."'";
			$sql_order2 	= '';
			$sql_limit2 	= '';
			$result2 = dmcms::getInstance()->select_query($array_fields2,$table2,$joins2,$sql_where2,$sql_order2,$sql_limit2);
			if($row = mysqli_fetch_array($result2)){
				$return['EmpNo']					= $row['empno'];
				$return['emp_name']					= $row['name'];
				$return['email_add'] 				= $row['email_add'];
			}
		} else {
			if($row = mysqli_fetch_array($result)){
				$return['EmpNo']					= $row['EmpNo'];
				$return['emp_name']					= $row['firstname'] . ' ' . $row['lastname'];
				$return['email_add'] 				= $row['email_add'];
			}
		}

		$return['result'] = json_encode($result);
		echo json_encode($return);


		/*$return = $_POST;
		$result = "";
		$array_fields = array('EmpNo','lastname','firstname','email_add');
		$table 	   	= 'db_hris.vw_EmpInfo_Rapid';
		$joins 	   	= '';
		$sql_where 	= "WHERE username='".$return['username']."'";
		$sql_order 	= '';
		$sql_limit 	= '';
		$result = SYS1_HRIS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$script = SYS1_HRIS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);

		if($row = mysqli_fetch_array($result)){
			$return['EmpNo']					= $row['EmpNo'];
			$return['lastname']					= $row['lastname'];
			$return['firstname'] 				= $row['firstname'];
			$return['email_add'] 				= $row['email_add'];
			$email 								= $row['email_add'];
		}

		$return['script'] = $script;
		$return['result'] = json_encode($result);
		echo json_encode($return);*/

	}
?>