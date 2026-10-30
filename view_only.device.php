<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$d_id_identifier=$_GET['device_id'];
?>
<style>
	#main_container{
		margin:auto;
		width:90%;
	}
	#options{
		margin-top: 7%;
	}
	#d_t_device{
		margin-top: 2%;
	}
	#details_cont{
		margin-top: 6%;
		margin-bottom: 2%;		
	}
	table.dataTable thead th, table.dataTable tbody tr td{ 
		border-bottom: none!important; 
	}
</style>
<input type="hidden" id="hd_username" value="<?php echo $username; ?>">
<!-- <div class="box-header with-border">
	<h3 class="box-title">Mold Maintenance Cycle Table</h3>
	<span class="small pull-right">Date Today: <b>&nbsp<?php echo date("F d, Y"); ?></b></span>
</div> -->
<br>
<div class="col-md-12">
	<p id="id_paragraph" style="position:absolute;" hidden><?php echo $d_id_identifier;?></p>
	<div id="dc_id_identifier"></div>
	<div class="col-md-12">
		<a href="view_only_index.php?page=view_only.home.php" style="position:absolute;left:1%;"><i class="glyphicon glyphicon-chevron-left"></i> Back</a>	
		<div class="col-md-4" style="margin-left:3%;">
			<div class="box box-primary" style="overflow-y: hidden;overflow-x: hidden;min-height:111px;max-height:161px;">
				<div class="box-body box-profile" style="padding-top:2%;padding-left:10%">
						<div id="container_details"></div>
				</div>
			</div>
		</div>
		<div class="pull-right" style="margin-top:2%;width:26%;height:4%;margin-right:3%;">
			<span><span style="margin-left:2%;background-color:#83cdef;width:15px;"> &nbsp &nbsp &nbsp &nbsp</span> Other Activities</span>
			<span><span style="margin-left:2%;background-color:#9de58b;width:15px;"> &nbsp &nbsp &nbsp &nbsp</span> Maintenance Reset</span>		
		</div>
		<div id="container_accummshots"></div>
		<div id="container_user_name"></div>
		<div id="options">
		  	<a href="view_only_index.php?page=view_only.report.php&device_id=<?php echo $d_id_identifier;?>&link_from=view_only.device"><button class="btn btn-warning btn-md pull-right"><i class="fa fa-print"></i> Report</button></a>
		  <!-- 	<button class="btn btn-danger btn-md pull-right" id="dbtn_maintenance_cycle" style="margin-right: 1%;"><i class="fa fa-wrench"></i> Chg. Maint. Cycle</button>
		  	<button class="btn btn-danger btn-md pull-right" id="dbtn_other_act" style="margin-right: 1%;"><i class="fa fa-wrench"></i> Other</button>
		   -->	<button class="btn btn-primary btn-md pull-right" id="hbtn_advanced_search" style="margin-right: 1%;"><i class="fa fa-search"></i> Adv. Srch.</button>	
		</div><br>
		<div class="box box-primary" style="min-height:56%;background-color:#d1e1f9;padding:3%;padding-bottom:1.5%;max-height:80%;" id="d_t_device"></div>
	</div>
</div>
<div class="modal fade" id="hmodal_advanced_search">
  <div class="modal-dialog" style="width: 30%;margin-top:8%;">
  	<form method="post" id="frm_search2">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Advanced Search</h4>
      </div>
      <div class="modal-body">        
		   <div class="form-group">
			<label>Date: </label>
			<input type="text" class="form-control" id="txt_date_range" name="sr_l" placeholder="Date Range">
		  </div>
		   <div class="form-group">
			<label>Operator: </label>
			<input list="operator" class="form-control" name="sr_o" placeholder="Juan Dela Cruz">
			<datalist class="txt_operator" id="operator" >			   
			</datalist>
		  </div>
      </div>
      <div class="modal-footer">
      	<button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>        		
      </div>
    </div>
    </form>
  </div>
</div>
<script src="./js/view_only.fn.js" language="javascript"></script>
<script type="text/javascript">
	load_ongoing_device(' ');
	// load_machine_no();
	load_cycle_mstatus();
	load_users();
</script>