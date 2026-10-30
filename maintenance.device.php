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
	#a_load_new_cycle:hover{
		cursor:pointer;
	}
</style>
<input type="hidden" id="dc_id" value="<?php echo $_GET['dc_id'];?>">
<input type="hidden" id="hd_username" value="<?php echo $username; ?>">
<input type="hidden" id="hdn_old_maintenance_cycle">
<br>
<div class="col-md-12">
	<p id="id_paragraph" style="position:absolute;" hidden><?php echo $d_id_identifier;?></p>
	<div id="dc_id_identifier"></div>
	<div class="col-md-12">
		<div id="cont_new_maintenance_cycle"></div>
		<a href="maintenance_index.php?page=maintenance.home.php" style="position:absolute;left:2%;"><i class="glyphicon glyphicon-chevron-left"></i> Back</a>	
		<div class="col-md-4" style="margin-left:5%;">
			<div class="box box-primary" style="overflow-y:hidden;overflow-x:hidden;min-height:111px;max-height:161px;">
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
		  	<a href="maintenance_index.php?page=maintenance.report.php&device_id=<?php echo $d_id_identifier;?>&link_from=maintenance.device"><button class="btn btn-warning btn-md pull-right"><i class="fa fa-print"></i> Report</button></a>
		  	<button class="btn btn-success btn-md pull-right" id="dbtn_reset_shot" style="margin-right: 1%;"><i class="fa fa-spin fa-refresh"></i> Reset</button>
		  	<button class="btn btn-danger btn-md pull-right" id="dbtn_other_act" style="margin-right: 1%;"><i class="fa fa-wrench"></i> Other</button>
		  	<button class="btn btn-primary btn-md pull-right" id="hbtn_advanced_search" style="margin-right: 1%;"><i class="fa fa-search"></i> Advanced Srch.</button>	
		</div><br>
		<div class="box box-primary" style="min-height:56%;background-color:#d1e1f9;padding:3%;padding-bottom:1.5%;max-height:80%;" id="d_t_device">
		</div>
	</div>
</div>
<div class="modal fade" id="dmodal_reset_shot">
  <div class="modal-dialog" style="width: 30%;margin-top:10%;">
  	<form method="post" id="frm_reset_shots">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <div id="container_rshots_modal"></div>
      </div>
      <div class="modal-body">        
          <div class="form-group">
			<label for="txt_remarks">Remarks:</label><div class="input_required pull-right"></div>
			<textarea type="text" class="form-control" name="txt_remarks" placeholder="Remarks" style="resize: vertical;" required></textarea>
		  </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="submit_reset" class="btn btn-success"><i class="fa fa-check"></i> Reset</button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>		
      </div>
    </div>
    </form>
  </div>
</div>
<div class="modal fade" id="modal_confirm_reset">
  <div class="modal-dialog" style="width:30%;margin-top:12%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        System Alert
      </div>
      <div class="modal-body">
      	<form method="post" action="operator_index.php?page=maintenance.device.php" id="frm_confirm_reset">
      	Are you sure you want to reset the maintenance cycle?
      </div>
      <div class="modal-footer">
        <button id="submit_reset_yes" class="btn btn-success">Yes</button>
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
			<label for="txt_activity">Activity:</label><div class="input_required pull-right"></div>
			<input type="text" class="form-control" name="txt_activity" placeholder="Input the activity" required />
		  </div>
		  <div class="form-group">
			<input type="checkbox" id="yourBox" style="height:10px;"/><label for="txt_shots"> Testing Shots:</label>
			<span>
			  	<input type="text" class="form-control" id="yourText" name="txt_shots" disabled />
		  	</span>
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
  	<form method="post" action="operator_index.php?page=maintenance.device.php" id="frm_confirm_other_act">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        System Alert
      </div>
      <div class="modal-body">      	
      	Are you sure you want to add this activity?
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
  <div class="modal-dialog" style="width: 40%;margin-top:8%;width:30%;">
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
			<input list="operator" class="form-control" name="sr_o" placeholder="Juan Dela Cruz" type="text">
			<datalist class="txt_operator" id="operator" >			   
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
<script src="./js/maintenance.fn.js?1" language="javascript"></script>
<script type="text/javascript">
	date_time_picker('txt_date_range');
	load_ongoing_device(' ');
	// load_machine_no();
	load_cycle_mstatus();
	load_users();
	
	$('#datepicker_add').datepicker({
    format: 'mm/dd/yyyy',
    minDate: '-1d',
    maxDate: '+1d'
	});

	$('#datepicker_search').datepicker({
	changeMonth: true, 
	changeYear: true, 	
    format: 'mm/dd/yyyy',
    nextText: "Next"
	});

	$(document).ready(function(){
		document.getElementById('yourBox').onchange = function() {
    		document.getElementById('yourText').disabled = !this.checked;
		};

		var text = document.getElementById('yourText');
		text.onkeypress = text.onpaste = checkInput;

		function checkInput(e) {
		    var e = e || event;
		    var char = e.type == 'keypress' 
		        ? String.fromCharCode(e.keyCode || e.which) 
		        : (e.clipboardData || window.clipboardData).getData('Text');
		    if (/[^\d]/gi.test(char)) {
		        return false;
		    }
		}
		// if($('#cont_new_maintenance_cycle').child().child()){

		// }
	});	
</script>