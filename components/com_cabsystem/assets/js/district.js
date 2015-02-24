var selected_district_id = null;

$(document).ready(function()
{
	//Alle Buttons deaktivieren, die eine Auswahl benoetigen
	$('#getDeleteModalDistrict').attr("disabled", true);
	$('#getEditModalDistrict').attr("disabled", true);
	
	//Listener fuer die TR der DataTable erzeugen
	oTable.$('tr').on('click',function(event) {
		changeSelectedDistrictId($(this).attr('data-district_id'));
	});
	
	//Select2 initalisieren
	$("#addForm-city_id").select2();

	//Form Validation hinzufuegen
	$("#addDistrictForm").validate({
		rules: {
			"zip": {
				required: true,
				digits: true
			},
			"district": {
				required: true
			},
			"city_id": {
				required: true
			}
		},
		messages: {
			zip: "Bitte geben Sie die Postleitzahl an",
			district: "Bitte geben Sie den Namen an",
			city_id: "Bitte geben Sie den Ort an"
		}
	});
	
	//ENTER Key abfangen
	$("#addDistrictModal").keypress(function( event ) 
	{
		if ( event.which == 13 ) {
			event.preventDefault();
			if($('#addDistrictForm').valid()) 
			{
				addDistrict();
			}
		}
	});

	//Button-Listener hinzufuegen
	$('#addDistrict').click( function(e) 
	{
		if($('#addDistrictForm').valid()) 
		{
			addDistrict();
		}
	});
	
	$('#deleteDistrict').click( function(e) 
	{
		deleteDistrict();
	});

	$('#getEditModalDistrict').click( function(e) 
	{
		getEditDistrictModal();
	});

});

//die aktuell ausgewaehlte ID speichern und je nachdem die Buttons aktivieren/deaktivieren
function changeSelectedDistrictId(district_id) 
{
	if(selected_district_id == district_id) 
	{
		selected_district_id = null;
		$('#getDeleteModalDistrict').attr("disabled", true);
		$('#getEditModalDistrict').attr("disabled", true);
	}
	else 
	{
		selected_district_id = district_id;
		$('#getDeleteModalDistrict').attr("disabled", false);
		$('#getEditModalDistrict').attr("disabled", false);
	}
}

function addDistrict()
{
	$('#addDistrict').attr("disabled", true);
	//Informationen des Fahrers aus dem Form ziehen
	var districtInfo = {};
	jQuery("#addDistrictForm :input").each(function(idx,ele)
	{
		if (jQuery(ele).attr('type') == 'checkbox')
		{
			if(jQuery(ele).is(":checked")) {
				districtInfo[jQuery(ele).attr('name')] = 1;
			}
			else {
				districtInfo[jQuery(ele).attr('name')] = 0;
			}
		}
		else
		{
			districtInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
		}
	});

	//Ajax Request schicken
	jQuery.ajax({
		url:'index.php?option=com_cabsystem&controller=add&format=raw&tmpl=component',
		type:'POST',
		data:districtInfo,
		dataType:'JSON',
		success:function(result)
		{
			if ( result.success ){
				//Notification ausgeben
				$.notify(result.msg, "success");
				
				//Zeile zu DataTable hinzufuegen
				var data_array = [result.datatable_data.zip, result.datatable_data.district,result.datatable_data.city_name];
				jQuery.each(result.datatable_data.cartype_prices, function( index, value ) {
					if(value.price < 0) {
						value.price = "Anfrage";
					}
					data_array.push(value.price);
				});
				jQuery.each(result.datatable_data.cartype_prices_additional_address, function( index, value ) {
					if(value.additional_address_price < 0) {
						value.additional_address_price = "Anfrage";
					}
					data_array.push(value.additional_address_price);
				});
				var added_indexes = jQuery('#dataTable').dataTable().fnAddData(data_array);
				var added_trs = oTable.fnGetNodes(added_indexes[0]);
				
				//Gerade hinzugefuegter Zeile das Attribut mit der ID geben
				$(added_trs).attr('data-district_id',result.datatable_data.district_id);
				
				//Gerade hinzugefuegter Zeile den EventListener anhaengen
				$(added_trs).click(function(event) {
					changeSelectedDistrictId($(this).attr('data-district_id'));
				});
				
				//Modal verstecken
				jQuery("#addDistrictModal").modal('hide');
			}else{
				$.notify(result.msg, "error");
			}
		},
		complete:function() 
		{
			$('#addDistrict').attr("disabled", false);
		}
	});
}

function deleteDistrict(district_id)
{
	if(selected_district_id != null) {
		jQuery.ajax({
			url:'index.php?option=com_cabsystem&controller=delete&format=raw&tmpl=component',
			type:'POST',
			data: 'district_id='+selected_district_id+'&type=District',
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
					selected_district_id = null;
					$('#getDeleteModalDistrict').attr("disabled", true);
					$('#getEditModalDistrict').attr("disabled", true);
					
					jQuery("#deleteConfirm").modal('hide');
				}
				else {
					$.notify(result.msg, "error");
				}
			}
		});
	}
}

function getEditDistrictModal()
{
	if(selected_district_id != null) {
		jQuery.ajax({
			url:'index.php?option=com_cabsystem&controller=edit&task=getEditModal&format=raw&tmpl=component',
			type:'POST',
			data: {
				district_id: selected_district_id,
				view: 'district',
				model: 'District',
				item: 'district',
				table: 'districts'	
			},
			dataType: 'JSON',
			success:function(result)
			{
				if(result.html)
				{
					if($('#editDistrictModal').length != 0) {
						$('#editDistrictModal').replaceWith($(result.html));
					}
					else {
						$(result.html).insertAfter( "#getEditModalDistrict" );
					}
					$('#editDistrictModal').modal('show');
					
					//Select2 initalisieren
					$("#editForm-city_id").select2();
					
					//Form Validation
					$("#addDistrictForm").validate({
						rules: {
							"zip": {
								required: true,
								digits: true
							},
							"district": {
								required: true
							},
							"city_id": {
								required: true
							}
						},
						messages: {
							zip: "Bitte geben Sie die Postleitzahl an",
							district: "Bitte geben Sie den Namen an",
							city_id: "Bitte geben Sie den Ort an"
						}
					});
					
					//ENTER Key abfangen
					$("#editDistrictModal").keypress(function( event ) 
					{
						if ( event.which == 13 ) {
							event.preventDefault();
							if($('#editDistrictForm').valid()) 
							{
								editDistrict();
							}
						}
					});
					
					$('#editDistrict').click( function(e) 
					{
						if($('#editDistrictForm').valid()) 
						{
							editDistrict();
						}
					});
				}
			}
		});
	}
}

function editDistrict()
{
	$('#editDistrict').attr("disabled", true);
	var districtInfo = {};
	jQuery("#editDistrictForm :input").each(function(idx,ele)
	{
		if (jQuery(ele).attr('type') == 'checkbox')
		{
			if(jQuery(ele).is(":checked")) {
				districtInfo[jQuery(ele).attr('name')] = 1;
			}
			else {
				districtInfo[jQuery(ele).attr('name')] = 0;
			}
		}
		else
		{
			districtInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
		}
	});

	jQuery.ajax({
		url:'index.php?option=com_cabsystem&controller=edit&format=raw&tmpl=component',
		type:'POST',
		data:districtInfo,
		dataType:'JSON',
		success:function(result)
		{
			if ( result.success ){
				$.notify(result.msg, "success");
				
				var anSelected = getSelectedDataTableRow( oTable );
				if ( anSelected.length !== 0 ) {
					var data_array = [result.datatable_data.zip,result.datatable_data.district,result.datatable_data.city_name];
					jQuery.each(result.datatable_data.cartype_prices, function( index, value ) {
						if(value.price < 0) {
							value.price = "Anfrage";
						}
						data_array.push(value.price);
					});
					jQuery.each(result.datatable_data.cartype_prices_additional_address, function( index, value ) {
						if(value.additional_address_price < 0) {
							value.additional_address_price = "Anfrage";
						}
						data_array.push(value.additional_address_price);
					});
					oTable.fnUpdate(data_array, anSelected[0],undefined,false,false);
				}
				jQuery("#editDistrictModal").modal('hide');
			}else{
				$.notify(result.msg, "error");
			}
		},
		complete:function() 
		{
			$('#editDistrict').attr("disabled", false);
		}
	});
}