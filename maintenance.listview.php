<?php
	$type=$_GET['type'];
?>
<style>
	#d_t_report{
		margin-top: 2%
	}
	#details_cont{
		margin-top: 6%;
		margin-bottom: 2%;		
	}
	#options{
		margin-top:0%;
	}
</style>
<input type="hidden" id="hd_username" value="<?php echo $username; ?>">
<p id="type_paragraph" style="position:absolute;" hidden><?php echo $type;?></p>
<div class="box-header with-border" id="container_details_report">
	<span class="small pull-right">Date Today: <b>&nbsp<?php echo date("F d, Y"); ?></b></span>
</div>
<br>
<div class="col-md-12">
	<div class="col-md-12">
		<a href="maintenance_index.php?page=maintenance.home.php" style="position:absolute;left:2%;"><i class="fa fa-home"></i> Go Home</a>	
			<div id="options">
				<!-- <button class="btn btn-primary btn-md pull-left" id="hbtn_advanced_search"><i class="fa fa-search"></i> Advanced Search</button> -->
			</div><br>
		<div id="d_t_listview" class="box box-primary" style="margin-top:1%;height:inherit;background-color:#d1e1f9;padding:3%;padding-bottom:1.5%;overflow-y:scroll;height:67%;"></div>
	</div>
</div>
<div class="modal fade" id="hmodal_advanced_search">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Advanced Search</h4>
      </div>
      <div class="modal-body">
        <form method="post" id="frm_search3">
		  <div class="form-group" id="f_hmodal_report">
		   <div class="form-group">
			<label>Last Updated:</label>
			<input type="text" class="form-control" id="datepicker_search" name="sr_l" placeholder="Last Updated">
		  </div>
		  <label>Maintenance Cycle:</label>
			<input list="maintenance" class="form-control" name="sr_m" placeholder="Maintenance Cycle" autocomplete="OFF">
			<datalist class="txt_maintenance" id="maintenance" >			   
			</datalist>
		  </div>
		  <div class="form-group">
			<label>Cavty Qty:</label>
			<input list="cavty" class="form-control" name="sr_c" placeholder="Cavty Qty" autocomplete="OFF">
			<datalist class="txt_cavty" id="cavty" >			   
			</datalist>
		  </div>
		   <div class="form-group">
			<label>Drawing No.:</label>
			<input list="drawing" class="form-control" name="sr_d" placeholder="Drawing Number" autocomplete="OFF">
			<datalist class="txt_drawing" id="drawing" >			   
			</datalist>
		  </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success">Submit</button>
		</form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" language="javascript" src="./js/datatables.min.js"></script>
<script type="text/javascript" language="javascript" src="./js/dataTables.checkboxes.min.js"></script>
<script src="./js/maintenance.fn.js" language="javascript"></script>
<script type="text/javascript">
	load_cycle_mstatus();
	load_users();

	$('#datepicker_search').datepicker({
	changeMonth: true, 
	changeYear: true, 	
    format: 'mm/dd/yyyy',
    nextText: "Next"
	});
</script>