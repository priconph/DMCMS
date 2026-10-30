<?php
//Set Class name here, it's a good practice to Capitalize the first letter, I've just read it somewhere on the internet... so its up to you if will follow...
class dmcms extends mysqli 
{
	private static $instance = null;
	
	//db connection config variables
	private $user = "root";	//Username
	private $pass = "r@p1d0nl1n3"; //Password
	private $dbName = "db_dmcms"; //db_name Note: if you are going to use multiple database just place one here.. use sql to call the other one just in case, for example : database2.tablename
	public  $db_pps = "db_pps";
    public  $db_rapid = "db_rapid";
	private $dbHost = "localhost"; //host
	
	//NO NEED TO CHANGE HERE 
	public static function getInstance() {
		if(!self::$instance instanceof self) 
		{
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function __clone()
	{
		trigger_error('Clone is not allowed.',E_USER_ERROR);
	}
	
	public function __wakeup()
	{
		trigger_error('Deserializing is not allowed.',E_USER_ERROR);
	}
	
	private function __construct() {
		parent::__construct($this->dbHost, $this->user, $this->pass, $this->dbName);
		if(mysqli_connect_error())
		{
			exit('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
		}
		parent::set_charset('utf-8');
	}

	////////////////////////////////////UP TO HERE////////////////////////////////////////
	
	////////////////////////////////////PUBLIC FUNCTIONS STARTS HERE//////////////////////////////////////////////

	public function select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit){
		$query = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $array_fields) . " FROM {$table} {$joins} {$sql_where} {$sql_order} {$sql_limit};";
		return $this->query($query);
	}
	public function select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit){
		$query = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $array_fields) . " FROM {$table} {$joins} {$sql_where} {$sql_order} {$sql_limit};";
		return ($query);
	}
	
	public function select_query_test($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit){
		$query = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $array_fields) . " FROM {$table} {$joins} {$sql_where} {$sql_order} {$sql_limit};";
		return $query;
		// return $this->query($query);
	}

	public function select_sum_query($field,$table,$joins,$sql_where,$as){
		$query = "(SELECT SUM(".$field.") FROM `".$table."`) AS ".$as;
		return $this->query($query);		
	}
		
	public function insert_query($table,$array_fields,$array_values){
		$new_values = $this->add_quotations($array_values); 
		$query 		= "INSERT INTO {$table} (". implode(", ", $array_fields).") VALUES (". implode(",", $new_values) .");";
		$this->query($query);
		// $this->save_logs('1',$query);
		return "New record has been saved";
	}

	public function insert_selected_query($table,$array_fields,$select){
		$query 		= "INSERT INTO {$table} (". implode(", ", $array_fields).") ".$select.";";
		$result = $this->query($query);
		return $result;
	}

	public function query_insert_selected_query($table,$array_fields,$select){
		$query 		= "INSERT INTO {$table} (". implode(", ", $array_fields).") ".$select.";";
		return $query;
	}

	public function update_query($table,$array_fields,$array_values,$pkid){
		$update_array =  $this->create_update_param($array_fields,$array_values);
		$query 		  = "UPDATE {$table} SET ". implode(",", $update_array) ."WHERE pkid='{$pkid}';";
		$this->query($query);
		// $this->save_logs('2',$query);
		return "Record has been updated";
	}

	public function update_dmcms($table,$array_fields,$array_values,$id,$pkid){
		$update_array =  $this->create_update_param($array_fields,$array_values);
		$query 		  = "UPDATE {$table} SET ". implode(",", $update_array) ."WHERE ".$id."='{$pkid}';";
		$this->query($query);
		// $this->save_logs('2',$query);
		return "Record has been updated";
	}

	public function update_dmcms_query($table,$array_fields,$array_values,$id,$pkid){
		$update_array =  $this->create_update_param($array_fields,$array_values);
		$query 		  = "UPDATE {$table} SET ". implode(",", $update_array) ."WHERE ".$id."='{$pkid}';";
		// $this->query($query);
		// $this->save_logs('2',$query);
		return $query;
	}

	public function update_query_real_cycle($table,$array_fields,$array_values,$pkid){
		$update_array =  $this->create_update_param($array_fields,$array_values);
		$query 		  = "UPDATE {$table} SET ". implode(",", $update_array) ."WHERE dc_d_id='{$pkid}' AND dc_status='ongoing';";
		$this->query($query);
		// $this->save_logs('2',$query);
		return "Record has been updated";
	}

	public function update_query_real_cycle_query($table,$array_fields,$array_values,$pkid){
		$update_array =  $this->create_update_param($array_fields,$array_values);
		$query 		  = "UPDATE {$table} SET ". implode(",", $update_array) ."WHERE dc_d_id='{$pkid}' AND dc_status='ongoing';";
		
		return $query;
	}

	public function update_query_dcycle($table,$array_fields,$array_values,$pkid){
		$update_array =  $this->create_update_param($array_fields,$array_values);
		$query 		  = "UPDATE {$table} SET ". implode(",", $update_array) ."WHERE d_id='{$pkid}';";
		$this->query($query);
		// $this->save_logs('2',$query);
		return "Record has been updated";
	}


	public function update_query_dccycle($table,$array_fields,$array_values,$pkid){
		$update_array =  $this->create_update_param($array_fields,$array_values);
		$query 		  = "UPDATE {$table} SET ". implode(",", $update_array) ."WHERE dc_idd='{$pkid}';";
		$this->query($query);
		// $this->save_logs('2',$query);
		return "Record has been updated";
	}

	public function update_query_ongoing($table,$array_fields,$array_values,$pkid){
		$update_array =  $this->create_update_param($array_fields,$array_values);
		$query 		  = "UPDATE {$table} SET ". implode(",", $update_array) ."WHERE dc_d_id='{$pkid}' AND dc_status='ongoing';";
		$this->query($query);
		// $this->save_logs('2',$query);
		return "Record has been updated";
	}

	public function update_query_save_other($table,$array_fields,$array_values,$pkid){
		$update_array =  $this->create_update_param($array_fields,$array_values);
		$query 		  = "UPDATE {$table} SET ". implode(",", $update_array) ."WHERE dc_idd='{$pkid}';";
		$this->query($query);
		// $this->save_logs('2',$query);
		return "Record has been updated";
	}

	public function update_query_reset_tbl_dieset($table,$array_fields,$array_values,$pkid){
		$update_array =  $this->create_update_param($array_fields,$array_values);
		$query 		  = "UPDATE {$table} SET ". implode(",", $update_array) ."WHERE dc_d_id='{$pkid}' AND dc_status='ongoing';";
		$this->query($query);
		// $this->save_logs('2',$query);
		return "Record has been updated";
	}

		public function update_query_reset_tbl_shots($table,$array_fields,$array_values,$pkid){
		$update_array =  $this->create_update_param($array_fields,$array_values);
		$query 		  = "UPDATE {$table} SET ". implode(",", $update_array) ."WHERE s_d_id='{$pkid}' AND s_status='ongoing';";
		$this->query($query);
		// $this->save_logs('2',$query);
		return "Record has been updated";
	}

	public function delete_query($table,$pkid){
		$query = "DELETE FROM {$table} WHERE `pkid` = '{$pkid}';";
		$this->query($query);
		// $this->save_logs('3',$query);
		return "Record has been deleted";
	}
	
	public function add_quotations($array_values){
		$array_values_r = array();
		for($i=0;$i<count($array_values);$i++){
			if(isset($array_values[$i]) || $array_values[$i] != ''){
				$array_values[$i] = $this->real_escape_string($array_values[$i]);
				$array_values_r[$i] = "'".$array_values[$i]."'";
			}else{
				$array_values_r[$i] = '';
			}
		}
		return $array_values_r;
	}

	public function create_update_param($array_fields,$array_values){
		$array_update = array();
		for($i=0;$i<count($array_fields);$i++){
			$array_update[$i] = "`".$array_fields[$i]."` = "."'".$array_values[$i]."' ";
		}
		return $array_update;
	}
	
	// public function save_logs($type,$query){
	// 	$date_today = date('Y-m-d H:i:s');
	// 	$new_query = str_replace("'",'"',$query);
	// 	$logs = "INSERT INTO `tbl_logs` (`type_of_query`,`query`,`date`) VALUES('".$type."','".$new_query."','".$date_today."');";
	// 	$this->query($logs);
	// }
	
		public function check_unset($variable){
		if(!isset($variable)){
			$new_variable = '';
		}else{
			$new_variable = $variable;
		}
		return $new_variable;
	}
	
	public function check_if_array($array){
		if(!is_array($array)){
			$new_array = array($array);
		}else{
			$new_array = $array;
		}
		return $new_array;
	}
	
}

class sanda_dmcms extends mysqli 
{
	private static $instance = null;
	
	//db connection config variables
	private $user = "root";	//Username
	private $pass = "r@p1d0nl1n3"; //Password
	private $dbName = "db_sanda_dmcms"; //db_name Note: if you are going to use multiple database just place one here.. use sql to call the other one just in case, for example : database2.tablename
	public  $db_pps = "db_pps";
    public  $db_rapid = "db_rapid";
	private $dbHost = "localhost"; //host
	
	//NO NEED TO CHANGE HERE 
	public static function getInstance() {
		if(!self::$instance instanceof self) 
		{
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function __clone()
	{
		trigger_error('Clone is not allowed.',E_USER_ERROR);
	}
	
	public function __wakeup()
	{
		trigger_error('Deserializing is not allowed.',E_USER_ERROR);
	}
	
	private function __construct() {
		parent::__construct($this->dbHost, $this->user, $this->pass, $this->dbName);
		if(mysqli_connect_error())
		{
			exit('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
		}
		parent::set_charset('utf-8');
	}

	////////////////////////////////////UP TO HERE////////////////////////////////////////
	
	////////////////////////////////////PUBLIC FUNCTIONS STARTS HERE//////////////////////////////////////////////

	public function select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit){
		$query = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $array_fields) . " FROM {$table} {$joins} {$sql_where} {$sql_order} {$sql_limit};";
		return $this->query($query);
	}
	public function select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit){
		$query = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $array_fields) . " FROM {$table} {$joins} {$sql_where} {$sql_order} {$sql_limit};";
		return ($query);
	}
	
	public function select_query_test($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit){
		$query = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $array_fields) . " FROM {$table} {$joins} {$sql_where} {$sql_order} {$sql_limit};";
		return $query;
		// return $this->query($query);
	}

	public function select_sum_query($field,$table,$joins,$sql_where,$as){
		$query = "(SELECT SUM(".$field.") FROM `".$table."`) AS ".$as;
		return $this->query($query);		
	}
		
	public function insert_query($table,$array_fields,$array_values){
		$new_values = $this->add_quotations($array_values); 
		$query 		= "INSERT INTO {$table} (". implode(", ", $array_fields).") VALUES (". implode(",", $new_values) .");";
		$this->query($query);
		// $this->save_logs('1',$query);
		return "New record has been saved";
	}

	public function insert_selected_query($table,$array_fields,$select){
		$new_values = $this->add_quotations($array_values); 
		$query 		= "INSERT INTO {$table} (". implode(", ", $array_fields).") ".$select.";";
		$this->query($query);
	}

	public function query_insert_selected_query($table,$array_fields,$select){
		$new_values = $this->add_quotations($array_values); 
		$query 		= "INSERT INTO {$table} (". implode(", ", $array_fields).") ".$select.";";
		return $query;
	}

	public function update_query($table,$array_fields,$array_values,$pkid){
		$update_array =  $this->create_update_param($array_fields,$array_values);
		$query 		  = "UPDATE {$table} SET ". implode(",", $update_array) ."WHERE pkid='{$pkid}';";
		$this->query($query);
		// $this->save_logs('2',$query);
		return "Record has been updated";
	}

	public function update_dmcms($table,$array_fields,$array_values,$id,$pkid){
		$update_array =  $this->create_update_param($array_fields,$array_values);
		$query 		  = "UPDATE {$table} SET ". implode(",", $update_array) ."WHERE ".$id."='{$pkid}';";
		$this->query($query);
		// $this->save_logs('2',$query);
		return "Record has been updated";
	}

	public function update_dmcms_query($table,$array_fields,$array_values,$id,$pkid){
		$update_array =  $this->create_update_param($array_fields,$array_values);
		$query 		  = "UPDATE {$table} SET ". implode(",", $update_array) ."WHERE ".$id."='{$pkid}';";
		// $this->query($query);
		// $this->save_logs('2',$query);
		return $query;
	}

	public function update_query_real_cycle($table,$array_fields,$array_values,$pkid){
		$update_array =  $this->create_update_param($array_fields,$array_values);
		$query 		  = "UPDATE {$table} SET ". implode(",", $update_array) ."WHERE dc_d_id='{$pkid}' AND dc_status='ongoing';";
		$this->query($query);
		// $this->save_logs('2',$query);
		return "Record has been updated";
	}

	public function update_query_real_cycle_query($table,$array_fields,$array_values,$pkid){
		$update_array =  $this->create_update_param($array_fields,$array_values);
		$query 		  = "UPDATE {$table} SET ". implode(",", $update_array) ."WHERE dc_d_id='{$pkid}' AND dc_status='ongoing';";
		
		return $query;
	}

	public function update_query_dcycle($table,$array_fields,$array_values,$pkid){
		$update_array =  $this->create_update_param($array_fields,$array_values);
		$query 		  = "UPDATE {$table} SET ". implode(",", $update_array) ."WHERE d_id='{$pkid}';";
		$this->query($query);
		// $this->save_logs('2',$query);
		return "Record has been updated";
	}


	public function update_query_dccycle($table,$array_fields,$array_values,$pkid){
		$update_array =  $this->create_update_param($array_fields,$array_values);
		$query 		  = "UPDATE {$table} SET ". implode(",", $update_array) ."WHERE dc_idd='{$pkid}';";
		$this->query($query);
		// $this->save_logs('2',$query);
		return "Record has been updated";
	}

	public function update_query_ongoing($table,$array_fields,$array_values,$pkid){
		$update_array =  $this->create_update_param($array_fields,$array_values);
		$query 		  = "UPDATE {$table} SET ". implode(",", $update_array) ."WHERE dc_d_id='{$pkid}' AND dc_status='ongoing';";
		$this->query($query);
		// $this->save_logs('2',$query);
		return "Record has been updated";
	}

	public function update_query_save_other($table,$array_fields,$array_values,$pkid){
		$update_array =  $this->create_update_param($array_fields,$array_values);
		$query 		  = "UPDATE {$table} SET ". implode(",", $update_array) ."WHERE dc_idd='{$pkid}';";
		$this->query($query);
		// $this->save_logs('2',$query);
		return "Record has been updated";
	}

	public function update_query_reset_tbl_dieset($table,$array_fields,$array_values,$pkid){
		$update_array =  $this->create_update_param($array_fields,$array_values);
		$query 		  = "UPDATE {$table} SET ". implode(",", $update_array) ."WHERE dc_d_id='{$pkid}' AND dc_status='ongoing';";
		$this->query($query);
		// $this->save_logs('2',$query);
		return "Record has been updated";
	}

		public function update_query_reset_tbl_shots($table,$array_fields,$array_values,$pkid){
		$update_array =  $this->create_update_param($array_fields,$array_values);
		$query 		  = "UPDATE {$table} SET ". implode(",", $update_array) ."WHERE s_d_id='{$pkid}' AND s_status='ongoing';";
		$this->query($query);
		// $this->save_logs('2',$query);
		return "Record has been updated";
	}

	public function delete_query($table,$pkid){
		$query = "DELETE FROM {$table} WHERE `pkid` = '{$pkid}';";
		$this->query($query);
		// $this->save_logs('3',$query);
		return "Record has been deleted";
	}
	
	public function add_quotations($array_values){
		$array_values_r = array();
		for($i=0;$i<count($array_values);$i++){
			if(isset($array_values[$i]) || $array_values[$i] != ''){
				$array_values[$i] = $this->real_escape_string($array_values[$i]);
				$array_values_r[$i] = "'".$array_values[$i]."'";
			}else{
				$array_values_r[$i] = '';
			}
		}
		return $array_values_r;
	}

	public function create_update_param($array_fields,$array_values){
		$array_update = array();
		for($i=0;$i<count($array_fields);$i++){
			$array_update[$i] = "`".$array_fields[$i]."` = "."'".$array_values[$i]."' ";
		}
		return $array_update;
	}
	
	// public function save_logs($type,$query){
	// 	$date_today = date('Y-m-d H:i:s');
	// 	$new_query = str_replace("'",'"',$query);
	// 	$logs = "INSERT INTO `tbl_logs` (`type_of_query`,`query`,`date`) VALUES('".$type."','".$new_query."','".$date_today."');";
	// 	$this->query($logs);
	// }
	
		public function check_unset($variable){
		if(!isset($variable)){
			$new_variable = '';
		}else{
			$new_variable = $variable;
		}
		return $new_variable;
	}
	
	public function check_if_array($array){
		if(!is_array($array)){
			$new_array = array($array);
		}else{
			$new_array = $array;
		}
		return $new_array;
	}
	
}
//THIS CLASS CONNECTS INTO THE SystemOne Server	
class SYS1_HRIS extends mysqli
                {
                    private static $instance = null;
                    
                    //db connection config variables
                    private $user = "root";
                    private $pass = "newd3v";
                    private $dbName = "db_hris";
                    private $dbHost = "192.168.3.240";

                    public static function getInstance() {
                                    if(!self::$instance instanceof self) 
                                    {
                                                    self::$instance = new self;
                                    }
                                    return self::$instance;
                    }

                    public function __clone()
                    {
                                    trigger_error('Clone is not allowed.',E_USER_ERROR);
                    }
                    
                    public function __wakeup()
                    {
                                    trigger_error('Deserializing is not allowed.',E_USER_ERROR);
                    }
                    
                    private function __construct() {
                                    parent::__construct($this->dbHost, $this->user, $this->pass, $this->dbName);
                                    if(mysqli_connect_error())
                                    {
                                                    exit('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
                                    }
                                    parent::set_charset('utf-8');
                    }

                    ////////////////////////////////////PUBLIC FUNCTIONS STARTS HERE//////////////////////////////////////////////
                    public function select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit){
                                    $query = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $array_fields) . " FROM {$table} {$joins} {$sql_where} {$sql_order} {$sql_limit};";
                                    return $this->query($query);
                    }

					public function select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit){
                                    $query = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $array_fields) . " FROM {$table} {$joins} {$sql_where} {$sql_order} {$sql_limit};";
                                    return $query;
                    }
    }

class pps extends mysqli
				{
					private static $instance = null;
                    
                    //db connection config variables
                    private $user = "root";
                    private $pass = "r@p1d0nl1n3";
                    private $dbName = "db_pps";
                    public  $db_dmcms = "db_dmcms";
                    private $dbHost = "localhost";

                    public static function getInstance() {
                                    if(!self::$instance instanceof self) 
                                    {
                                                    self::$instance = new self;
                                    }
                                    return self::$instance;
                    }

                    public function __clone()
                    {
                                    trigger_error('Clone is not allowed.',E_USER_ERROR);
                    }
                    
                    public function __wakeup()
                    {
                                    trigger_error('Deserializing is not allowed.',E_USER_ERROR);
                    }
                    
                    private function __construct() {
                                    parent::__construct($this->dbHost, $this->user, $this->pass, $this->dbName);
                                    if(mysqli_connect_error())
                                    {
                                                    exit('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
                                    }
                                    parent::set_charset('utf-8');
                    }

                    ////////////////////////////////////PUBLIC FUNCTIONS STARTS HERE//////////////////////////////////////////////
                    public function select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit){
                                    $query = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $array_fields) . " FROM {$table} {$joins} {$sql_where} {$sql_order} {$sql_limit};";
                                    return $this->query($query);
                    }

					public function select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit){
                                    $query = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $array_fields) . " FROM {$table} {$joins} {$sql_where} {$sql_order} {$sql_limit};";
                                    return $query;
                    }
	}

// class rapid extends mysqli
// 				{
// 					private static $instance = null;
                    
//                     //db connection config variables
//                     private $user = "ojt";
//                     private $pass = "ojt1234";
//                     private $dbName = "db_rapid";
//                     private $dbHost = "localhost";

//                     public static function getInstance() {
//                                     if(!self::$instance instanceof self) 
//                                     {
//                                                     self::$instance = new self;
//                                     }
//                                     return self::$instance;
//                     }

//                     public function __clone()
//                     {
//                                     trigger_error('Clone is not allowed.',E_USER_ERROR);
//                     }
                    
//                     public function __wakeup()
//                     {
//                                     trigger_error('Deserializing is not allowed.',E_USER_ERROR);
//                     }
                    
//                     private function __construct() {
//                                     parent::__construct($this->dbHost, $this->user, $this->pass, $this->dbName);
//                                     if(mysqli_connect_error())
//                                     {
//                                                     exit('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
//                                     }
//                                     parent::set_charset('utf-8');
//                     }

//                     ////////////////////////////////////PUBLIC FUNCTIONS STARTS HERE//////////////////////////////////////////////
//                     public function select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit){
//                                     $query = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $array_fields) . " FROM {$table} {$joins} {$sql_where} {$sql_order} {$sql_limit};";
//                                     return $this->query($query);
//                     }

// 					public function select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit){
//                                     $query = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $array_fields) . " FROM {$table} {$joins} {$sql_where} {$sql_order} {$sql_limit};";
//                                     return $this->query($query);
//                     }
// 	}	
?>