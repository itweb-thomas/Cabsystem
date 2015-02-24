var selected_driver_id = null;

$(document).ready(function()
{
	//Alle Buttons deaktivieren, die eine Auswahl benoetigen
	$('#getDeleteModalDriver').attr("disabled", true);
	$('#getEditModalDriver').attr("disabled", true);
	$('#lockDriver').attr("disabled", true);
	$('#unlockDriver').attr("disabled", true);
	
	//Select2 initalisieren
	$("#addForm-cartype_id").select2();
	
	//Listener fuer die TR der DataTable erzeugen
	oTable.$('tr').on('click',function(event) {
		changeSelectedDriverId($(this).attr('data-driver_id'));
	});

	//Form Validation hinzufuegen
	$("#addDriverForm").validate({
		rules: {
			"name": {
				required: true,
			},
			"email": {
				required: true,
				email: true
			},
			"cartype_id": {
				required: true
			}
		},
		messages: {
			name: "Bitte geben Sie den Namen an",
			email: {
				required: "Bitte geben Sie die Email Adresse an",
				email: "Die Email Adresse muss die Form name@domain.com haben"
			},
			cartype_id: "Bitte geben Sie den Autotyp an"
		}
	});
	
	//ENTER Key abfangen
	$("#addDriverModal").keypress(function( event ) 
	{
		if ( event.which == 13 ) {
			event.preventDefault();
			if($('#addDriverForm').valid()) 
			{
				addDriver();
			}
		}
	});

	//Button-Listener hinzufuegen
	$('#addDriver').click( function(e) 
	{
		if($('#addDriverForm').valid()) 
		{
			addDriver();
		}
	});
	
	$('#deleteDriver').click( function(e) 
	{
		deleteDriver();
	});

	$('#getEditModalDriver').click( function(e) 
	{
		getEditDriverModal();
	});

	$('#lockDriver').click( function(e) 
	{
		lockDriver();
	});


	$('#unlockDriver').click( function(e) 
	{
		unlockDriver();
	});

});

//die aktuell ausgewaehlte ID speichern und je nachdem die Buttons aktivieren/deaktivieren
function changeSelectedDriverId(driver_id) 
{
	if(selected_driver_id == driver_id) 
	{
		selected_driver_id = null;
		$('#getDeleteModalDriver').attr("disabled", true);
		$('#getEditModalDriver').attr("disabled", true);
		$('#lockDriver').attr("disabled", true);
		$('#unlockDriver').attr("disabled", true);
	}
	else 
	{
		selected_driver_id = driver_id;
		$('#getDeleteModalDriver').attr("disabled", false);
		$('#getEditModalDriver').attr("disabled", false);
		$('#lockDriver').attr("disabled", false);
		$('#unlockDriver').attr("disabled", false);
	}
}

function addDriver()
{
	$('#addDriver').attr("disabled", true);
	//Informationen des Fahrers aus dem Form ziehen
	var driverInfo = {};
	jQuery("#addDriverForm :input").each(function(idx,ele)
	{
		if (jQuery(ele).attr('type') == 'checkbox')
		{
			if(jQuery(ele).is(":checked")) {
				driverInfo[jQuery(ele).attr('name')] = 1;
			}
			else {
				driverInfo[jQuery(ele).attr('name')] = 0;
			}
		}
		else
		{
			driverInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
		}
	});

	//Ajax Request schicken
	jQuery.ajax({
		url:'index.php?option=com_cabsystem&controller=add&format=raw&tmpl=component',
		type:'POST',
		data:driverInfo,
		dataType:'JSON',
		success:function(result)
		{
			if ( result.success ){
				//Notification ausgeben
				$.notify(result.msg, "success");
				
				//Icon fuer aktiv/inaktiv zusammenbauen
				var active = icon_inactive;
				if(result.datatable_data.active == 1) {
					active = icon_active;
				}
				
				//Zeile zu DataTable hinzufuegen
				var added_indexes = jQuery('#dataTable').dataTable().fnAddData( [
					active,
					result.datatable_data.name,
					result.datatable_data.email,
					result.datatable_data.cartype_name]
				);
				var added_trs = oTable.fnGetNodes(added_indexes[0]);
				
				//Gerade hinzugefuegter Zeile das Attribut mit der ID geben
				$(added_trs).attr('data-driver_id',result.datatable_data.driver_id);
				
				//Gerade hinzugefuegter Zeile den EventListener anhaengen
				$(added_trs).click(function(event) {
					changeSelectedDriverId($(this).attr('data-driver_id'));
				});
				
				//Modal verstecken
				jQuery("#addDriverModal").modal('hide');
			}else{
				$.notify(result.msg, "error");
			}
		},
		complete:function() 
		{
			$('#addDriver').attr("disabled", false);
		}
	});
}

function deleteDriver(driver_id)
{
	if(selected_driver_id != null) {
		jQuery.ajax({
			url:'index.php?option=com_cabsystem&controller=delete&format=raw&tmpl=component',
			type:'POST',
			data: 'driver_id='+selected_driver_id+'&type=Driver',
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
					selected_driver_id = null;
					$('#getDeleteModalDriver').attr("disabled", true);
					$('#getEditModalDriver').attr("disabled", true);
					$('#lockDriver').attr("disabled", true);
					$('#unlockDriver').attr("disabled", true);
					
					jQuery("#deleteConfirm").modal('hide');
				}
				else {
					$.notify(result.msg, "error");
				}
			}
		});
	}
}

function lockDriver(driver_id)
{
	if(selected_driver_id != null) {
		jQuery.ajax({
			url:'index.php?option=com_cabsystem&controller=lock&format=raw&tmpl=component',
			type:'POST',
			data: 'driver_id='+selected_driver_id+'&type=Driver',
			dataType: 'JSON',
			success:function(result)
			{
				if(result.success)
				{
					$.notify(result.msg, "success");
					var anSelected = getSelectedDataTableRow( oTable );
					if ( anSelected.length !== 0 ) {
						oTable.fnUpdate(icon_inactive, anSelected[0], 0,false,false);
					}
				}
				else {
					$.notify(result.msg, "error");
				}
			}
		});
	}
}

function unlockDriver(driver_id)
{
	if(selected_driver_id != null) {
		jQuery.ajax({
			url:'index.php?option=com_cabsystem&controller=unlock&format=raw&tmpl=component',
			type:'POST',
			data: 'driver_id='+selected_driver_id+'&type=Driver',
			dataType: 'JSON',
			success:function(result)
			{
				if(result.success)
				{
					$.notify(result.msg, "success");
					var anSelected = getSelectedDataTableRow( oTable );
					if ( anSelected.length !== 0 ) {
						oTable.fnUpdate(icon_active, anSelected[0], 0,false,false);
					}
				}
				else {
					$.notify(result.msg, "error");
				}
			}
		});
	}
}

function getEditDriverModal()
{
	if(selected_driver_id != null) {
		jQuery.ajax({
			url:'index.php?option=com_cabsystem&controller=edit&task=getEditModal&format=raw&tmpl=component',
			type:'POST',
			data: {
				driver_id: selected_driver_id,
				view: 'driver',
				model: 'Driver',
				item: 'driver',
				table: 'drivers'	
			},
			dataType: 'JSON',
			success:function(result)
			{
				if(result.html)
				{
					if($('#editDriverModal').length != 0) {
						$('#editDriverModal').replaceWith($(result.html));
					}
					else {
						$(result.html).insertAfter( "#getEditModalDriver" );
					}
					$('#editDriverModal').modal('show');
					
					//Select2 initalisieren
					$("#editForm-cartype_id").select2();
					
					//Form Validation
					$("#editDriverForm").validate({
						rules: {
							"name": {
								required: true,
							},
							"email": {
								required: true,
								email: true
							},
							"cartype_id": {
								required: true
							}
						},
						messages: {
							name: "Bitte geben Sie den Namen an",
							email: {
								required: "Bitte geben Sie die Email Adresse an",
								email: "Die Email Adresse muss die Form name@domain.com haben"
							},
							cartype_id: "Bitte geben Sie den Autotyp an"
						}
					});
					
					//ENTER Key abfangen
					$("#editDriverModal").keypress(function( event ) 
					{
						if ( event.which == 13 ) {
							event.preventDefault();
							if($('#editDriverForm').valid()) 
							{
								editDriver();
							}
						}
					});
					
					$('#editDriver').click( function(e) 
					{
						if($('#editDriverForm').valid()) 
						{
							editDriver();
						}
					});
				}
			}
		});
	}
}

function editDriver()
{
	$('#editDriver').attr("disabled", true);
	var driverInfo = {};
	jQuery("#editDriverForm :input").each(function(idx,ele)
	{
		if (jQuery(ele).attr('type') == 'checkbox')
		{
			if(jQuery(ele).is(":checked")) {
				driverInfo[jQuery(ele).attr('name')] = 1;
			}
			else {
				driverInfo[jQuery(ele).attr('name')] = 0;
			}
		}
		else
		{
			driverInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
		}
	});

	jQuery.ajax({
		url:'index.php?option=com_cabsystem&controller=edit&format=raw&tmpl=component',
		type:'POST',
		data:driverInfo,
		dataType:'JSON',
		success:function(result)
		{
			if ( result.success ){
				$.notify(result.msg, "success");
				var active = icon_inactive;
				if(result.datatable_data.active == 1) {
					active = icon_active;
				}
				var anSelected = getSelectedDataTableRow( oTable );
				if ( anSelected.length !== 0 ) {
					oTable.fnUpdate([
					active,
					result.datatable_data.name,
					result.datatable_data.email,
					result.datatable_data.cartype_name], anSelected[0],undefined,false,false);
				}
				jQuery("#editDriverModal").modal('hide');
			}else{
				$.notify(result.msg, "error");
			}
		},
		complete:function() 
		{
			$('#editDriver').attr("disabled", false);
		}
	});
}