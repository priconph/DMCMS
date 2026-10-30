<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$link_to=$_GET['link_from'].".php";
if(isset($_GET['device_id'])){$d_id_identifier=$_GET['device_id'];}
else{$d_id_identifier=$_POST['d_id'];}
if(strpos( $link_to, "device" ) !== true ){$link_to=$link_to.'&device_id='.$d_id_identifier;}
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
		margin-top:2%;
	}
	.even.selected td {
           background-color: #d0e8d2; !important; /* Add !important to make sure override datables base styles */
  	}
 	.odd.selected td {
          background-color: #d0e8d2; !important; /* Add !important to make sure override datables base styles */
  	 }
  	 #link_report_r:selected{
  	 	background-color: green;
  	 }
  	 #a_color.selected{
  	 	color:black;
  	 }
</style>
<input type="hidden" id="hd_username" value="<?php echo $username; ?>">
<p id="id_paragraph" style="position:absolute;" hidden><?php echo $d_id_identifier;?></p>
<div class="box-header with-border" id="container_details_report">
	<!-- <span class="small pull-right">Date Today: <b>&nbsp<?php echo date("F d, Y"); ?></b></span> -->
</div>
<br>
<div class="col-md-12">
<div class="col-md-12">
	<a href="maintenance_index.php?page=<?php echo $link_to;?>" style="position:absolute;left:2%;"><i class="glyphicon glyphicon-chevron-left"></i> Back</a>	
		<div id="options">
			<form id="frm-example" action="report.php" method="get" target='_blank'>
				<div id="container_frm-example"></div>
				<button type="submit" class="btn btn-danger btn-md pull-right" id="btn_pdf_view"><i class="fa fa-file-pdf-o"></i> View as PDF</button>
			</form>
				<button class="btn btn-primary btn-md pull-left" id="hbtn_advanced_search"><i class="fa fa-search"></i> Advanced Search</button>
		</div><br>
	<div id="d_t_report" class="box box-primary" style="height:inherit;background-color:#d1e1f9;padding:3%;padding-bottom:1.5%;overflow-y:scroll;height:67%;"></div>
</div>
</div>
<div class="modal fade" id="hmodal_advanced_search">
  <div class="modal-dialog" style="width:30%;margin-top:10%;">
  	<form method="post" id="frm_search3">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Advanced Search</h4>
      </div>
      <div class="modal-body">        
		  <div class="form-group" id="f_hmodal_report">
		   <div class="form-group">
			<label>Date Range:</label>
			<input type="text" class="form-control" id="txt_date_range" name="sr_l" placeholder="Date Range">
			<input type="hidden" class="form-control" id="txt_date_range2" name="sr_l2" placeholder="Last Updated">
		  </div>
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
<script type="text/javascript" language="javascript" src="./js/datatables.min.js"></script>
<script type="text/javascript" language="javascript" src="./js/dataTables.checkboxes.min.js"></script>
<script src="./js/maintenance.fn.js" language="javascript"></script>
<script type="text/javascript">
	load_ongoing_device();
	load_report_table();
	load_cycle_mstatus();
	load_users();
	date_time_picker('txt_date_range');

	$('#datepicker_search').datepicker({
	changeMonth: true, 
	changeYear: true, 	
    format: 'mm/dd/yyyy',
    nextText: "Next"
	});
</script>