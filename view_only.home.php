<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<style>
	#main_container{
		margin:auto;
		width:90%;
	}
	#d_t_main{
		margin-top: 2%;
	}
</style>
<input type="hidden" id="hd_username" value="<?php echo $username; ?>">
<br>
<div class="col-md-12">
	<div id="options">
		<button class="btn btn-primary btn-md pull-left" id="hbtn_advanced_search"><i class="fa fa-search"></i> Advanced Search</button>
	  	<span><span style="margin-left:10%;background-color:red;"> &nbsp &nbsp &nbsp &nbsp</span> 100% Reached</span>
	  	<span><span style="margin-left:1%;background-color:yellow;"> &nbsp &nbsp &nbsp &nbsp</span> 90% Reached</span>
	  	<!-- <span><span style="margin-left:1%;background-color:#f7c07e;"> &nbsp &nbsp &nbsp &nbsp</span> 80% Reached</span> -->
	  	<span style="margin-left:1%;color:red;font-size:86%;"><i>*Note: Refresh page for real-time data.</i></span>
	  	<button class="btn btn-warning btn-md pull-right" id="hbtn_report_gen"><i class="fa fa-print"></i> Report</button>
	</div><br>
	<div class="box box-primary" style="height:inherit;background-color:#d1e1f9;padding:3%;padding-bottom:1.5%;max-height:auto;" id="d_t_main">
		<div class="box-body">
			<table id="t_main" class="display table-bordered stripe compact cell-border table-hover" style="border-color:#ccd4e2;border-radius: 7px;">
				<thead>
					<tr style="background-color:#4288f7;color:#fcfcfc;">
						<th style="width:1%;text-align:center;"><i class="fa fa-gear fa-spin"></i></th>		
						<th style="width:150px;text-align:center;">Device Name</th>
						<th style="width:100px;text-align:center;">Drawing No.</th>
						<th style="width:50px;text-align:center;">Cavity Qty.</th>
						<th style="width:90px;text-align:center;">Maintenance Cycle</th>
						<th style="width:90px;text-align:center;">Accumulated Shots</th>
						<th style="width:150px;text-align:center;">Remaining Shots</th>
						<th style="width:50px;text-align:center;">Actual Cavity Qty.</th>
						<!-- <th style="width:80px;text-align:center;">Cycle Time Per Shot<br> <small style="color:#e8eaf9;">H:M:S:MS</small></th> -->
						<th style="width:90px;text-align:center;">Remaining Prodn. Time<br> <small style="color:#e8eaf9;">H:M</small></th>
						<th style="width:130px;text-align:center;">Last Update</th>
						<th>Grand Total</th>														
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
	<div id="dc_id_identifier"></div>	
</div>
<div class="modal fade" id="hmodal_advanced_search">
  <div class="modal-dialog">
  	<form method="post" id="frm_search">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Advanced Search</h4>
      </div>
      <div class="modal-body">        
		  <div class="form-group" id="f_hmodal_report1">
			<label for="txt_device_name">Device Name:</label>
			<input id="selected1" list="devices" class="form-control" name="sr_name" placeholder="Device Name">
			<datalist class="txt_device_name" id="devices" >			   
			</datalist>
      	 </div>
		  <div class="form-group">
			<label for="txt_maintenance">Maintenance Cycle:</label>
			<input list="maintenance" class="form-control" name="sr_m" placeholder="Maintenance Cycle">
			<datalist class="txt_maintenance" id="maintenance" >			   
			</datalist>
		  </div>
		  <div class="form-group">
			<label for="txt_cavty">Cavty Qty:</label>
			<input list="cavty" class="form-control" name="sr_c" placeholder="Cavty Qty" autocomplete="OFF">
			<datalist class="txt_cavty" id="cavty" >			   
			</datalist>
		  </div>
		   <div class="form-group">
			<label for="txt_drawing">Drawing No.:</label>
			<input list="drawing" class="form-control" name="sr_d" placeholder="Drawing Number" autocomplete="OFF">
			<datalist class="txt_drawing" id="drawing" >			   
			</datalist>
		  </div>
		   <div class="form-group">
			<label for="txt_remarks">Last Updated:</label>
			<input type="text" class="form-control" id="datepicker_last" name="sr_l" placeholder="Date of Last Update">
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
<div class="modal fade" id="hmodal_report">
  <div class="modal-dialog" style="width:28%;margin-top:10%;">
  	<form method="post" action="view_only_index.php?page=view_only.report.php&link_from=view_only.home">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Report Generation</h4>
      </div>
      <div class="modal-body">        
		  <div class="form-group" id="f_hmodal_report3">
			<label for="txt_device_name">Device Name:</label>
			<input id="selected3" list="devices" class="form-control" name="x" placeholder="Device Name" required>
			<datalist class="txt_device_name" id="devices">			   
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
</div><!-- /.modal -->
<script src="./js/view_only.fn.js?1" language="javascript"></script>
<script type="text/javascript">
	load_devices();
	// load_machine_no();
	load_drawing();
	load_cavty();
	load_maintenance();
	convert_select();
	load_cycle_mstatus();
	load_users();

	$('#datepicker_add').datepicker({
    format: 'mm/dd/yyyy',
    minDate: '-1d',
    maxDate: '+1d'
	});

	$('#datepicker_last').datepicker({
	changeMonth: true, 
	changeYear: true, 	
    format: 'mm/dd/yyyy',
    nextText: "Next"
	});
</script>