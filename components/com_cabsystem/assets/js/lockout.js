var selected_lockout_id = null;

$(document).ready(function()
{
	//Alle Buttons deaktivieren, die eine Auswahl benoetigen
	$('#getDeleteModalLockout').attr("disabled", true);
	$('#getEditModalLockout').attr("disabled", true);
	$('#lockLockout').attr("disabled", true);
	$('#unlockLockout').attr("disabled", true);

	//Select2 initalisieren
	$("#addForm-cartype_id").select2();

	//Listener fuer die TR der DataTable erzeugen
	oTable.$('tr').on('click',function(event) {
		changeSelectedLockoutId($(this).attr('data-lockout_id'));
	});

	//Form Validation hinzufuegen
	$("#addLockoutForm").validate({
		rules: {
			"name": {
				required: true
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
	$("#addLockoutModal").keypress(function( event )
	{
		if ( event.which == 13 ) {
			event.preventDefault();
			if($('#addLockoutForm').valid())
			{
				addLockout();
			}
		}
	});

	//Button-Listener hinzufuegen
	$('#addLockout').click( function(e)
	{
		if($('#addLockoutForm').valid())
		{
			addLockout();
		}
	});

	$('#deleteLockout').click( function(e)
	{
		deleteLockout();
	});

	$('#getEditModalLockout').click( function(e)
	{
		getEditLockoutModal();
	});

	$('#lockLockout').click( function(e) 
	{
		lockLockout();
	});


	$('#unlockLockout').click( function(e) 
	{
		unlockLockout();
	});

});

//die aktuell ausgewaehlte ID speichern und je nachdem die Buttons aktivieren/deaktivieren
function changeSelectedLockoutId(lockout_id) 
{
	if(selected_lockout_id == lockout_id) 
	{
		selected_lockout_id = null;
		$('#getDeleteModalLockout').attr("disabled", true);
		$('#getEditModalLockout').attr("disabled", true);
		$('#lockLockout').attr("disabled", true);
		$('#unlockLockout').attr("disabled", true);
	}
	else 
	{
		selected_lockout_id = lockout_id;
		$('#getDeleteModalLockout').attr("disabled", false);
		$('#getEditModalLockout').attr("disabled", false);
		$('#lockLockout').attr("disabled", false);
		$('#unlockLockout').attr("disabled", false);
	}
}

function addLockout()
{
	$('#addLockout').attr("disabled", true);
	//Informationen des Fahrers aus dem Form ziehen
	var lockoutInfo = {};
	jQuery("#addLockoutForm :input").each(function(idx,ele)
	{
		if (jQuery(ele).attr('type') == 'checkbox')
		{
			if(jQuery(ele).is(":checked")) {
				lockoutInfo[jQuery(ele).attr('name')] = 1;
			}
			else {
				lockoutInfo[jQuery(ele).attr('name')] = 0;
			}
		}
		else
		{
			lockoutInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
		}
	});

	//Ajax Request schicken
	jQuery.ajax({
		url:'index.php?option=com_cabsystem&controller=add&format=raw&tmpl=component',
		type:'POST',
		data:lockoutInfo,
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
				$(added_trs).attr('data-lockout_id',result.datatable_data.lockout_id);
				
				//Gerade hinzugefuegter Zeile den EventListener anhaengen
				$(added_trs).click(function(event) {
					changeSelectedLockoutId($(this).attr('data-lockout_id'));
				});
				
				//Modal verstecken
				jQuery("#addLockoutModal").modal('hide');
			}else{
				$.notify(result.msg, "error");
			}
		},
		complete:function() 
		{
			$('#addLockout').attr("disabled", false);
		}
	});
}

function deleteLockout(lockout_id)
{
	if(selected_lockout_id != null) {
		jQuery.ajax({
			url:'index.php?option=com_cabsystem&controller=delete&format=raw&tmpl=component',
			type:'POST',
			data: 'lockout_id='+selected_lockout_id+'&type=Lockout',
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
					selected_lockout_id = null;
					$('#getDeleteModalLockout').attr("disabled", true);
					$('#getEditModalLockout').attr("disabled", true);
					$('#lockLockout').attr("disabled", true);
					$('#unlockLockout').attr("disabled", true);
					
					jQuery("#deleteConfirm").modal('hide');
				}
				else {
					$.notify(result.msg, "error");
				}
			}
		});
	}
}

function lockLockout(lockout_id)
{
	if(selected_lockout_id != null) {
		jQuery.ajax({
			url:'index.php?option=com_cabsystem&controller=lock&format=raw&tmpl=component',
			type:'POST',
			data: 'lockout_id='+selected_lockout_id+'&type=Lockout',
			dataType: 'JSON',
			success:function(result)
			{
				if(result.success)
				{
					$.notify(result.msg, "success");
					var anSelected = getSelectedDataTableRow( oTable );
					if ( anSelected.length !== 0 ) {
						oTable.fnUpdate(icon_inactive, anSelected[0], 1,false,false);
					}
				}
				else {
					$.notify(result.msg, "error");
				}
			}
		});
	}
}

function unlockLockout(lockout_id)
{
	if(selected_lockout_id != null) {
		jQuery.ajax({
			url:'index.php?option=com_cabsystem&controller=unlock&format=raw&tmpl=component',
			type:'POST',
			data: 'lockout_id='+selected_lockout_id+'&type=Lockout',
			dataType: 'JSON',
			success:function(result)
			{
				if(result.success)
				{
					$.notify(result.msg, "success");
					var anSelected = getSelectedDataTableRow( oTable );
					if ( anSelected.length !== 0 ) {
						oTable.fnUpdate(icon_active, anSelected[0], 1,false,false);
					}
				}
				else {
					$.notify(result.msg, "error");
				}
			}
		});
	}
}

function getEditLockoutModal()
{
	if(selected_lockout_id != null) {
		jQuery.ajax({
			url:'index.php?option=com_cabsystem&controller=edit&task=getEditModal&format=raw&tmpl=component',
			type:'POST',
			data: {
				lockout_id: selected_lockout_id,
				view: 'lockout',
				model: 'Lockout',
				item: 'lockout',
				table: 'lockouts'	
			},
			dataType: 'JSON',
			success:function(result)
			{
				if(result.html)
				{
					if($('#editLockoutModal').length != 0) {
						$('#editLockoutModal').replaceWith($(result.html));
					}
					else {
						$(result.html).insertAfter( "#getEditModalLockout" );
					}
					$('#editLockoutModal').modal('show');
					
					//Select2 initalisieren
					$("#editForm-cartype_id").select2();
					
					//Form Validation
					$("#editLockoutForm").validate({
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
					$("#editLockoutModal").keypress(function( event ) 
					{
						if ( event.which == 13 ) {
							event.preventDefault();
							if($('#editLockoutForm').valid()) 
							{
								editLockout();
							}
						}
					});
					
					$('#editLockout').click( function(e) 
					{
						if($('#editLockoutForm').valid()) 
						{
							editLockout();
						}
					});
				}
			}
		});
	}
}

function editLockout()
{
	$('#editLockout').attr("disabled", true);
	var lockoutInfo = {};
	jQuery("#editLockoutForm :input").each(function(idx,ele)
	{
		if (jQuery(ele).attr('type') == 'checkbox')
		{
			if(jQuery(ele).is(":checked")) {
				lockoutInfo[jQuery(ele).attr('name')] = 1;
			}
			else {
				lockoutInfo[jQuery(ele).attr('name')] = 0;
			}
		}
		else
		{
			lockoutInfo[jQuery(ele).attr('name')] = jQuery(ele).val();
		}
	});

	jQuery.ajax({
		url:'index.php?option=com_cabsystem&controller=edit&format=raw&tmpl=component',
		type:'POST',
		data:lockoutInfo,
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
				jQuery("#editLockoutModal").modal('hide');
			}else{
				$.notify(result.msg, "error");
			}
		},
		complete:function() 
		{
			$('#editLockout').attr("disabled", false);
		}
	});
}