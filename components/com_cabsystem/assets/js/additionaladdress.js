var selected_additionaladdress_id = null;

$(document).ready(function()
{
	//Alle Buttons deaktivieren, die eine Auswahl benoetigen
	$('#getDeleteModalAdditionaladdress').attr("disabled", true);
	$('#getEditModalAdditionaladdress').attr("disabled", true);
	
	//Listener fuer die TR der DataTable erzeugen
	oTable.$('tr').on('click',function(event) {
		changeSelectedAdditionaladdressId($(this).attr('data-additionaladdress_id'));
	});

	//Spinner initialisieren
	$("input[name='districts']").TouchSpin();
	
	//Form Validation hinzufuegen
	$("#addAdditionaladdressForm").validate({
		rules: {
			"name": {
				required: true,
			},
			"price": {
				required: true
			}
		},
		messages: {
			name: "Bitte geben Sie den Namen an",
			price: "Bitte geben Sie alle Preise an"
		}
	});
	
	//ENTER Key abfangen
	$("#addAdditionaladdressModal").keypress(function( event ) 
	{
		if ( event.which == 13 ) {
			event.preventDefault();
			if($('#addAdditionaladdressForm').valid()) 
			{
				addAdditionaladdress();
			}
		}
	});

	//Button-Listener hinzufuegen
	$('#addAdditionaladdress').click( function(e) 
	{
		if($('#addAdditionaladdressForm').valid()) 
		{
			addAdditionaladdress();
		}
	});
	
	$('#deleteAdditionaladdress').click( function(e) 
	{
		deleteAdditionaladdress();
	});

	$('#getEditModalAdditionaladdress').click( function(e) 
	{
		getEditAdditionaladdressModal();
	});

});

//die aktuell ausgewaehlte ID speichern und je nachdem die Buttons aktivieren/deaktivieren
function changeSelectedAdditionaladdressId(additionaladdress_id) 
{
	if(selected_additionaladdress_id == additionaladdress_id) 
	{
		selected_additionaladdress_id = null;
		$('#getDeleteModalAdditionaladdress').attr("disabled", true);
		$('#getEditModalAdditionaladdress').attr("disabled", true);
	}
	else 
	{
		selected_additionaladdress_id = additionaladdress_id;
		$('#getDeleteModalAdditionaladdress').attr("disabled", false);
		$('#getEditModalAdditionaladdress').attr("disabled", false);
	}
}

function addAdditionaladdress()
{
	$('#addAdditionaladdress').attr("disabled", true);
	//Informationen des Fahrers aus dem Form ziehen
	var additionaladdressInfo = {};
	jQuery("#addAdditionaladdressForm :input").each(function(idx,ele)
	{
		if (jQuery(ele).attr('type') == 'checkbox')
		{
			if(jQuery(ele).is(":checked")) {
				additionaladdressInfo[jQuery(ele).attr('name')] = 1;
			}
			else {
				additionaladdressInfo[jQuery(ele).attr('name')] = 0;
			}
		}
		else
		{
			additionaladdressInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
		}
	});

	//Ajax Request schicken
	jQuery.ajax({
		url:'index.php?option=com_cabsystem&controller=add&format=raw&tmpl=component',
		type:'POST',
		data:additionaladdressInfo,
		dataType:'JSON',
		success:function(result)
		{
			if ( result.success ){
				//Notification ausgeben
				$.notify(result.msg, "success");
				
				//Zeile zu DataTable hinzufuegen
				var data_array = [result.datatable_data.name,result.datatable_data.districts];
				jQuery.each(result.datatable_data.cartype_prices, function( index, value ) {
					data_array.push(value.price);
				});
				var added_indexes = jQuery('#dataTable').dataTable().fnAddData(data_array);
				var added_trs = oTable.fnGetNodes(added_indexes[0]);
				
				//Gerade hinzugefuegter Zeile das Attribut mit der ID geben
				$(added_trs).attr('data-additionaladdress_id',result.datatable_data.additionaladdress_id);
				
				//Gerade hinzugefuegter Zeile den EventListener anhaengen
				$(added_trs).click(function(event) {
					changeSelectedAdditionaladdressId($(this).attr('data-additionaladdress_id'));
				});
				
				//Modal verstecken
				jQuery("#addAdditionaladdressModal").modal('hide');
			}else{
				$.notify(result.msg, "error");
			}
		},
		complete:function() 
		{
			$('#addAdditionaladdress').attr("disabled", false);
		}
	});
}

function deleteAdditionaladdress(additionaladdress_id)
{
	if(selected_additionaladdress_id != null) {
		jQuery.ajax({
			url:'index.php?option=com_cabsystem&controller=delete&format=raw&tmpl=component',
			type:'POST',
			data: 'additionaladdress_id='+selected_additionaladdress_id+'&type=Additionaladdress',
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
					selected_additionaladdress_id = null;
					$('#getDeleteModalAdditionaladdress').attr("disabled", true);
					$('#getEditModalAdditionaladdress').attr("disabled", true);
					
					jQuery("#deleteConfirm").modal('hide');
				}
				else {
					$.notify(result.msg, "error");
				}
			}
		});
	}
}

function getEditAdditionaladdressModal()
{
	if(selected_additionaladdress_id != null) {
		jQuery.ajax({
			url:'index.php?option=com_cabsystem&controller=edit&task=getEditModal&format=raw&tmpl=component',
			type:'POST',
			data: {
				additionaladdress_id: selected_additionaladdress_id,
				view: 'additionaladdress',
				model: 'Additionaladdress',
				item: 'additionaladdress',
				table: 'additionaladdresses'	
			},
			dataType: 'JSON',
			success:function(result)
			{
				if(result.html)
				{
					if($('#editAdditionaladdressModal').length != 0) {
						$('#editAdditionaladdressModal').replaceWith($(result.html));
					}
					else {
						$(result.html).insertAfter( "#getEditModalAdditionaladdress" );
					}
					$('#editAdditionaladdressModal').modal('show');
					
					//Form Validation
					$("#addAdditionaladdressForm").validate({
						rules: {
							"name": {
								required: true,
							},
							"price": {
								required: true
							}
						},
						messages: {
							name: "Bitte geben Sie den Namen an",
							price: "Bitte geben Sie alle Preise an"
						}
					});
					
					//ENTER Key abfangen
					$("#editAdditionaladdressModal").keypress(function( event ) 
					{
						if ( event.which == 13 ) {
							event.preventDefault();
							if($('#editAdditionaladdressForm').valid()) 
							{
								editAdditionaladdress();
							}
						}
					});
					
					$('#editAdditionaladdress').click( function(e) 
					{
						if($('#editAdditionaladdressForm').valid()) 
						{
							editAdditionaladdress();
						}
					});

					//Spinner initialisieren
					$("input[name='districts']").TouchSpin();
				}
			}
		});
	}
}

function editAdditionaladdress()
{
	$('#editAdditionaladdress').attr("disabled", true);
	var additionaladdressInfo = {};
	jQuery("#editAdditionaladdressForm :input").each(function(idx,ele)
	{
		if (jQuery(ele).attr('type') == 'checkbox')
		{
			if(jQuery(ele).is(":checked")) {
				additionaladdressInfo[jQuery(ele).attr('name')] = 1;
			}
			else {
				additionaladdressInfo[jQuery(ele).attr('name')] = 0;
			}
		}
		else
		{
			additionaladdressInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
		}
	});

	jQuery.ajax({
		url:'index.php?option=com_cabsystem&controller=edit&format=raw&tmpl=component',
		type:'POST',
		data:additionaladdressInfo,
		dataType:'JSON',
		success:function(result)
		{
			if ( result.success ){
				$.notify(result.msg, "success");
				
				var anSelected = getSelectedDataTableRow( oTable );
				if ( anSelected.length !== 0 ) {
					var data_array = [result.datatable_data.name,result.datatable_data.districts];
					jQuery.each(result.datatable_data.cartype_prices, function( index, value ) {
						data_array.push(value.price);
					});
					oTable.fnUpdate(data_array, anSelected[0],undefined,false,false);
				}
				jQuery("#editAdditionaladdressModal").modal('hide');
			}else{
				$.notify(result.msg, "error");
			}
		},
		complete:function() 
		{
			$('#editAdditionaladdress').attr("disabled", false);
		}
	});
}