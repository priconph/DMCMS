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
		<div id="cont_new_maintenance_cycle" style="display:none;"></div>
		<a href="operator_index.php?page=operator.home.php" style="position:absolute;left:2%;"><i class="glyphicon glyphicon-chevron-left"></i> Back</a>	
		<div class="col-md-4" style="margin-left:7%;">
			<div class="box box-primary" style="overflow-y: hidden;overflow-x: hidden;min-height:111px;max-height:151px;">
				<div class="box-body box-profile" style="padding-top:2%;padding-left:10%;">
						<div id="container_details"></div>
				</div>
			</div>
		</div>
		<div class="pull-right" style="margin-top:2%;width:26%;height:4%;">
			<span><span style="margin-left:2%;background-color:#83cdef;width:15px;"> &nbsp &nbsp &nbsp &nbsp</span> Other Activities</span>
			<span><span style="margin-left:2%;background-color:#9de58b;width:15px;"> &nbsp &nbsp &nbsp &nbsp</span> Maintenance Reset</span>		
		</div>
		<div id="container_accummshots" style=""></div>
		<div id="container_user_name"></div>
		<div id="options">
		  	<a href="operator_index.php?page=operator.report.php&device_id=<?php echo $d_id_identifier;?>&link_from=operator.device"><button class="btn btn-warning btn-md pull-right"><i class="fa fa-print"></i> Report</button></a>
		  	<button class="btn btn-success btn-md pull-right" id="dbtn_add_shot" style="margin-right: 1%;" disabled><i class="fa fa-plus"></i> Add Shot</button>
		  	<button class="btn btn-danger btn-md pull-right" id="dbtn_other_act" style="margin-right: 1%;"><i class="fa fa-wrench"></i> Other</button>
		  	<button class="btn btn-primary btn-md pull-right" id="hbtn_advanced_search" style="margin-right: 1%;"><i class="fa fa-search"></i> Adv. Search</button>	
		</div><br>
		<div class="box box-primary" style="min-height:56%;background-color:#d1e1f9;padding:3%;padding-bottom:1.5%;max-height:80%;" id="d_t_device"></div>
	</div>
</div>
<div class="modal fade" id="dmodal_add_shot" style="height:1800px;">
  <div class="modal-dialog" style="margin-top:1%;">
    <form method="post" action="operator_index.php?page=operator.device.php" id="frm_update_shots">   
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <div id="container_nshots_modal"></div>
      </div>
      <div class="modal-body">
        	<input type="text" style="position:absolute;" name="d_id" value="<?php echo $d_id_identifier;?>" hidden>
		  <div class="form-group">
			<label for="txt_lastname">Production Date:</label><div class="input_required pull-right"></div>
			<input type="text" class="form-control" id="datepicker_add" name="txt_date" placeholder="Date" required>
		  </div>
		   <div class="form-group">
			<label for="txt_address">Shots:</label><div class="input_required pull-right"></div>
			<input type="text" class="form-control" id="text" name="txt_shots" placeholder="Shots" required>
			<div id="container_viewaccum"></div>
		  </div>
		  <div class="form-group">
			<label for="txt_address">Actual Cavity Qty.:</label><div class="input_required pull-right"></div>
			<input type="text" class="form-control" id="text2" name="txt_Cavty" placeholder="Actual Cavity Qty." required>
		  </div>
		  <div class="form-group">
			<label for="txt_device_name">Machine No.:</label><div class="input_required pull-right"></div>
			<input type="text" class="form-control" placeholder="Machine Number" id="txt_machine_no" name="txt_machine_no" required>
		  </div>
		   <div class="form-group">
			<label for="txt_remarks">Remarks:</label>
			<textarea type="text" class="form-control" name="txt_remarks" placeholder="Remarks" style="resize: vertical;"></textarea>
		  </div>
      </div>
      <div class="modal-footer">        
        <button type="button" id="submit_shots" class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
      </div>   
    </div>
    </form>
  </div>
</div>
<div class="modal fade" id="modal_confirm_shot">
  <div class="modal-dialog" style="width:30%;margin-top:15%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        System Alert
      </div>
      <div class="modal-body">
      	<form method="post" action="operator_index.php?page=operator.device.php" id="frm_confirm_shots">
      	Are you sure you want to add shots?
      </div>
      <div class="modal-footer">        
        <button id="submit_shots_yes" class="btn btn-success">Yes</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
		</form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="dmodal_other_act">
  <div class="modal-dialog" style="width: 30%;margin-top:10%;">
  	<form method="post" id="frm_other_act">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <div id="container_otheract_modal"></div>
      </div>
      <div class="modal-body">
      	  <div class="form-group">
			<label for="txt_lastname">Production Date:</label><div class="input_required pull-right"></div>
			<input type="text" class="form-control" id="datepicker_past" name="txt_past_date" placeholder="Date" required>
		  </div>        
          <div class="form-group">
			<label for="txt_activity">Past Shots:</label><div class="input_required pull-right"></div>
			<input type="text" class="form-control" id="txt_past_shots" name="txt_past_shots" placeholder="Shots" required />
			<span style="color:red;font-weight:bold;">*Note: This is for previous accumulated shots only!</span>
		  </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="submit_other_act" class="btn btn-success"><i class="fa fa-check"></i> Submit</button>	
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>	
      </div>
    </div>
    </form>
  </div>
</div>
<div class="modal fade" id="modal_confirm_other_act">
  <div class="modal-dialog" style="width:30%;margin-top:12%;">
  	<form method="post" action="operator_index.php?page=operator.device.php" id="frm_confirm_other_act">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        System Alert
      </div>
      <div class="modal-body">      	
      	Are you sure you want to add this previous shots?
      </div>
      <div class="modal-footer">        
        <button id="submit_other_act_yes" class="btn btn-success">Yes</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>	
      </div>
    </div>
    </form>
  </div>
</div>
<div class="modal fade" id="hmodal_advanced_search">
  <div class="modal-dialog" style="width:30%;margin-top:8%;">
  	<form method="post" id="frm_search2">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Advanced Search</h4>
      </div>
      <div class="modal-body">        
          <div class="form-group">
			<label>Date:</label>
			<input type="text" class="form-control" id="txt_date_range" name="sr_l" placeholder="Date Range">
		  </div>
		  <div class="form-group">
			<label>Operator:</label>
			<input list="operator" class="form-control" name="sr_o" placeholder="Juan Dela Cruz">
			<datalist class="txt_operator" id="operator">			   
			</datalist>
		  </div>
      </div>
      <div class="modal-footer">      
        <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>		
      </div>
    </div><!-- /.modal-content -->
    </form>
  </div><!-- /.modal-dialog -->
</div>
<script src="./js/operator.fn.js?1" language="javascript"></script>
<script src="./dist/sweetalert-dev.js"></script>
<link rel="stylesheet" href="./dist/sweetalert.css">
<script type="text/javascript">
	date_time_picker('txt_date_range');
	load_ongoing_device(' ');
	// load_machine_no();
	load_dieset_cycle();
	load_users();

	$('#datepicker_add').datepicker({
	    format: 'mm/dd/yyyy'
	});

	$('#datepicker_search').datepicker({
		changeMonth: true, 
		changeYear: true, 	
	    format: 'mm/dd/yyyy',
	    nextText: "Next"
	});

	$('#datepicker_past').datepicker({
		changeMonth: true, 
		changeYear: true, 	
	    format: 'mm/dd/yyyy',
	    nextText: "Next"
	});
	$(document).ready(function(){
		function checkInput(e) {
		    var e = e || event;
		    var char = e.type == 'keypress' 
		        ? String.fromCharCode(e.keyCode || e.which) 
		        : (e.clipboardData || window.clipboardData).getData('Text');
		    if (/[^\d]/gi.test(char)) {
		        return false;
		    }
		 }

		 var text = document.getElementById('text');
		 text.onkeypress = text.onpaste = checkInput;
		 var text = document.getElementById('text2');
		 text.onkeypress = text.onpaste = checkInput;
		var text = document.getElementById('txt_past_shots');
		 text.onkeypress = text.onpaste = checkInput;
	});
</script>