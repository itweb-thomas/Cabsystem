var selected_customer_id = null;
		
var data_district=[{}];
var data_street=[{}];

$(document).ready(function()
{
	//Alle Buttons deaktivieren, die eine Auswahl benoetigen
	$('#getDeleteModalCustomer').attr("disabled", true);
	$('#getEditModalCustomer').attr("disabled", true);
	
	//Listener fuer die TR der DataTable erzeugen
	oTable.$('tr').on('click',function(event) {
		changeSelectedCustomerId($(this).attr('data-customer_id'));
	});

	//Form Validation hinzufuegen
	$("#addCustomerForm").validate({
		rules: {
			"name": {
				required: true,
			},
			"phone": {
				required: true
			},
			"email": {
				required: true,
				email: true
			},
			"city_id": {
				required: true
			},
			"district_id": {
				required: true
			},
			"street_id": {
				required: true
			}
		},
		messages: {
			salutation_id: "Bitte geben Sie eine Anrede an",
			name: "Bitte geben Sie den Namen an",
			phone: "Bitte geben Sie die Telefonnummer an",
			email: {
				required: "Bitte geben Sie die Email Adresse an",
				email: "Die Email Adresse muss die Form name@domain.com haben"
			},
			city_id: "Bitte geben Sie den Ort an",
			district_id: "Bitte geben Sie den Bezirk an",
			street_id: "Bitte geben Sie die Adresse an"
		}
	});
	
	//ENTER Key abfangen
	$("#addCustomerModal").keypress(function( event ) 
	{
		if ( event.which == 13 ) {
			event.preventDefault();
			if($('#addCustomerForm').valid()) 
			{
				addCustomer();
			}
		}
	});

	//Button-Listener hinzufuegen
	$('#addCustomer').click( function(e) 
	{
		if($('#addCustomerForm').valid()) 
		{
			addCustomer();
		}
	});
	
	$('#deleteCustomer').click( function(e) 
	{
		deleteCustomer();
	});

	$('#getEditModalCustomer').click( function(e) 
	{
		getEditCustomerModal();
	});
	
	if($('#addForm-district-array').length !== 0 && $('#addForm-street-array').length !== 0) {
		var district_array = JSON.parse($('#addForm-district-array').val());
		var street_array = JSON.parse($('#addForm-street-array').val());
		
		function format(item) { return item.tag; }
		
		$("#addForm-salutation_id").select2();
		$("#addForm-title_id").select2({allowClear:true});
		$("#addForm-city_id").select2();
		
		data_district = district_array[$("#addForm-city_id").select2("val")];
		$("#addForm-district_id").select2({
			data:function() { return { text:'tag', results: data_district }; },
			formatSelection: format,
			formatResult: format
		});
		
		data_street = street_array[$("#addForm-district_id").select2("val")];
		$("#addForm-street_id").select2({
			data:function() { return { text:'tag', results: data_street }; },
			formatSelection: format,
			formatResult: format
		});
		
		$("#addForm-city_id").on("select2-selecting", function(e) 
		{
			data_district = district_array[e.val];
			$("#addForm-district_id").select2("val","");
			$("#addForm-street_id").select2("val","");
		});
		
		$("#addForm-district_id").on("select2-selecting", function(e) 
		{
			data_street = street_array[e.val];
			$("#addForm-street_id").select2("val","");
		});
	}
});

//die aktuell ausgewaehlte ID speichern und je nachdem die Buttons aktivieren/deaktivieren
function changeSelectedCustomerId(customer_id) 
{
	if(selected_customer_id == customer_id) 
	{
		selected_customer_id = null;
		$('#getDeleteModalCustomer').attr("disabled", true);
		$('#getEditModalCustomer').attr("disabled", true);
	}
	else 
	{
		selected_customer_id = customer_id;
		$('#getDeleteModalCustomer').attr("disabled", false);
		$('#getEditModalCustomer').attr("disabled", false);
	}
}

function addCustomer()
{
	$('#addCustomer').attr("disabled", true);
	//Informationen des Fahrers aus dem Form ziehen
	var customerInfo = {};
	jQuery("#addCustomerForm :input").each(function(idx,ele)
	{
		if (jQuery(ele).attr('type') == 'checkbox')
		{
			if(jQuery(ele).is(":checked")) {
				customerInfo[jQuery(ele).attr('name')] = 1;
			}
			else {
				customerInfo[jQuery(ele).attr('name')] = 0;
			}
		}
		else
		{
			customerInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
		}
	});

	//Ajax Request schicken
	jQuery.ajax({
		url:'index.php?option=com_cabsystem&controller=add&format=raw&tmpl=component',
		type:'POST',
		data:customerInfo,
		dataType:'JSON',
		success:function(result)
		{
			if ( result.success ){
				//Notification ausgeben
				$.notify(result.msg, "success");
				
				var salutation = result.datatable_data.salutation_name;
				if(result.datatable_data.title_name) {
					salutation += ' '+result.datatable_data.title_name;
				}
				
				var address = result.datatable_data.street_name;
				if(result.datatable_data.house) {
					address += ' '+result.datatable_data.house;
				}
				if(result.datatable_data.stair) {
					address += '/'+result.datatable_data.stair;
				}
				if(result.datatable_data.door) {
					address += '/'+result.datatable_data.door;
				}
				address += ', '+result.datatable_data.district_zip+' '+result.datatable_data.city_name+' '+result.datatable_data.district_name;
				
				//Zeile zu DataTable hinzufuegen
				var added_indexes = jQuery('#dataTable').dataTable().fnAddData( [
					salutation,
					result.datatable_data.name,
					address,
					result.datatable_data.phone,
					result.datatable_data.email]
				);
				var added_trs = oTable.fnGetNodes(added_indexes[0]);
				
				//Gerade hinzugefuegter Zeile das Attribut mit der ID geben
				$(added_trs).attr('data-customer_id',result.datatable_data.customer_id);
				
				//Gerade hinzugefuegter Zeile den EventListener anhaengen
				$(added_trs).click(function(event) {
					changeSelectedCustomerId($(this).attr('data-customer_id'));
				});
				
				//Modal verstecken
				jQuery("#addCustomerModal").modal('hide');
			}else{
				$.notify(result.msg, "error");
			}
		},
		complete:function() 
		{
			$('#addCustomer').attr("disabled", false);
		}
	});
}

function deleteCustomer(customer_id)
{
	if(selected_customer_id != null) {
		jQuery.ajax({
			url:'index.php?option=com_cabsystem&controller=delete&format=raw&tmpl=component',
			type:'POST',
			data: 'customer_id='+selected_customer_id+'&type=Customer',
			dataType: 'JSON',
			success:function(result)
			{
				if(result.success)
				{
					$.notify(result.msg, "success");
					var anSelected = getSelectedDataTableRow( oTable );
					if ( anSelected.length !== 0 ) {
						oTable.fnDeleteRow( anSelected[0] );
					}
					
					//Variable der aktuell selektierten TR auf null setzen und Buttons disablen
					selected_customer_id = null;
					$('#getDeleteModalCustomer').attr("disabled", true);
					$('#getEditModalCustomer').attr("disabled", true);
					
					jQuery("#deleteConfirm").modal('hide');
				}
				else {
					$.notify(result.msg, "error");
				}
			}
		});
	}
}

function getEditCustomerModal()
{
	if(selected_customer_id != null) {
		jQuery.ajax({
			url:'index.php?option=com_cabsystem&controller=edit&task=getEditModal&format=raw&tmpl=component',
			type:'POST',
			data: {
				customer_id: selected_customer_id,
				view: 'customer',
				model: 'Customer',
				item: 'customer',
				table: 'customers'	
			},
			dataType: 'JSON',
			success:function(result)
			{
				if(result.html)
				{
					if($('#editCustomerModal').length != 0) {
						$('#editCustomerModal').replaceWith($(result.html));
					}
					else {
						$(result.html).insertAfter( "#getEditModalCustomer" );
					}
					$('#editCustomerModal').modal('show');
					
					//Form Validation
					$("#editCustomerForm").validate({
						rules: {
							"name": {
								required: true,
							},
							"phone": {
								required: true
							},
							"email": {
								required: true,
								email: true
							},
							"city_id": {
								required: true
							},
							"district_id": {
								required: true
							},
							"street_id": {
								required: true
							}
						},
						messages: {
							salutation_id: "Bitte geben Sie eine Anrede an",
							name: "Bitte geben Sie den Namen an",
							phone: "Bitte geben Sie die Telefonnummer an",
							email: {
								required: "Bitte geben Sie die Email Adresse an",
								email: "Die Email Adresse muss die Form name@domain.com haben"
							},
							city_id: "Bitte geben Sie den Ort an",
							district_id: "Bitte geben Sie den Bezirk an",
							street_id: "Bitte geben Sie die Adresse an"
						}
					});
					
					//ENTER Key abfangen
					$("#editCustomerModal").keypress(function( event ) 
					{
						if ( event.which == 13 ) {
							event.preventDefault();
							if($('#editCustomerForm').valid()) 
							{
								editCustomer();
							}
						}
					});
					
					$('#editCustomer').click( function(e) 
					{
						if($('#editCustomerForm').valid()) 
						{
							editCustomer();
						}
					});
	
					if($('#addForm-district-array').length !== 0 && $('#addForm-street-array').length !== 0) {
						var district_array = JSON.parse($('#addForm-district-array').val());
						var street_array = JSON.parse($('#addForm-street-array').val());
						
						function format(item) { return item.tag; }
						
						$("#editForm-salutation_id").select2();
						$("#editForm-title_id").select2({allowClear:true});
						$("#editForm-city_id").select2();
						
						data_district = district_array[$("#editForm-city_id").select2("val")];
						$("#editForm-district_id").select2({
							data:function() { return { text:'tag', results: data_district }; },
							formatSelection: format,
							formatResult: format
						});
						
						data_street = street_array[$("#editForm-district_id").select2("val")];
						$("#editForm-street_id").select2({
							data:function() { return { text:'tag', results: data_street }; },
							formatSelection: format,
							formatResult: format
						});
						
						$("#editForm-city_id").on("select2-selecting", function(e) 
						{
							data_district = district_array[e.val];
							$("#editForm-district_id").select2("val","");
							$("#editForm-street_id").select2("val","");
						});
						
						$("#editForm-district_id").on("select2-selecting", function(e) 
						{
							data_street = street_array[e.val];
							$("#editForm-street_id").select2("val","");
						});
					}
				}
			}
		});
	}
}

function editCustomer()
{
	$('#editCustomer').attr("disabled", true);
	var customerInfo = {};
	jQuery("#editCustomerForm :input").each(function(idx,ele)
	{
		if (jQuery(ele).attr('type') == 'checkbox')
		{
			if(jQuery(ele).is(":checked")) {
				customerInfo[jQuery(ele).attr('name')] = 1;
			}
			else {
				customerInfo[jQuery(ele).attr('name')] = 0;
			}
		}
		else
		{
			customerInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
		}
	});

	jQuery.ajax({
		url:'index.php?option=com_cabsystem&controller=edit&format=raw&tmpl=component',
		type:'POST',
		data:customerInfo,
		dataType:'JSON',
		success:function(result)
		{
			if ( result.success ){
				$.notify(result.msg, "success");
				var salutation = result.datatable_data.salutation_name;
				if(result.datatable_data.title_name) {
					salutation += ' '+result.datatable_data.title_name;
				}
				
				var address = result.datatable_data.street_name;
				if(result.datatable_data.house) {
					address += ' '+result.datatable_data.house;
				}
				if(result.datatable_data.stair) {
					address += '/'+result.datatable_data.stair;
				}
				if(result.datatable_data.door) {
					address += '/'+result.datatable_data.door;
				}
				address += ', '+result.datatable_data.district_zip+' '+result.datatable_data.city_name+' '+result.datatable_data.district_name;
				
				var anSelected = getSelectedDataTableRow( oTable );
				if ( anSelected.length !== 0 ) {
					oTable.fnUpdate([
					salutation,
					result.datatable_data.name,
					address,
					result.datatable_data.phone,
					result.datatable_data.email], anSelected[0],undefined,false,false);
				}
				jQuery("#editCustomerModal").modal('hide');
			}else{
				$.notify(result.msg, "error");
			}
		},
		complete:function() 
		{
			$('#editCustomer').attr("disabled", false);
		}
	});
}