var selected_flight_id = null;

$(document).ready(function()
{
	//Alle Buttons deaktivieren, die eine Auswahl benoetigen
	$('#getDeleteModalFlight').attr("disabled", true);
	$('#getEditModalFlight').attr("disabled", true);
	
	//Select2 initalisieren
	$("#addForm-city_id").select2();
	
	//Listener fuer die TR der DataTable erzeugen
	oTable.$('tr').on('click',function(event) {
		changeSelectedFlightId($(this).attr('data-flight_id'));
	});

	//Form Validation hinzufuegen
	$("#addFlightForm").validate({
		rules: {
			"flightnumber": {
				required: true
			}/*,
			time: "required time"*/
		},
		messages: {
			flightnumber: "Bitte geben Sie die Flugnummer an",
			/*time: {
				required: "Bitte geben Sie die Zeit an",
				time: "Bitte geben Sie eine Zeit in der Form XX:XX:XX ein"
			}*/
		}
	});
	
	//ENTER Key abfangen
	$("#addFlightModal").keypress(function( event ) 
	{
		if ( event.which == 13 ) {
			event.preventDefault();
			if($('#addFlightForm').valid()) 
			{
				addFlight();
			}
		}
	});

	//Button-Listener hinzufuegen
	$('#addFlight').click( function(e) 
	{
		if($('#addFlightForm').valid()) 
		{
			addFlight();
		}
	});
	
	$('#deleteFlight').click( function(e) 
	{
		deleteFlight();
	});

	$('#getEditModalFlight').click( function(e) 
	{
		getEditFlightModal();
	});
});

//die aktuell ausgewaehlte ID speichern und je nachdem die Buttons aktivieren/deaktivieren
function changeSelectedFlightId(flight_id) 
{
	if(selected_flight_id == flight_id) 
	{
		selected_flight_id = null;
		$('#getDeleteModalFlight').attr("disabled", true);
		$('#getEditModalFlight').attr("disabled", true);
	}
	else 
	{
		selected_flight_id = flight_id;
		$('#getDeleteModalFlight').attr("disabled", false);
		$('#getEditModalFlight').attr("disabled", false);
	}
}

function addFlight()
{
	$('#addFlight').attr("disabled", true);
	//Informationen des Fahrers aus dem Form ziehen
	var flightInfo = {};
	jQuery("#addFlightForm :input").each(function(idx,ele)
	{
		if (jQuery(ele).attr('type') == 'checkbox')
		{
			if(jQuery(ele).is(":checked")) {
				flightInfo[jQuery(ele).attr('name')] = 1;
			}
			else {
				flightInfo[jQuery(ele).attr('name')] = 0;
			}
		}
		else
		{
			flightInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
		}
	});

	//Ajax Request schicken
	jQuery.ajax({
		url:'index.php?option=com_cabsystem&controller=add&format=raw&tmpl=component',
		type:'POST',
		data:flightInfo,
		dataType:'JSON',
		success:function(result)
		{
			if ( result.success ){
				//Notification ausgeben
				$.notify(result.msg, "success");
				
				//Zeile zu DataTable hinzufuegen
				var added_indexes = jQuery('#dataTable').dataTable().fnAddData( [
					result.datatable_data.flightnumber,
					result.datatable_data.time,
					result.datatable_data.city_name]
				);
				var added_trs = oTable.fnGetNodes(added_indexes[0]);
				
				//Gerade hinzugefuegter Zeile das Attribut mit der ID geben
				$(added_trs).attr('data-flight_id',result.datatable_data.flight_id);
				
				//Gerade hinzugefuegter Zeile den EventListener anhaengen
				$(added_trs).click(function(event) {
					changeSelectedFlightId($(this).attr('data-flight_id'));
				});
				
				//Modal verstecken
				jQuery("#addFlightModal").modal('hide');
			}else{
				$.notify(result.msg, "error");
			}
		},
		complete:function() 
		{
			$('#addFlight').attr("disabled", false);
		}
	});
}

function deleteFlight(flight_id)
{
	if(selected_flight_id != null) {
		jQuery.ajax({
			url:'index.php?option=com_cabsystem&controller=delete&format=raw&tmpl=component',
			type:'POST',
			data: 'flight_id='+selected_flight_id+'&type=Flight',
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
					selected_flight_id = null;
					$('#getDeleteModalFlight').attr("disabled", true);
					$('#getEditModalFlight').attr("disabled", true);
					
					jQuery("#deleteConfirm").modal('hide');
				}
				else {
					$.notify(result.msg, "error");
				}
			}
		});
	}
}

function getEditFlightModal()
{
	if(selected_flight_id != null) {
		jQuery.ajax({
			url:'index.php?option=com_cabsystem&controller=edit&task=getEditModal&format=raw&tmpl=component',
			type:'POST',
			data: {
				flight_id: selected_flight_id,
				view: 'flight',
				model: 'Flight',
				item: 'flight',
				table: 'flights'	
			},
			dataType: 'JSON',
			success:function(result)
			{
				if(result.html)
				{
					if($('#editFlightModal').length != 0) {
						$('#editFlightModal').replaceWith($(result.html));
					}
					else {
						$(result.html).insertAfter( "#getEditModalFlight" );
					}
					$('#editFlightModal').modal('show');
	
					//Select2 initalisieren
					$("#editForm-city_id").select2();
					
					//Form Validation
					$("#editFlightForm").validate({
						rules: {
							"flightnumber": {
								required: true
							}/*,
							time: "required time"*/
						},
						messages: {
							flightnumber: "Bitte geben Sie die Flugnummer an",
							/*time: {
								required: "Bitte geben Sie die Zeit an",
								time: "Bitte geben Sie eine Zeit in der Form XX:XX:XX ein"
							}*/
						}
					});
					
					//ENTER Key abfangen
					$("#editFlightModal").keypress(function( event ) 
					{
						if ( event.which == 13 ) {
							event.preventDefault();
							if($('#editFlightForm').valid()) 
							{
								editFlight();
							}
						}
					});
					
					$('#editFlight').click( function(e) 
					{
						if($('#editFlightForm').valid()) 
						{
							editFlight();
						}
					});
				}
			}
		});
	}
}

function editFlight()
{
	$('#editFlight').attr("disabled", true);
	var flightInfo = {};
	jQuery("#editFlightForm :input").each(function(idx,ele)
	{
		if (jQuery(ele).attr('type') == 'checkbox')
		{
			if(jQuery(ele).is(":checked")) {
				flightInfo[jQuery(ele).attr('name')] = 1;
			}
			else {
				flightInfo[jQuery(ele).attr('name')] = 0;
			}
		}
		else
		{
			flightInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
		}
	});

	jQuery.ajax({
		url:'index.php?option=com_cabsystem&controller=edit&format=raw&tmpl=component',
		type:'POST',
		data:flightInfo,
		dataType:'JSON',
		success:function(result)
		{
			if ( result.success ){
				$.notify(result.msg, "success");
				var anSelected = getSelectedDataTableRow( oTable );
				if ( anSelected.length !== 0 ) {
					oTable.fnUpdate([
					result.datatable_data.flightnumber,
					result.datatable_data.time,
					result.datatable_data.city_name], anSelected[0],undefined,false,false);
				}
				jQuery("#editFlightModal").modal('hide');
			}else{
				$.notify(result.msg, "error");
			}
		},
		complete:function() 
		{
			$('#editFlight').attr("disabled", false);
		}
	});
}