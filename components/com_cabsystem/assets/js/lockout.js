var selected_lockout_id = null;
var date_search_flag = "asc";

$(document).ready(function()
{
	oTable.fnSetColumnVis( 0, false );

	oTable.fnSort( [[0,'asc']] );

	//Nach Datum sortieren einbauen
	table = $('#dataTable').DataTable();
	var header = table.column(1).header();
	$(header).on('click',function(event) {
		if(date_search_flag == "desc") {
			date_search_flag = "asc";
		}
		else if(date_search_flag == "asc") {
			date_search_flag = "desc";
		}
		oTable.fnSort( [0,date_search_flag] );
	});

	//Alle Buttons deaktivieren, die eine Auswahl benoetigen
	$('#getDeleteModalLockout').attr("disabled", true);
	$('#getEditModalLockout').attr("disabled", true);
	$('#lockLockout').attr("disabled", true);
	$('#unlockLockout').attr("disabled", true);

	//Listener fuer die TR der DataTable erzeugen
	oTable.$('tr').on('click',function(event) {
		changeSelectedLockoutId($(this).attr('data-lockout_id'));
	});

	initForm('add');

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

});

function initForm(type) {
	//Select2 + Datepicker initalisieren

	var min_date = '1/1/1900';
	if($('#'+type+'Form-date-picker').data('min-date')) {
		min_date = moment().subtract('days', 1);
	}

	//Datetimepicker initialisieren
	$('#'+type+'Form-date-picker').datetimepicker({
		language: 'de',
		pick12HourFormat: false,
		minuteStepping:5,
		showToday: true,
		defaultDate:"",
		useCurrent: true,
		pickTime: false,
		minDate:min_date
	});

	$("#"+type+"Form-hour").select2();
	$("#"+type+"Form-type").select2();

	//Form Validation hinzufuegen
	$("#"+type+"LockoutForm").validate({
		rules: {
			"date": {
				required: true
			},
			"hour": {
				required: true
			}
		},
		messages: {
			date: "Bitte geben Sie das Datum an",
			hour: "Bitte geben Sie den Zeitraum an"
		}
	});
}

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
	var lockoutInfo = $("#addLockoutForm").serialize();

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

				var tr = $(result.tr);

				//Zeile das Attribut mit der ID geben
				tr.attr('data-lockout_id',result.datatable_data.lockout_id);

				//Zeile den EventListener anhaengen
				tr.click(function(event) {
					changeSelectedLockoutId($(this).attr('data-lockout_id'));
				});

				oTable.fnAddTr(tr[0]);
				
				//Modal verstecken
				$("#addLockoutModal").insertAfter('getAddModalOrder');
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
						oTable.fnUpdate(icon_inactive, anSelected[0], 3,false,false);
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
						oTable.fnUpdate(icon_active, anSelected[0], 3,false,false);
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

					initForm('edit');
					
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
	var lockoutInfo = $("#editLockoutForm").serialize();

	jQuery.ajax({
		url:'index.php?option=com_cabsystem&controller=edit&format=raw&tmpl=component',
		type:'POST',
		data:lockoutInfo,
		dataType:'JSON',
		success:function(result)
		{
			if ( result.success ){
				//Notification ausgeben
				$.notify(result.msg, "success");

				var anSelected = getSelectedDataTableRow( oTable );
				if ( anSelected.length !== 0 ) {
					oTable.fnDeleteRow( anSelected[0] );
				}

				//Variable der aktuell selektierten TR auf null setzen und Buttons disablen
				selected_lockout_id = null;
				$('#getDeleteModalLockout').attr("disabled", true);
				$('#getEditModalLockout').attr("disabled", true);
				$('#cancelLockout').attr("disabled", true);

				var tr = $(result.tr);

				//Zeile das Attribut mit der ID geben
				tr.attr('data-lockout_id',result.datatable_data.lockout_id);

				//Zeile den EventListener anhaengen
				tr.click(function(event) {
					changeSelectedLockoutId($(this).attr('data-lockout_id'));
				});

				oTable.fnAddTr(tr[0]);

				//Modal verstecken
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