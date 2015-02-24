var selected_city_id = null;

$(document).ready(function()
{
	//Alle Buttons deaktivieren, die eine Auswahl benoetigen
	$('#getDeleteModalCity').attr("disabled", true);
	$('#getEditModalCity').attr("disabled", true);
	
	//Listener fuer die TR der DataTable erzeugen
	oTable.$('tr').on('click',function(event) {
		changeSelectedCityId($(this).attr('data-city_id'));
	});

	//Form Validation hinzufuegen
	$("#addCityForm").validate({
		rules: {
			"name": {
				required: true,
			}
		},
		messages: {
			name: "Bitte geben Sie den Namen an"
		}
	});
		
	//ENTER Key abfangen
	$("#addCityModal").keypress(function( event ) 
	{
		if ( event.which == 13 ) {
			event.preventDefault();
			if($('#addCityForm').valid()) 
			{
				addCity();
			}
		}
	});
	
	//Button-Listener hinzufuegen
	$('#addCity').click( function(e) 
	{
		if($('#addCityForm').valid()) 
		{
			addCity();
		}
	});
	
	$('#deleteCity').click( function(e) 
	{
		deleteCity();
	});

	$('#getEditModalCity').click( function(e) 
	{
		getEditCityModal();
	});

});

//die aktuell ausgewaehlte ID speichern und je nachdem die Buttons aktivieren/deaktivieren
function changeSelectedCityId(city_id) 
{
	if(selected_city_id == city_id) 
	{
		selected_city_id = null;
		$('#getDeleteModalCity').attr("disabled", true);
		$('#getEditModalCity').attr("disabled", true);
	}
	else 
	{
		selected_city_id = city_id;
		$('#getDeleteModalCity').attr("disabled", false);
		$('#getEditModalCity').attr("disabled", false);
	}
}

function addCity()
{
	$('#addCity').attr("disabled", true);
	//Informationen der Stadt aus dem Form ziehen
	var cityInfo = {};
	jQuery("#addCityForm :input").each(function(idx,ele)
	{
		if (jQuery(ele).attr('type') == 'checkbox')
		{
			if(jQuery(ele).is(":checked")) {
				cityInfo[jQuery(ele).attr('name')] = 1;
			}
			else {
				cityInfo[jQuery(ele).attr('name')] = 0;
			}
		}
		else
		{
			cityInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
		}
	});

	//Ajax Request schicken
	jQuery.ajax({
		url:'index.php?option=com_cabsystem&controller=add&format=raw&tmpl=component',
		type:'POST',
		data:cityInfo,
		dataType:'JSON',
		success:function(result)
		{
			if ( result.success ){
				//Notification ausgeben
				$.notify(result.msg, "success");
				
				//Zeile zu DataTable hinzufuegen
				var added_indexes = jQuery('#dataTable').dataTable().fnAddData( [
					result.datatable_data.name]
				);
				var added_trs = oTable.fnGetNodes(added_indexes[0]);
				
				//Gerade hinzugefuegter Zeile das Attribut mit der ID geben
				$(added_trs).attr('data-city_id',result.datatable_data.city_id);
				
				//Gerade hinzugefuegter Zeile den EventListener anhaengen
				$(added_trs).click(function(event) {
					changeSelectedCityId($(this).attr('data-city_id'));
				});
				
				//Modal verstecken
				jQuery("#addCityModal").modal('hide');
			}else{
				$.notify(result.msg, "error");
			}
		},
		complete:function() 
		{
			$('#addCity').attr("disabled", false);
		}
	});
}

function deleteCity(city_id)
{
	if(selected_city_id != null) {
		jQuery.ajax({
			url:'index.php?option=com_cabsystem&controller=delete&format=raw&tmpl=component',
			type:'POST',
			data: 'city_id='+selected_city_id+'&type=City',
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
					selected_city_id = null;
					$('#getDeleteModalCity').attr("disabled", true);
					$('#getEditModalCity').attr("disabled", true);
					
					jQuery("#deleteConfirm").modal('hide');
				}
				else {
					$.notify(result.msg, "error");
				}
			}
		});
	}
}

function getEditCityModal()
{
	if(selected_city_id != null) {
		jQuery.ajax({
			url:'index.php?option=com_cabsystem&controller=edit&task=getEditModal&format=raw&tmpl=component',
			type:'POST',
			data: {
				city_id: selected_city_id,
				view: 'city',
				model: 'City',
				item: 'city',
				table: 'cities'	
			},
			dataType: 'JSON',
			success:function(result)
			{
				if(result.html)
				{
					if($('#editCityModal').length != 0) {
						$('#editCityModal').replaceWith($(result.html));
					}
					else {
						$(result.html).insertAfter( "#getEditModalCity" );
					}
					$('#editCityModal').modal('show');
					
					//Form Validation
					$("#editCityForm").validate({
						rules: {
							"name": {
								required: true,
							}
						},
						messages: {
							name: "Bitte geben Sie den Namen an"
						}
					});
					
					//ENTER Key abfangen
					$("#editCityModal").keypress(function( event ) 
					{
						if ( event.which == 13 ) {
							event.preventDefault();
							if($('#editCityForm').valid()) 
							{
								editCity();
							}
						}
					});
					
					$('#editCity').click( function(e) 
					{
						if($('#editCityForm').valid()) 
						{
							editCity();
						}
					});
				}
			}
		});
	}
}

function editCity()
{
	$('#editCity').attr("disabled", true);
	var cityInfo = {};
	jQuery("#editCityForm :input").each(function(idx,ele)
	{
		if (jQuery(ele).attr('type') == 'checkbox')
		{
			if(jQuery(ele).is(":checked")) {
				cityInfo[jQuery(ele).attr('name')] = 1;
			}
			else {
				cityInfo[jQuery(ele).attr('name')] = 0;
			}
		}
		else
		{
			cityInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
		}
	});

	jQuery.ajax({
		url:'index.php?option=com_cabsystem&controller=edit&format=raw&tmpl=component',
		type:'POST',
		data:cityInfo,
		dataType:'JSON',
		success:function(result)
		{
			if ( result.success ){
				$.notify(result.msg, "success");
				var anSelected = getSelectedDataTableRow( oTable );
				if ( anSelected.length !== 0 ) {
					oTable.fnUpdate([
					result.datatable_data.name], anSelected[0],undefined,false,false);
				}
				jQuery("#editCityModal").modal('hide');
			}else{
				$.notify(result.msg, "error");
			}
		},
		complete:function() 
		{
			$('#editCity').attr("disabled", false);
		}
	});
}