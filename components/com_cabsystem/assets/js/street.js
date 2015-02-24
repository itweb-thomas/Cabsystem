var selected_street_id = null;

$(document).ready(function()
{
	//Alle Buttons deaktivieren, die eine Auswahl benoetigen
	$('#getDeleteModalStreet').attr("disabled", true);
	$('#getEditModalStreet').attr("disabled", true);
	
	//Listener fuer die TR der DataTable erzeugen
	oTable.$('tr').on('click',function(event) {
		changeSelectedStreetId($(this).attr('data-street_id'));
	});
	
	//Select2 initalisieren
	$("#addForm-district_id").select2();

	//Form Validation hinzufuegen
	$("#addStreetForm").validate({
		rules: {
			"name": {
				required: true
			},
			"district_id": {
				required: true
			}
		},
		messages: {
			name: "Bitte geben Sie den Namen an",
			district_id: "Bitte geben Sie den Bezirk an"
		}
	});
	
	//ENTER Key abfangen
	$("#addStreetModal").keypress(function( event ) 
	{
		if ( event.which == 13 ) {
			event.preventDefault();
			if($('#addStreetForm').valid()) 
			{
				addStreet();
			}
		}
	});

	//Button-Listener hinzufuegen
	$('#addStreet').click( function(e) 
	{
		if($('#addStreetForm').valid()) 
		{
			addStreet();
		}
	});
	
	$('#deleteStreet').click( function(e) 
	{
		deleteStreet();
	});

	$('#getEditModalStreet').click( function(e) 
	{
		getEditStreetModal();
	});
});

//die aktuell ausgewaehlte ID speichern und je nachdem die Buttons aktivieren/deaktivieren
function changeSelectedStreetId(street_id) 
{
	if(selected_street_id == street_id) 
	{
		selected_street_id = null;
		$('#getDeleteModalStreet').attr("disabled", true);
		$('#getEditModalStreet').attr("disabled", true);
	}
	else 
	{
		selected_street_id = street_id;
		$('#getDeleteModalStreet').attr("disabled", false);
		$('#getEditModalStreet').attr("disabled", false);
	}
}

function addStreet()
{
	$('#addStreet').attr("disabled", true);
	//Informationen des Fahrers aus dem Form ziehen
	var streetInfo = {};
	jQuery("#addStreetForm :input").each(function(idx,ele)
	{
		if (jQuery(ele).attr('type') == 'checkbox')
		{
			if(jQuery(ele).is(":checked")) {
				streetInfo[jQuery(ele).attr('name')] = 1;
			}
			else {
				streetInfo[jQuery(ele).attr('name')] = 0;
			}
		}
		else
		{
			streetInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
		}
	});

	//Ajax Request schicken
	jQuery.ajax({
		url:'index.php?option=com_cabsystem&controller=add&format=raw&tmpl=component',
		type:'POST',
		data:streetInfo,
		dataType:'JSON',
		success:function(result)
		{
			if ( result.success ){
				//Notification ausgeben
				$.notify(result.msg, "success");
				
				//Zeile zu DataTable hinzufuegen
				var added_indexes = jQuery('#dataTable').dataTable().fnAddData( [
					result.datatable_data.name,
					result.datatable_data.district_zip,
					result.datatable_data.district_name]
				);
				var added_trs = oTable.fnGetNodes(added_indexes[0]);
				
				//Gerade hinzugefuegter Zeile das Attribut mit der ID geben
				$(added_trs).attr('data-street_id',result.datatable_data.street_id);
				
				//Gerade hinzugefuegter Zeile den EventListener anhaengen
				$(added_trs).click(function(event) {
					changeSelectedStreetId($(this).attr('data-street_id'));
				});
				
				//Modal verstecken
				jQuery("#addStreetModal").modal('hide');
			}else{
				$.notify(result.msg, "error");
			}
		},
		complete:function() 
		{
			$('#addStreet').attr("disabled", false);
		}
	});
}

function deleteStreet(street_id)
{
	if(selected_street_id != null) {
		jQuery.ajax({
			url:'index.php?option=com_cabsystem&controller=delete&format=raw&tmpl=component',
			type:'POST',
			data: 'street_id='+selected_street_id+'&type=Street',
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
					selected_street_id = null;
					$('#getDeleteModalStreet').attr("disabled", true);
					$('#getEditModalStreet').attr("disabled", true);
					
					jQuery("#deleteConfirm").modal('hide');
				}
				else {
					$.notify(result.msg, "error");
				}
			}
		});
	}
}

function getEditStreetModal()
{
	if(selected_street_id != null) {
		jQuery.ajax({
			url:'index.php?option=com_cabsystem&controller=edit&task=getEditModal&format=raw&tmpl=component',
			type:'POST',
			data: {
				street_id: selected_street_id,
				view: 'street',
				model: 'Street',
				item: 'street',
				table: 'streets'	
			},
			dataType: 'JSON',
			success:function(result)
			{
				if(result.html)
				{
					if($('#editStreetModal').length != 0) {
						$('#editStreetModal').replaceWith($(result.html));
					}
					else {
						$(result.html).insertAfter( "#getEditModalStreet" );
					}
					$('#editStreetModal').modal('show');
	
					//Select2 initalisieren
					$("#editForm-district_id").select2();
					
					//Form Validation
					$("#editStreetForm").validate({
						rules: {
							"name": {
								required: true
							},
							"district_id": {
								required: true
							}
						},
						messages: {
							name: "Bitte geben Sie den Namen an",
							district_id: "Bitte geben Sie den Bezirk an"
						}
					});
					
					//ENTER Key abfangen
					$("#editStreetModal").keypress(function( event ) 
					{
						if ( event.which == 13 ) {
							event.preventDefault();
							if($('#editStreetForm').valid()) 
							{
								editStreet();
							}
						}
					});
					
					$('#editStreet').click( function(e) 
					{
						if($('#editStreetForm').valid()) 
						{
							editStreet();
						}
					});
				}
			}
		});
	}
}

function editStreet()
{
	$('#editStreet').attr("disabled", true);
	var streetInfo = {};
	jQuery("#editStreetForm :input").each(function(idx,ele)
	{
		if (jQuery(ele).attr('type') == 'checkbox')
		{
			if(jQuery(ele).is(":checked")) {
				streetInfo[jQuery(ele).attr('name')] = 1;
			}
			else {
				streetInfo[jQuery(ele).attr('name')] = 0;
			}
		}
		else
		{
			streetInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
		}
	});

	jQuery.ajax({
		url:'index.php?option=com_cabsystem&controller=edit&format=raw&tmpl=component',
		type:'POST',
		data:streetInfo,
		dataType:'JSON',
		success:function(result)
		{
			if ( result.success ){
				$.notify(result.msg, "success");
				var anSelected = getSelectedDataTableRow( oTable );
				if ( anSelected.length !== 0 ) {
					oTable.fnUpdate([
					result.datatable_data.name,
					result.datatable_data.district_zip,
					result.datatable_data.district_name], anSelected[0],undefined,false,false);
				}
				jQuery("#editStreetModal").modal('hide');
			}else{
				$.notify(result.msg, "error");
			}
		},
		complete:function() 
		{
			$('#editStreet').attr("disabled", false);
		}
	});
}