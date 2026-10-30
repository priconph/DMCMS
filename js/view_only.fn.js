var handler = "handler.php";
var username = $('#hd_username').val();
load_new_devices();
var t_main 	= $('#t_main').DataTable({ 
		// "bProcessing": true,
		// "bServerSide": true,
		"ajax" : "./server-side/view_only_main_table.php",
		select: false,
		scrollY: "50vh",
		scrollX: true,
		scrollCollapse: false,
		fixedHeader: true,
		pageLength: 20,
		lengthMenu: [[10, 20, 50, 100, -1], [10, 20	, 50, 100, "All"]],
		order: [[0,"desc"],[9,"desc"]],
		columnDefs: [
			{"targets":[0],"orderable":true},
			{"className":"dt-right","targets":[3,4,5,6,7,10]},
			{"className":"dt-center","targets":[0,8,9]},
			{ "width": "2%", "targets": 0 },
			{ "width": "20%", "targets": 1 },
			{ "width": "13%", "targets": 2 },
			{ "width": "5%", "targets": 3 },
			{ "width": "9%", "targets": 4 },
			{ "width": "9%", "targets": 5 },
			{ "width": "9%", "targets": 6 },
			{ "width": "5%", "targets": 7 },
			{ "width": "9%", "targets": 8 },
			{ "width": "10%", "targets": 9 },
			{ "width": "9%", "targets": 10 }]
	});
var count100 = '<span class="label" style="background-color: red;color:black;font-size:11px;" id="container_100"></span> ';

$(document).ready(function(){

	/* open modal for advanced search */
	$('#hbtn_advanced_search').click(function(){ 
		$('#hmodal_advanced_search').modal('show');
	});

	/* open modal for report_generation */
	$('#hbtn_report_gen').click(function(){ 
		$('#hmodal_report').modal('show');
	});

	/* open modal for new maintenance cycle */
	$('#dbtn_maintenance_cycle').click(function(){ 
		$('#hmodal_change_cycle').modal('show');
	});


		/* add new shots (main) submit */
	$('#frm-example').submit(function(e){
		/* clear the container after submit */
		$('#container_frm-example').empty();
	});

	/* advanced search */
	$('#frm_search').submit(function(e){
		/* prevent page to submit */
		e.preventDefault();
		// load_main( $(this).serialize() );
		// t_main.ajax.url( 'pages/main_table.php?d_name='+$('#selected1').val() ).load();
		// alert($(this).serialize());
		var sr_name = $('#frm_search #selected1').val();
		var sr_m = $('#frm_search input[name=sr_m]').val();
		var sr_c = $('#frm_search input[name=sr_c]').val();
		var sr_d = $('#frm_search input[name=sr_d]').val();
		var sr_l = $('#frm_search input[name=sr_l]').val();
		t_main.ajax.url( 'server-side/view_only_main_table.php?sr_name=' + escape(sr_name) + '&sr_m=' + escape(sr_m) + '&sr_c=' + escape(sr_c) + '&sr_d=' + escape(sr_d) + '&sr_l=' + escape(sr_l) ).load();
		$('.modal').modal('hide');
	});

	$('#frm_search2').submit(function(e){
		/* prevent page to submit */
		e.preventDefault();
		load_ongoing_device( $(this).serialize() );
	});

	$('#frm_search3').submit(function(e){
		/* prevent page to submit */
		e.preventDefault();
		load_report_table( $(this).serialize() );

	});

	$('#frm_change_cycle').submit(function(e){
		e.preventDefault();
		var id_para 		= $('#id_paragraph').text();
		var dc_id_para 		= $('#dc_id_paragraph').text();
		var para_user_name 	= $('#user_name_paragraph').text();

		var data = {
			"action"		: "change_cycle",
			"d_id_device"	: id_para,
			"dc_id_device"	: dc_id_para,
			"user_name" 	: para_user_name
		}
		data = $.param(data) + "&" + $(this).serialize();
		change_cycle(data);
	});
});

date_time_picker('txt_date_range');
// date_time_picker('txt_date_range2');

function load_new_devices(){
	var data = {
		"action"	: "load_new_devices"
	}
	$.ajax({
		type	: "post",
		dataType: "json",
		data	: data,
		url		: handler,
		success	: function(data){
			console.log(data['queryshit']);
			
		t_main.ajax.reload();
		},error	: function(data){
			alert("ajax error load_new_devices");
		}
	});	
}

function date_time_picker(id) {
	$('#'+id).daterangepicker(
			{
				ranges: {
					'Today': [moment(), moment()],
					'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
					'Last 7 Days': [moment().subtract('days', 6), moment()],
					'Last 30 Days': [moment().subtract('days', 29), moment()],
					'This Month': [moment().startOf('month'), moment().endOf('month')],
					'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
				},
				startDate: moment().subtract('days', 29),
				endDate: moment()
			},
		function(start, end) {
			// A1: sr_l('sr_l'); 
			$('#reportrange span').html(start.format('mm/dd/yyyy') + ' - ' + end.format('mm/dd/yyyy'));
			if(id == "date_created_search_group") {
				load_all_control_number_per_date_created('control_number_search_list_group','control_number_search',$('#date_created_search_group').val());
			}
		}
	);
	return false;
}

function load_users() {
	var data = {
		"action"	: "load_users",
		"username"	: username
	}
	$.ajax({
		type	: "post",
		dataType: "json",
		data	: data,
		url		: handler,
		success	: function(data){
			console.log(data['emp_name']);
			var html = '<b>'+data['emp_name']+'</b>'; 
			$('#user_name').append(html);
			var html  = '<p id="user_name_paragraph" hidden>'+data['emp_name']+'</p>';
			$('#container_user_name').append(html);
		},error	: function(data){
			alert("ajax error");
		}
	});
}

function load_main(data_search){
	var data = {
		"action"	: "load_main"
	}
	data = $.param(data) + '&' + data_search;
	$.ajax({
		type	: "post",
		dataType: "json",
		data	: data,
		url		: handler,
		success	: function(data){
			console.log(data['query']);console.log(data['test']);
			var ctr = data['ctr'];
			$('#d_t_main').empty();	
			var html  = '<table id="t_main" class="display table-bordered table-hover" style="border-color:#ccd4e2;border-radius: 7px;">';
				html += '	<thead>';
				html += '		<tr style="background-color:#4288f7;color:#fcfcfc;">';
				html += '			<th>Device Name</th>';
				html += '			<th>Maintenance Cycle</th>';
				html += '			<th>Cavity Qty.</th>';
				html += '			<th>Drawing No.</th>';
				html += '			<th>Accumulated Shots</th>';
				html += '			<th>Last Update</th>';				
				html += '		</tr>';
				html += '	</thead>';
				html += '	<tbody>';
				html += '	</tbody>';
				html += '</table>';
			$('#d_t_main').append(html);		
			for(i=0;i<ctr;i++){
				var qwe = data['dc_maintenance_cycle'][i];
				qwe = Number(qwe);
				var html  = '<tr id="'+data['d_id'][i]+'">';
					html += '	<td><a href="view_only_index.php?page=view_only.device.php&device_id='+data['d_id'][i]+'">'+data['d_name'][i]+'</td>';
					html += '	<td>'+data['dc_maintenance_cycle'][i].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");+'</td>';
					html += '	<td>'+data['d_cavty'][i]+'</td>';
					html += '	<td>'+data['d_drawing'][i]+'</td>';
					if (data['dc_accumulated_shots'][i] >= (qwe * 0.80) && (data['dc_accumulated_shots'][i] < (qwe * 0.9)))
						{
						html += '	<td style="background-color:#f7c07e;">'+data['dc_accumulated_shots'][i].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");+'</td>';
						} 
					else if (data['dc_accumulated_shots'][i] >= (qwe * 0.9) && (data['dc_accumulated_shots'][i] < (qwe)))
						{						
						html += '	<td style="background-color:yellow;">'+data['dc_accumulated_shots'][i].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");+'</td>';
				 		} 

					else if (data['dc_accumulated_shots'][i] >= (qwe)) 
						{
						html += '	<td style="background-color:red;">'+data['dc_accumulated_shots'][i].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");+'</td>';
				 		} 
					else
						{						
						html += '	<td style="background-color:;">'+data['dc_accumulated_shots'][i].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");+'</td>';
						}
					html += '	<td>'+data['dc_end_date'][i]+'</td>';
					html += '</tr>';
			$('#t_main tbody').append(html);
			}
			/* Initialize datatable without pagination */
			var oTable = $('#t_main').DataTable({ paginate:true });
			table_events();
		},error	: function(data){
			alert("ajax error");
		}
	});
}

function convert_select(){
	var data = {}; 
	$("#devices option").each(function(i,el) {  
	   data[$(el).data("value")] = $(el).val();
	});
	// `data` : object of `data-value` : `value`
	console.log(data, $("#devices option").val());

	    $('#selected1').change(function()
	    {
	    	$('#d_id').empty();
	        var value = $('#selected1').val();
	       	var form_datalist = $('<input style="position:absolute;" type="hidden" name="d_id" value="' +$('#devices [value="' + value + '"]').data('value')+ '"></input>');
	       	$('#f_hmodal_report1').append(form_datalist);
	    });

	    $('#selected2').change(function()
	    {
	    	$('#d_id').empty();
	        var value = $('#selected2').val();
	       	var form_datalist = $('<input style="position:absolute;" type="hidden" name="d_id" value="' +$('#devices [value="' + value + '"]').data('value')+ '"></input>');
			$('#f_hmodal_report2').append(form_datalist);	
								var id_para = $('#id_paragraph').text();
					var data = {
							"action"		: "load_dieset_cycle",
							"id_device" 	: $('#devices [value="' + value + '"]').data('value')
						}
						data = $.param(data);
						$.ajax({
							type	: "post",
							dataType: "json",
							data	: data,
							url		: handler,
							success	: function(data){
								var ctr = data['ctr'];
									ctr = ctr-1;
								var dc_id 						= data['dc_idd'][ctr];
								var dc_start_date				= data['dc_start_date'][ctr];
								var dc_end_date					= data['dc_end_date'][ctr];
								var dc_status					= data['dc_status'][ctr];
								var dc_ashots 					= data['dc_accumulated_shots'][ctr];
								var dc_maintenance_cycle 		= data['dc_maintenance_cycle'][ctr];
								$('#dc_id_identifier').empty();
								var html  = '<p id="dc_id_paragraph" hidden>'+dc_id+'</p>';
									html += '<p id="dc_ashots_paragraph" hidden>'+dc_ashots+'</p>';
									html += '<p id="dc_status_paragraph" hidden>'+dc_status+'</p>';
									html += '<p id="dc_start_date_paragraph" hidden>'+dc_start_date+'</p>';
									html += '<p id="dc_end_date_paragraph" hidden>'+dc_end_date+'</p>';
									html += '<p id="dc_maintenance_cycle_paragraph" hidden>'+dc_maintenance_cycle+'</p>';
								$('#dc_id_identifier').append(html);
							},error	: function(data){
								alert("ajax error load_dieset_cycle");
							}
						});			
	    });

		$('#selected3').change(function()
	    {
	    	$('#d_id').empty();
	        var value = $('#selected3').val();
	       	var form_datalist = $('<input style="position:absolute;" type="hidden" name="d_id" value="' +$('#devices [value="' + value + '"]').data('value')+ '"></input>');
	       	$('#f_hmodal_report3').append(form_datalist);
	    });
}

function date_time_picker(id) {
	$('#'+id).daterangepicker(
			{
				ranges: {
					'Today': [moment(), moment()],
					'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
					'Last 7 Days': [moment().subtract('days', 6), moment()],
					'Last 30 Days': [moment().subtract('days', 29), moment()],
					'This Month': [moment().startOf('month'), moment().endOf('month')],
					'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
				},
				startDate: moment().subtract('days', 29),
				endDate: moment()
			},
		function(start, end) {
			// A1: sr_l('sr_l'); 
			$('#reportrange span').html(start.format('mm/dd/yyyy') + ' - ' + end.format('mm/dd/yyyy'));
			if(id == "date_created_search_group") {
				load_all_control_number_per_date_created('control_number_search_list_group','control_number_search',$('#date_created_search_group').val());
			}
		}
	);
	return false;
}

function load_report_table(data_search){
	var id_para = $('#id_paragraph').text();
	var data = {
		"action"	: "load_report_table",
		"id_para"	: id_para
	}
	data = $.param(data) + '&' + data_search;
	$.ajax({
		type	: "post",
		dataType: "json",
		data	: data,
		url		: handler,
		success	: function(data){
			var ctr = data['ctr'];
			$('#d_t_report').empty();	
			var html  = '<table id="t_report" class="display table-bordered table-hover" style="border-color:#ccd4e2;border-radius: 7px;height: 77%;">';
				html += '	<thead>';
				html += '		<tr style="background-color:#4288f7;color:#fcfcfc;">';
				html += '			<th style="text-align: center;visibility: hidden;"><input name="select_all" id="select_all" value="1" type="checkbox"></th>';
				html += '			<th style="text-align: center;">Date Range</th>';
				html += '			<th style="text-align: center;">Maintenance Cycle</th>';
				html += '			<th style="text-align: center;">Accumulated Shots</th>';
				html += '			<th style="text-align: center;">Cavity Qty.</th>';
				html += '			<th style="text-align: center;">Drawing No.</th>';
				html += '		</tr>';
				html += '	</thead>';
				html += '	<tbody>';
				html += '	</tbody>';
				html += '</table>';
			$('#d_t_report').append(html);		
			for(i=0;i<ctr;i++){
				var html  = '<tr id="'+data['dc_idd'][i]+'">';
					html += '	<td id="id_select">'+data['dc_idd'][i]+'</td>';				
					html += '	<td style="text-align: center;"><a href="report.php?id0='+data['dc_idd'][i]+'&max=1" target="_blank">'+data['dc_start_date'][i]+' to '+data['dc_end_date'][i]+'</a></td>';
					html += '	<td>'+data['dc_maintenance_cycle'][i].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");+'</td>';
					html += '	<td>'+data['dc_accumulated_shots'][i].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");+'</td>';
					html += '	<td>'+data['d_cavty'][i]+'</td>';
					html += '	<td>'+data['d_drawing'][i]+'</td>';
					html += '</tr>';
				 $('#t_report tbody').append(html);	
			}
				/* Initialize datatable without pagination */
			   var table = $('#t_report').DataTable({
			   	bPaginate: true,
   				bLengthChange: false,
    			bFilter: true,
    			bInfo: false,
    			bAutoWidth: false,
    			paginate:true,
			      columnDefs: [
			         {
			            'targets'	: 0,
			            'checkboxes': {
			               'selectRow'	: true
			            }
			         }
			      ],
			      select: {
			         'style': 'multi'
			      },
			      order: [[1, 'desc']]
			   });

		  $('#frm-example').on('submit', function(e){
		      var form = this;
		      var x    = 0;
		      var rows_selected = table.column(0).checkboxes.selected();

		      // Iterate over all selected checkboxes
		      $.each(rows_selected, function(index, rowId){
		         // Create a hidden element 
		        $('#container_frm-example').append(
		             $('<input>')
		                .attr('type', 'hidden')
		                .attr('name', 'id'+x)
		                .val(rowId)
		        );
		    	 x=x+1;
		       });
		      	$('#container_frm-example').append(
		             $('<input>')
		                .attr('type', 'hidden')
		                .attr('name', 'max')
		                .val(x)
		        );
		   });
			table_events();
		},error	: function(data){
			alert("ajax error");
		}
	});
}

function load_alert(){
	var id_para = $('#id_paragraph').text();

	var data = {
		"action"			: "load_alert"
	}
	data = $.param(data) + "&" + $(this).serialize();

	$.ajax({
		type	: "post",
		dataType: "json",
		data	: data,
		url		: handler,
		success	: function(data){
			var ctr = data['ctr'];
			var control = '';
			for(i=0;i<ctr;i++){
			control += data['d_name'][i]+'\n';
		}
			alert(control);

		},error	: function(data){
			alert("ajax error");
		}
	});
}

function load_ongoing_device(data_search){
	load_dieset_cycle();
	var id_para 		= $('#id_paragraph').text();
	var start_date_para = $('#dc_start_date_paragraph').text();
	var end_date_para 	= $('#dc_end_date_paragraph').text();
	var data = {
		"action"			: "load_ongoing_device",
		"id_device"			: id_para,
		"start_date"		: start_date_para,
		"end_date"			: end_date_para
	}

	data = $.param(data) + '&' + data_search;

	$.ajax({
		type	: "post",
		dataType: "json",
		data	: data,
		url		: handler,
		success	: function(data){
			var accumulated_shots = 0;
			var ctr = data['ctr'];
			$('#d_t_device').empty();	
			var html  = '<table id="t_device" class="display table-bordered stripe compact cell-border table-hover" style="width:min-height:inherit;border-color:#ccd4e2;border-radius: 7px;">';
				html += '	<thead>';
				html += '		<tr style="background-color:#4288f7;color:#fcfcfc;">';
				html += '			<th style="text-align:center;">Production Date</th>';
				html += '			<th style="text-align:center;">Shot</th>';
				html += '			<th style="text-align:center;">Shot Accum.</th>';
				html += '			<th style="text-align:center;">Cavity Qty. (actual)</th>';
				html += '			<th style="text-align:center;">Mach No.</th>';
				html += '			<th style="text-align:center;">Operator</th>';
				html += '			<th style="text-align:center;">Remarks</th>';	
				html += '			<th style="text-align:center;">Date Encoded</th>';	
				html += '			<th style="display: none;"> </th>';			
				html += '		</tr>';
				html += '	</thead>';
				html += '	<tbody>';
				html += '	</tbody>';
				html += '</table>';
			$('#d_t_device').append(html);	
			var max=0;	
			var html = '';
			for(i=0;i<ctr;i++){
				if(data['s_status'][i]=="reset"){
					accumulated_shots = 0;
				}
				else{
					if(data['s_status'][i]!=="past"){
						accumulated_shots = (Number(accumulated_shots)+Number(data['s_count'][i]));
					}
					if(data['s_status'][i]=="past"){
						accumulated_shots = accumulated_shots;
					}
				}
				if(accumulated_shots===null){accumulated_shots=0;}
				if(data['s_status'][i]=='reset'){
					 html  += '<tr style="background-color:#9de58b;" id="'+data['s_id'][i]+'">';
						html += '	<td style="text-align:center;">'+data['s_date'][i]+'</td>';
						html += '	<td style="color:#9de58b;text-align:right;">'+data['s_count'][i].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");+'</td>';
						html += '	<td class="accum_column" style="text-align:right;">'+accumulated_shots.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");+'</td>';
						html += '	<td class>'+data['s_actual_cavty'][i]+'</td>';
						html += '	<td style="color:#9de58b;">'+data['s_machine'][i]+'</td>';
						html += '	<td>'+data['s_operator'][i]+'</td>';
						html += '	<td>'+data['s_remarks'][i]+'</td>';
						html += '	<td>'+data['s_adate'][i]+'</td>';						
						html += '	<td style="display:none;">'+data['s_date_for_order'][i]+accumulated_shots+'</td>';
						html += '</tr>';
				}
				else if (data['s_status'][i]=='other') {
					 html  += '<tr style="background-color:#83cdef;" id="'+data['s_id'][i]+'">';
						html += '	<td style="text-align:center;">'+data['s_date'][i]+'</td>';
						if(data['s_count'][i]>0){html += '	<td style="text-align:right">'+data['s_count'][i].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'<i style="font-size:12px;"> &nbsp(test)</i>'+'</td>';}
						else{html += '	<td style="color:#83cdef;text-align:right">'+data['s_count'][i].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");+'</td>';}
						html += '	<td class="accum_column">'+accumulated_shots.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");+'</td>';
						html += '	<td>'+data['s_actual_cavty'][i]+'</td>';
						html += '	<td style="color:#83cdef;">'+data['s_machine'][i]+'</td>';
						html += '	<td>'+data['s_operator'][i]+'</td>';
						html += '	<td>'+data['s_remarks'][i]+'</td>';
						html += '	<td>'+data['s_adate'][i]+'</td>';					
						html += '	<td style="display:none;">'+data['s_date_for_order'][i]+accumulated_shots+'</td>';
						html += '</tr>';			
				}
				else if (data['s_status'][i]=='past') {
					 html += '<tr id="'+data['s_id'][i]+'">';
						html += '	<td style="text-align:center;">'+data['s_date'][i]+'</td>';
						html += '	<td style="text-align:right">'+data['s_count'][i].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'&nbsp<i style="font-size:12px;">P.A.S.</i></td>';
						html += '	<td style="text-align:right" class="accum_column"></td>';
						html += '	<td></td>';
						html += '	<td>'+data['s_machine'][i]+'</td>';
						html += '	<td>'+data['s_operator'][i]+'</td>';
						html += '	<td>'+data['s_remarks'][i]+'</td>';
						html += '	<td>'+data['s_adate'][i]+'</td>';						
						html += '	<td style="display:none;">'+data['s_date_for_order'][i]+accumulated_shots+'</td>';
						html += '</tr>';
				}
				else if (data['s_status'][i]=='otherbyhea') {
					 html += '<tr id="'+data['s_id'][i]+'">';
						html += '	<td style="text-align:center;">'+data['s_date'][i]+'</td>';
						html += '	<td style="text-align:right">'+data['s_count'][i].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'&nbsp<i style="font-size:12px;">By Head</i></td>';
						html += '	<td class="accum_column" style="text-align:right">'+accumulated_shots.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");+'</td>';
						html += '	<td></td>';
						html += '	<td></td>';
						html += '	<td>'+data['s_operator'][i]+'</td>';
						html += '	<td>'+data['s_remarks'][i]+'</td>';
						html += '	<td>'+data['s_adate'][i]+'</td>';						
						html += '	<td style="display:none;">'+data['s_date_for_order'][i]+accumulated_shots+'</td>';
						html += '</tr>';
				}				
				else{
					 html += '<tr id="'+data['s_id'][i]+'">';
						html += '	<td style="text-align:center;">'+data['s_date'][i]+'</td>';
						html += '	<td style="text-align:right">'+data['s_count'][i].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");+'</td>';
						html += '	<td class="accum_column" style="text-align:right">'+accumulated_shots.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");+'</td>';
						html += '	<td>'+data['s_actual_cavty'][i]+'</td>';
						html += '	<td>'+data['s_machine'][i]+'</td>';
						html += '	<td>'+data['s_operator'][i]+'</td>';
						html += '	<td>'+data['s_remarks'][i]+'</td>';
						html += '	<td>'+data['s_adate'][i]+'</td>';						
						html += '	<td style="display:none;">'+data['s_date_for_order'][i]+accumulated_shots+'</td>';
						html += '</tr>';
				}
		 	
		 	max=max+1;
		 	}
		 	$('#t_device tbody').append(html);
		 	if(max>0){
		 		switch(Number(data['dc_mstatus'])){
		 			// case 2: html = "<style>.accum_column{background-color:#f7c07e;}<style>";
		 					// break;
		 			case 3: html = "<style>.accum_column{background-color:yellow;}<style>";
		 					break;
		 			case 4: html = "<style>.accum_column{background-color:red;}<style>";
		 					break;
		 			default: html = "<style><style>";
		 					break;		
		 		}
				$('body').append(html);
			}
			load_device_details();
		
			/* Initialize datatable without pagination */
			var oTable = $('#t_device').DataTable({
											paginate:true, 
											order: [[8,"desc"]], 
											scrollY: "40%",
											scrollX: true,
											"columnDefs": [
												{ "width": "10%", "targets": 0 },
												{ "width": "10%", "targets": 1 },
												{ "width": "10%", "targets": 2 },
												{ "width": "10%", "targets": 3 },
												{ "width": "8%", "targets": 4 },
												{ "width": "18%", "targets": 5 },
												{ "width": "20%", "targets": 6 }
											]
										});
			table_events();
		},error	: function(data){
			alert("ajax error");
		}
	});
}

/* load all dieset cycle */
function load_dieset_cycle(){
	var id_para = $('#id_paragraph').text();

	var data = {
			"action"		: "load_dieset_cycle",
			"id_device" 	: id_para
		}
	data = $.param(data);
	$.ajax({
		type	: "post",
		dataType: "json",
		data	: data,
		url		: handler,
		success	: function(data){
			var ctr = data['ctr'];
				ctr = ctr-1;
			var dc_id 						= data['dc_idd'];
			var dc_start_date				= data['dc_start_date'];
			var dc_end_date					= data['dc_end_date'];
			var dc_status					= data['dc_status'];
			var dc_ashots 					= data['dc_accumulated_shots'];
			var dc_maintenance_cycle 		= data['dc_maintenance_cycle'];
			var grand_total 				= data['grand_total'];
			var action 						= '';
			dc_ashots 						= Number(dc_ashots);
			dc_maintenance_cycle			= Number(dc_maintenance_cycle);
			
			if(dc_maintenance_cycle>=dc_ashots){
				var remaining_shots = Number(dc_maintenance_cycle)-Number(dc_ashots);
				if(remaining_shots==0){
					remaining_shots = '<i style="font-size:90%;color:red;">Maint. Cycle Reached</i>';
				}
				else{
					remaining_shots = remaining_shots.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				}
			}
			else{
				var remaining_shots = '<i style="font-size:90%;color:red;">Over Maintenance Cycle</i>';
			}


			var html  = '<p id="dc_id_paragraph" hidden>'+dc_id+'</p>';
				html += '<p id="dc_ashots_paragraph" hidden>'+dc_ashots+'</p>';
				html += '<p id="dc_status_paragraph" hidden>'+dc_status+'</p>';
				html += '<p id="dc_start_date_paragraph" hidden>'+dc_start_date+'</p>';
				html += '<p id="dc_end_date_paragraph" hidden>'+dc_end_date+'</p>';
				html += '<p id="dc_maintenance_cycle_paragraph" hidden>'+dc_maintenance_cycle+'</p>';
			$('#dc_id_identifier').append(html);
			$('#container_accummshots').empty();
			var html  = '<div class="col-md-3">';
				html += '	<div style="background-color:#d1e1f9;width:100%;height:111px;">';
				
					// if (dc_ashots >= (dc_maintenance_cycle * 0.80) && (dc_ashots < (dc_maintenance_cycle * 0.9)))
					// 	{
					// 	html += '		<span class="info-box-icon" style="height:111px;width:82px;background-color:#f7c07e;"></span>';
					// 	action = 'beige';
					// 	} 
					// else 

					if (dc_ashots >= (dc_maintenance_cycle * 0.9) && (dc_ashots < (dc_maintenance_cycle)))
						{						
						html += '		<span class="info-box-icon" style="height:111px;width:82px;background-color: yellow;"></span>';
						action = 'yellow';
				 		} 

					else if (dc_ashots >= (dc_maintenance_cycle)) 
						{
						html += '		<span class="info-box-icon" style="height:111px;width:82px;background-color:red;"></span>';
						action = 'red';
				 		} 
					else
						{						
						html += '		<span class="info-box-icon" style="height:111px;width:82px;background-color: #f7f8f9;"></span>';
						}
				html += '		<div style="font-size:85%;text-align:center;padding-right:11%;">';
				html += '			<span> &nbsp&nbsp&nbsp&nbsp&nbsp&nbspACCUMULATED SHOTS:<br></span>';
				html += '			<span style="font-size:15px;"><b> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp'+dc_ashots.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'</b><br></span>';
				html += '			<span> &nbsp&nbsp&nbsp&nbsp&nbsp&nbspREMAINING SHOTS:<br></span>';
				html += '			<span style="font-size:15px;"><b> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp'+remaining_shots.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'</b><br></span>';
				html += '			<span> &nbsp&nbsp&nbsp&nbsp&nbsp&nbspGRAND TOTAL:<br></span>';
				html += '			<span style="font-size:15px;"><b> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp'+grand_total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'</b></span>';
				html += '		</div>';
				html += '	</div>';
				html += '</div>';
			$('#container_accummshots').append(html);
			if(action!='red'){
				$('#dbtn_add_shot').removeAttr("disabled");
					}

			/* Initialize datatable without pagination */
		},error	: function(data){
			alert("ajax error load_dieset_cycle");
		}
	});
}

function load_device_details(){
	var id_para = $('#id_paragraph').text();
	var data = {
		"action"			: "load_device_details",
		"id_device"			: id_para
	}
	data = $.param(data) + "&" + $(this).serialize();
	$.ajax({
		type	: "post",
		dataType: "json",
		data	: data,
		url		: handler,
		success	: function(data){
				$('#container_details').empty();
				var	html  = '	<div class="col-md-6">';
					html += '	<span class="pull-left"><label>Device Name:</label><br>'+data['d_name'][0]+'</span>';
					html += '	<span class="pull-left"><label>Maintenance Cycle:</label><br>'+data['dc_maintenance_cycle'][0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");+'</span>';
					html += '	</div>';
					html += '	<div class="col-md-6">';
					html += '	<span class="pull-left"><label>Cavity Qty.: &nbsp &nbsp</label><br>'+data['d_cavty'][0]+'</span>';
					html += '	<span class="pull-left"><label>Drawing Number: &nbsp &nbsp</label><br>'+data['d_drawing'][0]+'</span>';
					html += '	</div>';
					html += '	<p id="d_name_paragraph" style="position:absolute;" hidden>'+data['d_name'][0]+'</p>';
					html += '	<p id="dc_mcycle_paragraph" style="position:absolute;" hidden>'+data['dc_maintenance_cycle'][0]+'</p>';
					html += '	<p id="d_cavty_paragraph" style="position:absolute;" hidden>'+data['d_cavty'][0]+'</p>';
					html += '	<p id="d_drawing_paragraph" style="position:absolute;" hidden>'+data['d_drawing'][0]+'</p>';	
				$('#container_details').append(html);
				$('#container_details_report').empty();
				var html  = '	<h3 class="box-title">Report Generation - '+data['d_name'][0]+'</h3>';
				$('#container_details_report').append(html);
				$('#container_rshots_modal').empty();
					var html  = '	<h4 class="modal-title">Reset Shots - '+data['d_name'][0]+'</h4>';
				$('#container_rshots_modal').append(html);
				$('#container_otheract_modal').empty();	
					var html  = '	<h4 class="modal-title">Other Activities - '+data['d_name'][0]+'</h4>';
				$('#container_otheract_modal').append(html);	
		},error	: function(data){
			alert("ajax error");
		}
	});
}

function load_cycle_mstatus(){
	var type = $('#type_paragraph').text();
	var data = {
		"action"	: "cycle_mstatus"
	}
	data = $.param(data);
	$.ajax({
		type	: "post",
		dataType: "json",
		data	: data,
		url		: handler,
		success	: function(data){
			var ctr2 = data['ctr2'];
			var ctr3 = data['ctr3'];
			var ctr4 = data['ctr4'];
			switch (type) {
			    case 'red':
				    $('#d_t_listview').empty();	
					var html  = '<table id="t_listview" class="table-hover table-bordered">';
						html += '	<thead>';
						html += '		<tr>';
						html += '			<th>Device Name</th>';
						html += '			<th>Maintenance Cycle</th>';
						html += '			<th>Start Date</th>';
						html += '			<th>End Date</th>';
						html += '		</tr>';
						html += '	</thead>';
						html += '	<tbody>';
						html += '	</tbody>';
						html += '</table>';
					$('#d_t_listview').append(html);		
					for(i=0;i<ctr4;i++){
						var html  = '<tr id="'+data['d_id4'][i]+'">';
							html += '	<td><a href="view_only_index.php?page=view_only.device.php&device_id='+data['d_id4'][i]+'">'+data['d_name4'][i]+'</a></td>';
							html += '	<td>'+data['dc_maintenance_cycle4'][i]+'</td>';
							html += '	<td>'+data['dc_start_date4'][i]+'</td>';
							html += '	<td>'+data['dc_end_date4'][i]+'</td>';
							html += '</tr>';
						 $('#t_listview tbody').append(html);	
					}
			        break; 
			    case 'yellow':
			        $('#d_t_listview').empty();	
					var html  = '<table id="t_listview" class="table-hover table-bordered">';
						html += '	<thead>';
						html += '		<tr>';
						html += '			<th>Device Name</th>';
						html += '			<th>Maintenance Cycle</th>';
						html += '			<th>Start Date</th>';
						html += '			<th>End Date</th>';
						html += '		</tr>';
						html += '	</thead>';
						html += '	<tbody>';
						html += '	</tbody>';
						html += '</table>';
					$('#d_t_listview').append(html);		
					for(i=0;i<ctr3;i++){
						var html  = '<tr id="'+data['d_id3'][i]+'">';
							html += '	<td><a href="view_only_index.php?page=view_only.device.php&device_id='+data['d_id3'][i]+'">'+data['d_name3'][i]+'</a></td>';
							html += '	<td>'+data['dc_maintenance_cycle3'][i]+'</td>';
							html += '	<td>'+data['dc_start_date3'][i]+'</td>';
							html += '	<td>'+data['dc_end_date3'][i]+'</td>';
							html += '</tr>';
						 $('#t_listview tbody').append(html);	
					}
			        break; 
			    case 'beige':
			        $('#d_t_listview').empty();	
					var html  = '<table id="t_listview" class="table-hover table-bordered">';
						html += '	<thead>';
						html += '		<tr>';
						html += '			<th>Device Name</th>';
						html += '			<th>Maintenance Cycle</th>';
						html += '			<th>Start Date</th>';
						html += '			<th>End Date</th>';
						html += '		</tr>';
						html += '	</thead>';
						html += '	<tbody>';
						html += '	</tbody>';
						html += '</table>';
					$('#d_t_listview').append(html);		
					for(i=0;i<ctr2;i++){
						var html  = '<tr id="'+data['d_id2'][i]+'">';
							html += '	<td><a href="view_only_index.php?page=view_only.device.php&device_id='+data['d_id2'][i]+'">'+data['d_name2'][i]+'</a></td>';
							html += '	<td>'+data['dc_maintenance_cycle2'][i]+'</td>';
							html += '	<td>'+data['dc_start_date2'][i]+'</td>';
							html += '	<td>'+data['dc_end_date2'][i]+'</td>';
							html += '</tr>';
						 $('#t_listview tbody').append(html);	
					}
			        break; 
			}
						
			/* Initialize datatable without pagination */
			var oTable = $('#t_listview').DataTable({ paginate:true });
			table_events();

			// var html = ' <span class="label" style="background-color: #f7c07e;color:black;font-size:11px;" id="container_82"></span>';

			if (data['count82']==1) {
				var html  = data['count82'];
				$('#container_82').append(html);
				var html  = 'You have '+data['count82']+' notification';

			}else if(data['count82']==0){
				var html  = data['count82'];
				// $('#container_82').append(html);
				var html  = 'You have no notification';
				
			}else{
				var html  = data['count82'];
				$('#container_82').append(html);
				var html  = 'You have '+data['count82']+' notifications';
			}
			$('#beige_notif').append(html);
			for(i=0;i<ctr2;i++){
				var badge  = '<li>';
					badge += '	<a href="view_only_index.php?page=view_only.device.php&device_id='+data['d_id2'][i]+'">';
					badge += '	 <i class="fa fa-eye text-aqua"></i>'+data['d_name2'][i]+' (80% reached)';
					badge += '	</a>';
					badge += '</li> ';
				 $('#beige_menu').append(badge);
            }                     
                    
			
			if (data['count90']==1) {
				var html  = data['count90'];
				$('#container_90').append(html);
				var html  = 'You have '+data['count90']+' notification';
			}else if(data['count90']==0){
				var html  = data['count90'];
				// $('#container_90').append(html);
				var html  = 'You have no notification';
			}else{
				var html  = data['count90'];
				$('#container_90').append(html);
				var html  = 'You have '+data['count90']+' notifications';
			}
			$('#yellow_notif').append(html);
			for(i=0;i<ctr3;i++){
				var badge  = '<li>';
					badge += '	<a href="view_only_index.php?page=view_only.device.php&device_id='+data['d_id3'][i]+'">';
					badge += '	 <i class="fa fa-eye text-aqua"></i>'+data['d_name3'][i]+' (90% reached)';
					badge += '	</a>';
					badge += '</li> ';
				 $('#yellow_menu').append(badge);
            }   

				
			if (data['count100']==1) {
				var html  = data['count100'];
				$('#container_100').append(html);	
				var html  = 'You have '+data['count100']+' notification';
			}else if(data['count100']==0){
				var html  = data['count100'];
				// $('#container_100').append(html);
				var html  = 'You have no notification';
			}else{
				var html  = data['count100'];
				$('#container_100').append(html);	
				var html  = 'You have '+data['count100']+' notifications';
			}
			$('#red_notif').append(html);
			for(i=0;i<ctr4;i++){
				var badge  = '<li>';
					badge += '	<a href="view_only_index.php?page=view_only.device.php&device_id='+data['d_id4'][i]+'">';
					badge += '	 <i class="fa fa-eye text-aqua"></i>'+data['d_name4'][i]+' (100% reached)';
					badge += '	</a>';
					badge += '</li> ';
				 $('#red_menu').append(badge);
            }  			

		},error	: function(data){
			alert("ajax error");
		}
	});
}

/* Call table events such as click tr */
function table_events(){
	/* Update - tr click event */
	$('#tbl_records tbody tr').click(function(){
	//$('#tbl_records').on('click','tbody tr',function(){ //used for datatables with pagination
		selected_pkid = "";
		$('#tbl_records tbody tr').attr('style','');
		$(this).attr('style','background:teal;color:white;');
		selected_pkid = this.id;
	});
}

function load_devices(){
	var data = {
		"action"	: "load_devices"
	}
	data = $.param(data);
	$.ajax({
		type 	: "post",
		dataType: "json",
		data 	:data,
		url 	:handler,		
		success :function(data){
			$('.txt_device_name').append(data['html']);
		},error: function(){
			alert('handler error');
		}
	});
}

function load_maintenance(){
	var data = {
		"action"	: "load_maintenance"
	}
	data = $.param(data);
	$.ajax({
		type 	: "post",
		dataType: "json",
		data 	:data,
		url 	:handler,
		success :function(data){
			// alert(data['html']);
			$('.txt_maintenance').append(data['html']);
		},error: function(){
			alert('handler error');
		}
	});
}

function load_cavty(){
	var data = {
		"action"	: "load_cavty"
	}
	data = $.param(data);
	$.ajax({
		type 	: "post",
		dataType: "json",
		data 	:data,
		url 	:handler,
		success :function(data){
			$('.txt_cavty').append(data['html']);
		},error: function(){
			alert('handler error');
		}
	});
}

function load_drawing(){
	var data = {
		"action"	: "load_drawing"
	}
	data = $.param(data);
	$.ajax({
		type 	: "post",
		dataType: "json",
		data 	:data,
		url 	:handler,
		success :function(data){
			// alert(data['html']);
			$('.txt_drawing').append(data['html']);
		},error: function(){
			alert('handler error');
		}
	});
}

$(document).ready(function() {
    var table = $('#t_main').DataTable();
 
    $('#t_main tbody').on('click', 'tr', function(){
        $(this).toggleClass('selected');
    });
 
    $('#button').click(function(){
        alert(table.rows('.selected').data().length +' row(s) selected');
    });
});