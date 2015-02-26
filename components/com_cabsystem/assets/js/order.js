var selected_order_id = null;
var selected_driver_id = null;
		
var from_data_district = new Array();
var from_data_street = new Array();
from_data_district['add'] = new Array();
from_data_street['add'] = new Array();
from_data_district['edit'] = new Array();
from_data_street['edit'] = new Array();
var from_data_flightnumber = new Array();
from_data_flightnumber['add'] = new Array();
from_data_flightnumber['edit'] = new Array();
var from_data_postorder_flightnumber = new Array();
from_data_postorder_flightnumber['add'] = new Array();
from_data_postorder_flightnumber['edit'] = new Array();
var act_price = 0;
var night_price = 0;
var lockouts_array = new Array();

var INVALID_SAME_TYPE = 'Eine Fahrt von dieser Adresse zur gleichen ist nicht zulässig!';
var INVALID_NO_AIRPORT = 'Der Flughafen muss entweder als Abfahrtspunkt oder Ziel angegeben werden.';
var INVALID_A_TO_B = 'Fahrten von A nach B sind nicht möglich. Bitte wählen Sie den Flughafen als Ziel oder Abholort aus.';

var LANG_PRICE = "Preis: ";
var LANG_PRICE_WILL_BE_CALCULATED = "wird berechnet";
var LANG_PRICE_ON_REQUEST = "auf Anfrage";

var all_districts_array;
var date_search_flag = "asc";

function format(item) { return item.tag; }
		
$(document).ready(function()
{
	if(oTable) {
		oTable.fnSetColumnVis( 1, false );
		oTable.fnSetColumnVis( 4, false );
		oTable.fnSetColumnVis( 6, false );
		
		oTable.yadcf([
			{
				column_number : 1,
				select_type: 'select2',
				select_type_options: {width: '150px'},
				filter_container_id: "advanced_filter1",
				filter_default_label: "Status filtern"
			},
			{
				column_number : 6,
				select_type: 'select2',
				select_type_options: {width: '150px'},
				filter_container_id: "advanced_filter2",
				data: [{
					value: 'abgelaufen',
					label: 'abgelaufen'
				}, {
					value: 'zukünftig',
					label: 'zukünftig'
				}],
				filter_default_label: "Fahrten filtern"
			}
		]);
		
		yadcf.exFilterColumn(oTable, [[6, 'zukünftig']]);
		
		oTable.fnSort( [ [4,'asc'], [1,'asc'] ] );
		
		//Nach Datum sortieren einbauen
		table = $('#dataTable').DataTable();
		var header = table.column(5).header();
		$(header).on('click',function(event) {
			oTable.fnSort( [4,date_search_flag] );
			if(date_search_flag == "desc") {
				date_search_flag = "asc";
			}
			else if(date_search_flag == "asc") {
				date_search_flag = "desc";
			}
		});
	
		//Listener fuer die TR der DataTable erzeugen
		oTable.$('tr').on('click',function(event) {
			changeSelectedOrderId($(this).attr('data-order_id'),$(this).attr('data-driver_id'));
		});
	}
	
	//Alle Buttons deaktivieren, die eine Auswahl benoetigen
	$('#getDeleteModalOrder').attr("disabled", true);
	$('#getEditModalOrder').attr("disabled", true);
	$('#getCopyFromModalOrder').attr("disabled", true);
	$('#getCopyToModalOrder').attr("disabled", true);
	$('#getSetDriverModalOrder').attr("disabled", true);
	$('#cancelOrder').attr("disabled", true);
	
	/*var table = $("#dataTable").DataTable();
	console.log(table);
		
	$("#dataTable th").each( function ( i ) {
        var select = $('<select><option value=""></option></select>')
            .appendTo( $(this).empty() )
            .on( 'change', function () {
                $("#dataTable").DataTable().column(i)
                    .search( '^'+$(this).val()+'$', true, false )
                    .draw();
            } );
 
        $("#dataTable").DataTable().column(i).data().unique().sort().each( function ( d, j ) {
            select.append( '<option value="'+d+'">'+d+'</option>' )
        } );
    } );*/
	
	initForm('add');
	
	//DELETE Button
	$('#deleteOrder').click( function(e) 
	{
		deleteOrder();
	});

	//EDIT Button
	$('#getEditModalOrder').click( function(e) 
	{
		getEditOrderModal();
	});
	//COPY FROM Button
	$('#getCopyFromModalOrder').click( function(e)
	{
		getCopyFromOrderModal();
	});
	//COPY TO Button
	$('#getCopyToModalOrder').click( function(e)
	{
		getCopyToOrderModal();
	});

	//SETDRIVER Button
	$('#getSetDriverModalOrder').click( function(e) 
	{
		getSetDriverOrderModal();
	});

	//FAHRT STORNIEREN Button
	$('#cancelOrder').click( function(e) 
	{
		cancelOrder();
	});
});

function cancelOrder()
{
	if(selected_order_id != null) {
		jQuery.ajax({
			url:'index.php?option=com_cabsystem&controller=ajax&task=cancelOrder&format=raw&tmpl=component',
			type:'POST',
			data: 'order_id='+selected_order_id,
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
					selected_order_id = null;
					selected_driver_id = null;
					$('#getDeleteModalOrder').attr("disabled", true);
					$('#getEditModalOrder').attr("disabled", true);
					$('#getCopyFromModalOrder').attr("disabled", true);
					$('#getCopyToModalOrder').attr("disabled", true);
					$('#getSetDriverModalOrder').attr("disabled", true);
					$('#cancelOrder').attr("disabled", true);
					var tr = $(result.tr);
					
					//Zeile das Attribut mit der ID geben
					tr.attr('data-order_id',result.datatable_data.order_id);
					
					//Zeile den EventListener anhaengen
					tr.click(function(event) {
						changeSelectedOrderId($(this).attr('data-order_id'),$(this).attr('data-driver_id'));
					});
					
					oTable.fnAddTr(tr[0]);
				}
				else {
					$.notify(result.msg, "error");
				}
			}
		});
	}
}

function setDriver(orderid, driverid)
{
	if(orderid == null) {
		orderid = selected_order_id;
	}
	if(driverid == null) {
		driverid = $('#setDriverForm-driver_id').select2('val');
	}
	if(orderid != null && driverid != null) {
		jQuery.ajax({
			url:'index.php?option=com_cabsystem&controller=ajax&format=raw&tmpl=component&task=setDriverToOrder',
			type:'POST',
			data: 'driver_id='+driverid+'&order_id='+orderid,
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
					else {
						oTable.fnDeleteRow(oTable.$('#list-row-'+orderid)[0]);
					}
					
					//Variable der aktuell selektierten TR auf null setzen und Buttons disablen
					selected_order_id = null;
					selected_driver_id = null;
					$('#getDeleteModalOrder').attr("disabled", true);
					$('#getEditModalOrder').attr("disabled", true);
					$('#getCopyFromModalOrder').attr("disabled", true);
					$('#getCopyToModalOrder').attr("disabled", true);
					$('#getSetDriverModalOrder').attr("disabled", true);
					$('#cancelOrder').attr("disabled", true);
					
					var tr = $(result.tr);
					
					//Zeile den EventListener anhaengen
					tr.click(function(event) {
						changeSelectedOrderId($(this).attr('data-order_id'),$(this).attr('data-driver_id'));
					});
					
					oTable.fnAddTr(tr[0]);
					
					if(jQuery("#setDriverModal").length) {
						jQuery("#setDriverModal").modal('hide');
					}
				}
				else {
					$.notify(result.msg, "error");
				}
			}
		});
	}
}

function analyseType(type) {
	switch(type) {
		case 'office':
			type = 'address';
			break;
		case 'hotel':
			type = 'address';
			break;
		case 'railway':
			type = 'address';
			break;
		default:
	}
	return type;
}

function displayPrice(price,type) {
	if(price != null) {
		act_price = price;
	}
	
	var POSTORDER_PRICE_TEXT = '';
	if($("#"+type+"Form-postorder").select2('val') == 1) {
		POSTORDER_PRICE_TEXT = ' (+ '+' € '+jQuery.number(parseFloat(act_price+night_price),2)+' für Rückfahrt)';
	}
	
	if(act_price < 0) {
		$('.'+type+'Form-pricedisplay').text(LANG_PRICE+LANG_PRICE_ON_REQUEST);
		$('#'+type+'Form-price').val('NULL');
	}
	else {
		$('.'+type+'Form-pricedisplay').text(LANG_PRICE+' € '+jQuery.number(parseFloat(act_price+night_price),2)+POSTORDER_PRICE_TEXT);
		$('#'+type+'Form-price').val(jQuery.number((parseFloat(act_price+night_price)),2));
	}
}

function getPrice(type) {	
	var district_id = null;
	if(analyseType($('#'+type+'Form-from_ordertype_id').find(":selected").data("type")) == 'address') {
		district_id = $('#'+type+'Form-from_district_id').select2('val');
	}
	else if(analyseType($('#'+type+'Form-to_ordertype_id').find(":selected").data("type")) == 'address') {
		district_id = $('#'+type+'Form-to_district_id').select2('val');
	}
	
	var additionaladdresses_districts = new Array();
	if($('#'+type+'Form-additionaladdresses_id').select2('val') != '') {
		additionaladdress_id = $('#'+type+'Form-additionaladdresses_id').select2('val');
		if($('#'+type+'Form-additionaladdresses_districts').find('input.additionaladdresses_district').length) {
			$('#'+type+'Form-additionaladdresses_districts').find('input.additionaladdresses_district').each(function() {
				if($(this).select2('val') != '') {
					additionaladdresses_districts.push($(this).select2('val'));
				}
			});
		}
	}

	jQuery.ajax({
		url: 'index.php?option=com_cabsystem&controller=ajax&format=raw&tmpl=component&task=getPrice',
		type: "POST",
		dataType: 'JSON',
		async: false,
		data: {
			cartype: $("#"+type+"Form-cartype_id").select2('val'),
			district: district_id,
			//paymentmethod: $('#'+type+'Form-paymentmethod_id').val(),
			additionaladdress: $('#'+type+'Form-additionaladdresses_id').val(),
			additionaladdress_districts: additionaladdresses_districts,
			child_seat_amount: $('#'+type+'Form-child_seat').val(),
			maxi_cosi_amount: $('#'+type+'Form-maxi_cosi').val(),
			child_seat_elevation_amount: $('#'+type+'Form-child_seat_elevation').val()
		},
		success: function(result) {
			if (result.success){
				act_price = result.price;
			}
			else{
				jQuery.notify(result.msg, "error");
				act_price = 0;
			}
			
			//Nachtzuschlag dazurechnen			
			displayPrice(null,type);
		},
		complete: function() {
			
		}
	});
}

function resetForm(type) {
	if(type == 'add') {
		$("#"+type+"Form-from_ordertype_id").select2("val","");
		$("#"+type+"Form-to_ordertype_id").select2("val","");
		$("#"+type+"Form-from_flight_id").select2("val","");
		$("#"+type+"Form-postorder_from_flight_id").select2("val","");
		$("#"+type+"Form-postorder").select2("val","0");
		//$("#"+type+"Form-to_flight_id").select2("val","");
		$("#"+type+"Form-from_city_id").select2('val','');
		$("#"+type+"Form-to_city_id").select2('val','');
		$("#"+type+"Form-from_district_id").select2('val','');
		$("#"+type+"Form-flight_number").select2('val','');
		$("#"+type+"Form-to_district_id").select2('val','');
		$("#"+type+"Form-from_street_id").select2('val','');
		$("#"+type+"Form-to_street_id").select2('val','');
		$("#"+type+"Form-additionaladdresses_id").select2('val','');
		
		$("#"+type+"Form-additionaladdresses_districts").empty();
	
		//ordertype_sections ausblenden
		$('.ordertype_section_to').hide();
		$('.ordertype_section_from').hide();
		
		night_price = 0;
		displayPrice(0,type);
	}
	//Der EDIT Wizard wird nicht reseted sondern komplett entfernt
	else if(type == 'edit' || type == 'copy') {
		//HIER PASSIERT NICHTS - das wird alles gemacht wenn ein neuer EDIT Modal Wizard geholt wird
	}
}

function initOtherOption() {
	//Wenn value == other
	if($(this).select2('val') === "other") {
		//Darunter Textfeld hinzufuegen
		$('<input type="text" name="'+$(this).attr('name')+'-other" id="'+$(this).attr('id')+'-other" class="form-control"/>').insertAfter($(this));
		//Validation Rule hinzufuegen wenn es fuer das Select2 eine gab
		if($('#'+$(this).attr('id')).rules()) {
			$('#'+$(this).attr('id')+'-other').rules( "remove");
			$('#'+$(this).attr('id')+'-other').rules( "add", {
				required: true,
				messages: {
					required: "Bitte füllen Sie dieses Feld aus"
				}
			});
		}
	}
	//Wenn value != other
	else {
		//Textfeld entfernen (falls existent)
		if($('#'+$(this).attr('id')+'-other').length > 0) {
			//Validation Rule entfernen (falls existent)
			if($('#' + $(this).attr('id') + '-other').rules()) {
				$('#' + $(this).attr('id') + '-other').rules("remove");
			}
			$('#'+$(this).attr('id')+'-other').remove();
		}
	}
}

function checkLockouts(type) {
	if(type == 'add') {
		//alle gesperrten Stunden durchgehen
		for (var i = 0; i < lockouts_array.length; i++) {
			//[year, month, day, hour, minute, second, millisecond]
			var time = moment(moment().format('YYYY-MM-DD') + ' ' + $('#' + type + 'Form-time').val());
			var from = moment().set('hour', lockouts_array[i]).set('seconds', 0).set('minutes', 0);
			var to = moment().set('hour', ((parseInt(lockouts_array[i]) + 1))).set('seconds', 0).set('minutes', 0);

			//Wenn ZEIT in den gesperrten Stunden liegt - nicht valide
			if ((time.isAfter(from) || time.isSame(from)) && (time.isSame(to) || time.isBefore(to))) {
				return false;
			}
		}
		return true;
	}
	return true;
}

function initForm(type) {
	if(type == 'add' && $("#getAddModalOrder").data('webform') == true) {
		$("#"+type+"OrderModal").on('show.bs.modal', function (e) {
		  $("#"+type+"OrderModal").appendTo('body');
		});
	}

	if(type == 'add') {
		//Sperren miteinbeziehen
		lockouts_array = JSON.parse($('#'+type+'Form-lockouts-array').val());
	}
	
	//ENTER Key abfangen
	$("#"+type+"OrderModal").keypress(function( event ) 
	{
		if ( event.which == 13 ) {
			event.preventDefault();
			//TODO Aktion einfuegen
		}
	});

	jQuery.validator.addMethod("lockout", function(value, element) {
		return checkLockouts(type);
	}, "Eine Buchung zu diesem Zeitpunkt ist aufgrund der aktuellen Auslastung leider nicht möglich.");
	
	//Form Validation hinzufuegen
	$("#"+type+"OrderForm").validate({
		rules: {
			"from_ordertype_id": {
				required: true
			},
			"to_ordertype_id": {
				required: true
			},
			"name": {
				required: true
			},
			"salutation_id": {
				required: true
			},
			"phone": {
				required: true
			},
			/*"email": {
				required: true,
				email: true
			},*/
			"to_city_id": {
				required: true
			},
			"to_district_id": {
				required: true
			},
			"to_street_id": {
				required: true
			},
			"from_city_id": {
				required: true
			},
			"from_district_id": {
				required: true
			},
			"from_street_id": {
				required: true
			},
			/*"from_flight_id": {
				required: true
			},*/
			/*"to_flight_id": {
				required: true
			},*/
			"postorder_from_flight_id": {
				required: true
			},
			"postorder_flight_number": {
				required: true
			},
			"date": {
				required: true
			},
			"time": {
				required: true,
				lockout: true
			},
			"postorder_date": {
				required: true
			},
			"postorder_time": {
				required: true
			},
			"tos_accepted": {
				required: true
			}
		},
		messages: {
			salutation_id: "Bitte geben Sie eine Anrede an",
			name: "Bitte geben Sie den Namen an",
			phone: "Bitte geben Sie die Telefonnummer an",
			/*email: {
				required: "Bitte geben Sie die Email Adresse an",
				email: "Die Email Adresse muss die Form name@domain.com haben"
			},*/
			from_city_id: "Bitte geben Sie den Startort an",
			from_district_id: "Bitte geben Sie den Startbezirk an",
			from_street_id: "Bitte geben Sie die Startadresse an",
			from_ordertype_id: "Bitte geben Sie den Abholort ein",
			to_ordertype_id: "Bitte geben Sie ein Ziel ein",
			to_city_id: "Bitte geben Sie den Zielort an",
			to_district_id: "Bitte geben Sie den Zielbezirk an",
			to_street_id: "Bitte geben Sie die Zieladresse an",
			//from_flight_id: "Bitte geben Sie das Land an aus dem Ihr Flug kommt",
			//to_flight_id: "Bitte geben Sie die Flugnummer an",
			postorder_from_flight_id: "Bitte geben Sie das Land an aus dem Ihr Flug kommt",
			postorder_flight_number: "Bitte geben Sie die Flugnummer für die Rückfahrt ein",
			date: "Bitte geben Sie ein Datum ein",
			time: {
				required: "Bitte geben Sie eine Zeit ein",
				lockout: "Eine Buchung zu diesem Zeitpunkt ist aufgrund der aktuellen Auslastung leider nicht möglich."
			},
			postorder_date: "Bitte geben Sie ein Datum für die Rückfahrt ein",
			postorder_time: "Bitte geben Sie eine Zeit für die Rückfahrt ein",
			tos_accepted: "Bitte akzeptieren Sie die AGBs"
		}
	});
	
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
	
	$('#'+type+'Form-time-picker').datetimepicker({
		language: 'de',
		pick12HourFormat: false,
		minuteStepping:5,
		showToday: true,
		defaultDate:"",
		useCurrent: true,
		pickDate: false,
		onGenerate:function(ct,$i){
			var ind = specificDates.indexOf(ct.dateFormat('d/m/Y'));
			$('.xdsoft_time_variant .xdsoft_time').show();
			if(ind !== -1) {
				$('.xdsoft_time_variant .xdsoft_time').each(function(index){
					if(hoursToTakeAway[ind].indexOf(parseInt($(this).text())) !== -1)              {
						$(this).hide();
					}
				});
			}
		}
	});
	
	$('#'+type+'Form-flight_time-picker').datetimepicker({
		language: 'de',
		pick12HourFormat: false,
		minuteStepping:5,
		showToday: true,
		defaultDate:"",
		useCurrent: true,
		pickDate: false
	});
	
	if($('#'+type+'Form-postorder_date-picker').data('min-date')) {
		min_date = moment().subtract('days', 1);
	}
	
	$('#'+type+'Form-postorder_date-picker').datetimepicker({
		language: 'de',
		pick12HourFormat: false,
		minuteStepping:5,
		showToday: true,
		defaultDate:"",
		useCurrent: true,
		pickTime: false,
		minDate:min_date
	});
	
	$('#'+type+'Form-postorder_time-picker').datetimepicker({
		language: 'de',
		pick12HourFormat: false,
		minuteStepping:5,
		showToday: true,
		defaultDate:"",
		useCurrent: true,
		pickDate: false
	});
		
	//Bestimmte Rules nur hinzufuegen wenn gechecked werden soll
	//Email
	if($("#"+type+"Form-email").data("check") == true) {
		$("#"+type+"Form-email").rules( "add", {
			required: true,
			email: true,
			messages: {
				required: "Bitte geben Sie die Email Adresse an",
				email: "Die Email Adresse muss die Form name@domain.com haben"
			}
		});
	}
	
	//Button-Listener hinzufuegen
	$('#'+type+'Order').click( function(e) 
	{
		if($('#'+type+'OrderForm').valid()) 
		{
			//Formularueberpruefungen
			var valid = true;

			//ROUTE
			//von A nach B voruebergehend ausschließen
			if(analyseType($('#'+type+'Form-to_ordertype_id').find(":selected").data("type")) == analyseType($('#'+type+'Form-from_ordertype_id').find(":selected").data("type")) && analyseType($('#'+type+'Form-to_ordertype_id').find(":selected").data("type")) == 'address') {
				alert(INVALID_A_TO_B);
				valid = false;
			}
			
			if(!$('#'+type+'Form-from_ordertype_id').valid()) {
				valid = false;
			}
			if(!$('#'+type+'Form-to_ordertype_id').valid()) {
				valid = false;
			}
			
			//FROM Felder fuer TYPE address checken
			if(analyseType($("#"+type+"Form-from_ordertype_id").find(":selected").data("type")) == 'address') {
				if(!$('#'+type+'Form-from_city_id').valid()) {
					valid = false;
				}
				if(!$('#'+type+'Form-from_district_id').valid()) {
					valid = false;
				}
				if(!$('#'+type+'Form-from_street_id').valid()) {
					valid = false;
				}
			}
			
			//TO Felder fuer TYPE address checken
			if(analyseType($("#"+type+"Form-to_ordertype_id").find(":selected").data("type")) == 'address') {
				if(!$('#'+type+'Form-to_city_id').valid()) {
					valid = false;
				}
				if(!$('#'+type+'Form-to_district_id').valid()) {
					valid = false;
				}
				if(!$('#'+type+'Form-to_street_id').valid()) {
					valid = false;
				}
			}
			
			//FROM Felder fuer TYPE flight checken
			/*if(analyseType($("#"+type+"Form-from_ordertype_id").find(":selected").data("type")) == 'airport') {
				if(!$('#'+type+'Form-from_flight_id').valid()) {
					valid = false;
				}
			}*/
			
			//TO Felder fuer TYPE flight checken
			/*if(analyseType($("#"+type+"Form-to_ordertype_id").find(":selected").data("type")) == 'airport') {
				if(!$('#'+type+'Form-to_flight_id').valid()) {
					valid = false;
				}
			}*/
			
			if(!$('#'+type+'Form-date').valid()) {
				valid = false;
			}
			//ZEIT nur wenn gecheckt werden soll (zb nicht wenn Ankunftszeit gesetzt ist)
			if($("#"+type+"Form-time").data("check") == true) {
				if(!$('#'+type+'Form-time').valid()) {
					valid = false;
				}
			}
			
			//PERSON
			if(!$('#'+type+'Form-name').valid()) {
				valid = false;
			}
			if(!$('#'+type+'Form-phone').valid()) {
				valid = false;
			}
			//Bei Admin muss keine Ueberpruefung stattfinden (check=false)
			if($('#'+type+'Form-email').data('check') == true) {
				if(!$('#'+type+'Form-email').valid()) {
					valid = false;
				}
			}
			if(!$('#'+type+'Form-salutation_id').valid()) {
				valid = false;
			}
			
			if(type == 'add') {
				//Bei Admin muss keine Ueberpruefung stattfinden (check=false)
				if($('#'+type+'Form-tos_accepted').length > 0) {
					//INFO
					if(!$('#'+type+'Form-tos_accepted').valid()) {
						valid = false;
					}
				}
			}
			
			//ADDITIONALADDRESS_DISTRICTS checken
			$('#'+type+'Form-additionaladdresses_districts').find('input.additionaladdresses_district').each(function() {
				if(!$(this).valid()) {
					valid = false;
				}
			});
			
			//RUECKFAHRT OPTIONEN
			if($("#"+type+"Form-postorder").select2('val') == 1) {
				if(!$('#'+type+'Form-postorder_date').valid()) {
					valid = false;
				}
				if(!$('#'+type+'Form-postorder_time').valid()) {
					valid = false;
				}
				//Wenn Fluginfos nicht disabled sind
				if($('#'+type+'Form-postorder_from_flight_id').select2('enable') == true) {
					if(!$('#'+type+'Form-postorder_from_flight_id').valid()) {
						valid = false;
					}
				}
				if($('#'+type+'Form-postorder_flight_number').prop('disabled') == false) {
					if(!$('#'+type+'Form-postorder_flight_number').valid()) {
						valid = false;
					}
				}
			}
			
			if(valid) {
				//Alle disabled Felder die mituebertragen werden sollen enablen
				$("#"+type+"Form-time-picker").data("DateTimePicker").enable();
				
				//Informationen des Fahrers aus dem Form ziehen
				var orderInfo = $("#"+type+"OrderForm").serialize();
				
				//Alle disabled Felder die mituebertragen werden sollen wieder disablen
				$("#"+type+"Form-time-picker").data("DateTimePicker").disable();

				var controller_type = type;
				if(type == 'copy') {
					controller_type = 'add';
				}

				jQuery.ajax({
					url: 'index.php?option=com_cabsystem&controller='+controller_type+'&format=raw&tmpl=component'+"&from_ordertype_type="+analyseType($("#"+type+"Form-from_ordertype_id").find(":selected").data("type"))+"&to_ordertype_type="+analyseType($("#"+type+"Form-to_ordertype_id").find(":selected").data("type")),
					type: "POST",
					dataType: 'JSON',
					data: orderInfo,
					success: function(result) {
						if (result.success){
							jQuery.notify(result.msg, "success");
							
							//Wenn EDIT dann aktuelle Zeile loeschen
							if(type == 'edit') {
								var anSelected = getSelectedDataTableRow( oTable );
								if ( anSelected.length !== 0 ) {
									oTable.fnDeleteRow( anSelected[0] );
								}
								
								//Variable der aktuell selektierten TR auf null setzen und Buttons disablen
								selected_order_id = null;
								selected_driver_id = null;
								$('#getDeleteModalOrder').attr("disabled", true);
								$('#getEditModalOrder').attr("disabled", true);
								$('#getCopyFromModalOrder').attr("disabled", true);
								$('#getCopyToModalOrder').attr("disabled", true);
								$('#getSetDriverModalOrder').attr("disabled", true);
								$('#cancelOrder').attr("disabled", true);
							}
							
							if($("#getAddModalOrder").data('webform') != true) {
								//Anzahl der Elemente holen
								var tr_amount = result.tr_amount;
								
								for(var i=0; i<tr_amount; i++) {
									var tr = $(result.tr[i]);
								
									//Zeile das Attribut mit der ID geben
									tr.attr('data-order_id',result.datatable_data[i].order_id);
									
									//Zeile den EventListener anhaengen
									tr.click(function(event) {
										changeSelectedOrderId($(this).attr('data-order_id'),$(this).attr('data-driver_id'));
									});
									
									oTable.fnAddTr(tr[0]);
								}
								
							}
							
							//Modal verstecken
							//Zuvor wieder zurueckverschieben zu urspruenglicher Stelle
							if(type == 'add' && $("#getAddModalOrder").data('webform') == true) {
								$("#"+type+"OrderModal").insertAfter('getAddModalOrder');
							}
							jQuery("#"+type+"OrderModal").modal('hide');
							resetForm(type);
						}
						else{
							jQuery.notify(result.msg, "error");
						}
					},
					complete: function() {
						
					}
				});
			}
		}
	});
	
	//SELECT 2 initialisieren
	$("#"+type+"Form-salutation_id").select2();
	$("#"+type+"Form-title_id").select2({allowClear:true});
	$("#"+type+"Form-city_id").select2();
	$("#"+type+"Form-from_ordertype_id").select2();
	$("#"+type+"Form-to_ordertype_id").select2();
	$("#"+type+"Form-salutation_id").select2();
	$("#"+type+"Form-title_id").select2({allowClear:true});
	$("#"+type+"Form-from_flight_id").select2();
	$("#"+type+"Form-postorder_from_flight_id").select2();
	$("#"+type+"Form-postorder").select2();
	//$("#"+type+"Form-to_flight_id").select2();
	$("#"+type+"Form-cartype_id").select2();
	$("#"+type+"Form-additionaladdresses_id").select2({allowClear:true});
	$("#"+type+"Form-paymentmethod_id").select2();

	//LERNENDES SYSTEM: Feld hinzufuegen/entfernen
	$("#"+type+"Form-title_id").on("change", initOtherOption);
	$("#"+type+"Form-from_flight_id").on("change", initOtherOption);
	$("#"+type+"Form-postorder_from_flight_id").on("change", initOtherOption);
	$("#"+type+"Form-postorder_flight_number").on("change", initOtherOption);
	
	if($('#'+type+'Form-district-array').length !== 0 && $('#'+type+'Form-street-array').length !== 0) {
		var district_array = JSON.parse($('#'+type+'Form-district-array').val());
		var street_array = JSON.parse($('#'+type+'Form-street-array').val());
		var all_districts_array = JSON.parse($('#'+type+'Form-all-districts-array').val());

		var flightnumber_array = JSON.parse($('#'+type+'Form-flightnumber-array').val());
		
		//VON
		$("#"+type+"Form-from_city_id").select2();

		//LERNENDES SYSTEM: Feld hinzufuegen/entfernen
		$("#"+type+"Form-from_city_id").on("change", initOtherOption);

		if(type == 'add') {
			$("#"+type+"Form-from_city_id").select2('val','',true);
		}
		
		from_data_district[type] = district_array[$("#"+type+"Form-from_city_id").select2("val")];
		$("#"+type+"Form-from_district_id").select2({
			data:function() { return { text:'tag', results: from_data_district[type] }; },
			formatSelection: format,
			formatResult: format
		});

		from_data_flightnumber[type] = flightnumber_array[$("#"+type+"Form-from_flight_id").select2("val")];
		$("#"+type+"Form-flight_number").select2({
			data:function() { return { text:'tag', results: from_data_flightnumber[type] }; },
			formatSelection: format,
			formatResult: format
		});

		from_data_postorder_flightnumber[type] = flightnumber_array[$("#"+type+"Form-postorder_from_flight_id").select2("val")];
		$("#"+type+"Form-postorder_flight_number").select2({
			data:function() { return { text:'tag', results: from_data_postorder_flightnumber[type] }; },
			formatSelection: format,
			formatResult: format
		});

		//LERNENDES SYSTEM: Feld hinzufuegen/entfernen
		$("#"+type+"Form-from_district_id").on("change", initOtherOption);
		$("#"+type+"Form-flight_number").on("change", initOtherOption);
		
		from_data_street[type] = street_array[$("#"+type+"Form-from_district_id").select2("val")];
		$("#"+type+"Form-from_street_id").select2({
			data:function() { return { text:'tag', results: from_data_street[type] }; },
			formatSelection: format,
			formatResult: format
		});

		//LERNENDES SYSTEM: Feld hinzufuegen/entfernen
		$("#"+type+"Form-from_street_id").on("change", initOtherOption);
		
		$("#"+type+"Form-from_city_id").on("select2-selecting", function(e) 
		{
			from_data_district[type] = district_array[e.val];
			$("#"+type+"Form-from_district_id").select2("val","",true);
			$("#"+type+"Form-from_street_id").select2("val","",true);
		});
		
		$("#"+type+"Form-from_district_id").on("select2-selecting", function(e) 
		{
			from_data_street[type] = street_array[e.val];
			$("#"+type+"Form-from_street_id").select2("val","",true);
		});

		$("#"+type+"Form-from_flight_id").on("select2-selecting", function(e)
		{
			from_data_flightnumber[type] = flightnumber_array[e.val];
			$("#"+type+"Form-flight_number").select2("val","",true);
		});

		$("#"+type+"Form-postorder_from_flight_id").on("select2-selecting", function(e)
		{
			from_data_postorder_flightnumber[type] = flightnumber_array[e.val];
			$("#"+type+"Form-postorder_flight_number").select2("val","",true);
		});

		//NACH
		$("#"+type+"Form-to_city_id").select2();

		//LERNENDES SYSTEM: Feld hinzufuegen/entfernen
		$("#"+type+"Form-to_city_id").on("change", initOtherOption);
		if(type == 'add') {
			$("#"+type+"Form-to_city_id").select2('val','',true);
		}
		
		to_data_district = district_array[$("#"+type+"Form-to_city_id").select2("val")];
		$("#"+type+"Form-to_district_id").select2({
			data:function() { return { text:'tag', results: to_data_district }; },
			formatSelection: format,
			formatResult: format
		});

		//LERNENDES SYSTEM: Feld hinzufuegen/entfernen
		$("#"+type+"Form-to_district_id").on("change", initOtherOption);
		
		to_data_street = street_array[$("#"+type+"Form-to_district_id").select2("val")];
		$("#"+type+"Form-to_street_id").select2({
			data:function() { return { text:'tag', results: to_data_street }; },
			formatSelection: format,
			formatResult: format
		});

		//LERNENDES SYSTEM: Feld hinzufuegen/entfernen
		$("#"+type+"Form-to_street_id").on("change", initOtherOption);
		
		$("#"+type+"Form-to_city_id").on("select2-selecting", function(e) 
		{
			to_data_district = district_array[e.val];
			$("#"+type+"Form-to_district_id").select2("val","",true);
			$("#"+type+"Form-to_street_id").select2("val","",true);
		});
		
		$("#"+type+"Form-to_district_id").on("select2-selecting", function(e) 
		{
			to_data_street = street_array[e.val];
			$("#"+type+"Form-to_street_id").select2("val","",true);
		});
	}

	if(type == 'add') {
		$("#"+type+"Form-from_ordertype_id").select2("val","");
		$("#"+type+"Form-to_ordertype_id").select2("val","");
	}
	
	if(type == 'add' || type == 'copy') {
		$("#"+type+"Form-from_flight_id").select2("val","");
		$("#"+type+"Form-postorder_from_flight_id").select2("val","");
		$("#"+type+"Form-postorder").select2("val","0");
		//$("#"+type+"Form-to_flight_id").select2("val","");
	}
	
	//ordertype_sections ausblenden
	$('.ordertype_section_to').hide();
	$('.ordertype_section_from').hide();
	
	//richtige ordertype_sections einblenden (NUR EDIT)
	if(type == 'edit' || type == 'copy') {
		var datatype = analyseType($("#"+type+"Form-from_ordertype_id").find(":selected").data("type"));
		if($('#'+type+'Form-from_ordertype_section_'+datatype).length > 0) {
			$('#'+type+'Form-from_ordertype_section_'+datatype).show();
		}
		var datatype = analyseType($("#"+type+"Form-to_ordertype_id").find(":selected").data("type"));
		if($('#'+type+'Form-to_ordertype_section_'+datatype).length > 0) {
			$('#'+type+'Form-to_ordertype_section_'+datatype).show();
		}
	}
	
	//FROM ueberpruefen ob nicht VON/NACH Flughafen/BahnhofS/BahnhofN gewaehlt wurde
	$('#'+type+'Form-from_ordertype_id').on('select2-selecting',function(e) {
		if(e.val == $("#"+type+"Form-to_ordertype_id").select2('val') && $("#"+type+"Form-to_ordertype_id").find(":selected").data("type") == 'airport') {
			alert(INVALID_SAME_TYPE);
			e.preventDefault();
		}
	});
	
	//FROM ueberpruefen ob nicht VON/NACH Flughafen/BahnhofS/BahnhofN gewaehlt wurde
	$('#'+type+'Form-to_ordertype_id').on('select2-selecting',function(e) {
		if(e.val == $("#"+type+"Form-from_ordertype_id").select2('val') && $("#"+type+"Form-from_ordertype_id").find(":selected").data("type") == 'airport') {
			alert(INVALID_SAME_TYPE);
			e.preventDefault();
		}
	});
	
	//FROM Change event
	$("#"+type+"Form-from_ordertype_id").on("change", function(e) {
		//ordertype_sections ausblenden
		$('.ordertype_section_from').hide();
		
		var datatype = analyseType($("#"+type+"Form-from_ordertype_id").find(":selected").data("type"));
		
		if($('#'+type+'Form-from_ordertype_section_'+datatype).length > 0) {
			$('#'+type+'Form-from_ordertype_section_'+datatype).show();
		}
		
		//Wenn VOM Flughafen
		if(datatype == 'airport') {
			//Ankunftszeit updaten
			$("#"+type+"Form-flight_time-picker").data("DateTimePicker").setDate(null);
			$("#"+type+"Form-flight_time-picker").trigger('dp.change');
		}
		else {
			$("#"+type+"Form-time-picker").data("DateTimePicker").enable();
			$("#"+type+"Form-time-picker").data("DateTimePicker").setDate('');
		}
	});
	
	//TO Change event
	$("#"+type+"Form-to_ordertype_id").on("change", function(e) {
		//ordertype_sections ausblenden
		$('.ordertype_section_to').hide();
		
		var datatype = analyseType($("#"+type+"Form-to_ordertype_id").find(":selected").data("type"));
		
		if($('#'+type+'Form-to_ordertype_section_'+datatype).length > 0) {
			$('#'+type+'Form-to_ordertype_section_'+datatype).show();
		}
		
		//Wenn ZUM Flughafen
		if(datatype == 'airport') {
			//Rueckfahrt-Optionen um Flugnummer und Land erweitern
			$("#"+type+"Form-postorder_from_flight_id").select2('enable',true);
			$("#"+type+"Form-postorder_flight_number").prop('disabled',false);	
		}
		else {	
			//Rueckfahrt-Optionen um Flugnummer und Land vermindern
			$("#"+type+"Form-postorder_from_flight_id").select2('val','');
			$("#"+type+"Form-postorder_from_flight_id").select2('enable',false);
			$("#"+type+"Form-postorder_flight_number").val('');	
			$("#"+type+"Form-postorder_flight_number").prop('disabled',true);	
		}
	});
	
	//CHANGE Event fuer FROM_DISTRICT select, damit der Preis berechnet werden kann
	$("#"+type+"Form-from_district_id").on("change", function(e) {
		getPrice(type);
	});
	
	if(type == 'add') {
		//erstmals ausfuehren um alte Bestaende zu loeschen und die Preisberechnung zurueckzusetzen
		$("#"+type+"Form-from_district_id").trigger('change');
	}
	
	//CHANGE Event fuer TO_DISTRICT select, damit der Preis berechnet werden kann
	$("#"+type+"Form-to_district_id").on("change", function(e) {
		getPrice(type);
	});
	
	if(type == 'add') {
		//erstmals ausfuehren um alte Bestaende zu loeschen und die Preisberechnung zurueckzusetzen
		$("#"+type+"Form-to_district_id").trigger('change');
	}	
	
	//CHANGE Event fuer CARTYPE_ID select, damit der Preis berechnet werden kann
	$("#"+type+"Form-cartype_id").on("change", function(e) {
		getPrice(type);
		//Wenn eingetragene Koffer zu viele
		if($("#"+type+"Form-cartype_id").find(":selected").data('luggage') != "" && $("#"+type+"Form-luggage").val() > $("#"+type+"Form-cartype_id").find(":selected").data('luggage')) {
			$("#"+type+"Form-luggage").val($("#"+type+"Form-cartype_id").find(":selected").data('luggage'));
			alert("In diesem Fahrzeugtyp können nur "+$("#"+type+"Form-cartype_id").find(":selected").data('luggage')+" Koffer mitgenommen werden, die Anzahl der Koffer wurde reduziert. Wenn Sie mehr mitnehmen möchten, wählen Sie bitte den nächst größeren Fahrzeugtyp.");
		}
		//Wenn eingetragene Hangepaecksstuecke zu viele
		if($("#"+type+"Form-cartype_id").find(":selected").data('handluggage') != "" && $("#"+type+"Form-handluggage").val() > $("#"+type+"Form-cartype_id").find(":selected").data('handluggage')) {
			$("#"+type+"Form-handluggage").val($("#"+type+"Form-cartype_id").find(":selected").data('handluggage'));
			alert("In diesem Fahrzeugtyp können nur "+$("#"+type+"Form-cartype_id").find(":selected").data('handluggage')+" Handgepäckstücke mitgenommen werden, die Anzahl der Handgepäckstücke wurde reduziert. Wenn Sie mehr mitnehmen möchten, wählen Sie bitte den nächst größeren Fahrzeugtyp.");
		}
		//Wenn eingetragene Personen zu viele
		if($("#"+type+"Form-cartype_id").find(":selected").data('persons') != "" && $("#"+type+"Form-persons").val() > $("#"+type+"Form-cartype_id").find(":selected").data('persons')) {
			$("#"+type+"Form-persons").val($("#"+type+"Form-cartype_id").find(":selected").data('persons'));
			alert("In diesem Fahrzeugtyp können nur "+$("#"+type+"Form-cartype_id").find(":selected").data('persons')+" Personen mitgenommen werden, die Anzahl der Personen wurde reduziert. Wenn Sie mehr auswählen möchten, wählen Sie bitte den nächst größeren Fahrzeugtyp.");
		}
		
	});
	//CHANGE Event fuer Person Spinner
	$("#"+type+"Form-persons").on("change", function(e) {
		//Wenn eingetragene Koffer zu viele
		if($("#"+type+"Form-cartype_id").find(":selected").data('persons') != "" && $("#"+type+"Form-persons").val() > $("#"+type+"Form-cartype_id").find(":selected").data('persons')) {
			$("#"+type+"Form-persons").val($("#"+type+"Form-cartype_id").find(":selected").data('persons'));
			alert("In diesem Fahrzeugtyp können nur "+$("#"+type+"Form-cartype_id").find(":selected").data('persons')+" Personen mitgenommen werden. Wenn Sie mehr auswählen möchten, wählen Sie bitte den nächst größeren Fahrzeugtyp.");
		}
	});
	
	//CHANGE Event fuer Luggage und Handluggage Spinner
	$("#"+type+"Form-luggage").on("change", function(e) {
		//Wenn eingetragene Koffer zu viele
		if($("#"+type+"Form-cartype_id").find(":selected").data('luggage') != "" && $("#"+type+"Form-luggage").val() > $("#"+type+"Form-cartype_id").find(":selected").data('luggage')) {
			$("#"+type+"Form-luggage").val($("#"+type+"Form-cartype_id").find(":selected").data('luggage'));
			alert("In diesem Fahrzeugtyp können nur "+$("#"+type+"Form-cartype_id").find(":selected").data('luggage')+" Koffer mitgenommen werden. Wenn Sie mehr mitnehmen möchten, wählen Sie bitte den nächst größeren Fahrzeugtyp.");
		}
	});
	
	//CHANGE Event fuer Luggage und Handluggage Spinner
	$("#"+type+"Form-handluggage").on("change", function(e) {
		//Wenn eingetragene Hangepaecksstuecke zu viele
		if($("#"+type+"Form-cartype_id").find(":selected").data('handluggage') != "" && $("#"+type+"Form-handluggage").val() > $("#"+type+"Form-cartype_id").find(":selected").data('handluggage')) {
			$("#"+type+"Form-handluggage").val($("#"+type+"Form-cartype_id").find(":selected").data('handluggage'));
			alert("In diesem Fahrzeugtyp können nur "+$("#"+type+"Form-cartype_id").find(":selected").data('handluggage')+" Handgepäckstücke mitgenommen werden. Wenn Sie mehr mitnehmen möchten, wählen Sie bitte den nächst größeren Fahrzeugtyp.");
		}
	});
	
	if(type == 'add') {
		//erstmals ausfuehren um alte Bestaende zu loeschen und die Preisberechnung zurueckzusetzen
		$("#"+type+"Form-cartype_id").trigger('change');
	}
	
	//CHANGE Event fuer ADDITIONALADDRESSES select, damit der Preis berechnet werden kann
	$("#"+type+"Form-additionaladdresses_id").on("change", function(e) {
		//Anzahl der Bezirke auslesen und Selects dafuer bauen
		if($(this).find(":selected").data("districts")) {
			$("#"+type+"Form-additionaladdresses_districts").empty();
			for(var i=1; i<=$(this).find(":selected").data("districts"); i++) {
				var new_element = $('<div class="form-group"><label for="additionaladdresses_district_'+i+'" class="col-sm-3 control-label">Ziel '+i+'</label><div class="col-sm-9"><input type="hidden" class="form-control additionaladdresses_district" name="additionaladdress_districts[]" id="'+type+'Form-additionaladdresses_district_'+i+'" /></div></div><div class="form-group"><label for="additionaladdresses_district_address_'+i+'" class="col-sm-3 control-label">Adresse '+i+'</label><div class="col-sm-9"><input type="text" class="form-control additionaladdresses_district_address" name="additionaladdress_districts_addresses[]" id="'+type+'Form-additionaladdresses_district_address_'+i+'" /></div></div>');
				$("#"+type+"Form-additionaladdresses_districts").append(new_element);
				$("#"+type+"Form-additionaladdresses_district_"+i).select2({
					data:function() { return { text:'tag', results: all_districts_array }; },
					formatSelection: format,
					formatResult: format
				});
				
				//Form Validation
				$("#"+type+"Form-additionaladdresses_district_"+i).rules( "remove");
				$("#"+type+"Form-additionaladdresses_district_"+i).rules( "add", {
					required: true,
					messages: {
						required: "Bitte geben Sie einen Bezirk für die Zusatzadresse an"
					}
				});

				//Event Listener
				$("#"+type+"Form-additionaladdresses_district_"+i).on('change',function() {
					getPrice(type);
				});
			}
		}
		getPrice(type);
	});
	
	$("#"+type+"Form-additionaladdresses_id").on("select2-clearing", function(e) {
		$("#"+type+"Form-additionaladdresses_districts").empty();
	});
	
	if(type == 'edit' || type == 'copy') {
		$('#'+type+'OrderForm').find('input.additionaladdresses_district').each(function() {
			//Select2
			$(this).select2({
				data:function() { return { text:'tag', results: all_districts_array }; },
				formatSelection: format,
				formatResult: format
			});
			if($(this).data('select-value')) {
				$(this).select2('val',$(this).data('select-value'));
			}
			//Form Validation
			if($(this).rules()) {
				$(this).rules("remove");
			}
			$(this).rules("add", {
				required: true,
				messages: {
					required: "Bitte geben Sie einen Bezirk für die Zusatzadresse an"
				}
			});
			//Event Listener
			$(this).on('change',function() {
				getPrice(type);
			});
		});
	}
	
	if(type == 'add') {
		//erstmals ausfuehren um alte Bestaende zu loeschen und die Preisberechnung zurueckzusetzen
		$("#"+type+"Form-additionaladdresses_id").select2('val','').trigger('change');
	}
	
	//CHANGE Event fuer PAYMENTMETHOD select, damit der Preis berechnet werden kann
	/*$("#"+type+"Form-paymentmethod_id").on("change", function(e) {
		getPrice(type);
	});
	
	if(type == 'add') {
		//erstmals ausfuehren um alte Bestaende zu loeschen und die Preisberechnung zurueckzusetzen
		$("#"+type+"Form-paymentmethod_id").trigger('change');
	}*/
	
	//Spinner initialisieren
	$.each( $('.show-spinner'), function(key, obj) {
		$(this).TouchSpin({
			min: $(this).data('spinner-min'),
			max: $(this).data('spinner-max'),
			initval: $(this).data('spinner-min'),
			postfix: $(this).data('spinner-postfix')
		});
	});
	
	//Wenn EDIT dann soll der Preis angezeigt werden
	if(type == 'edit' || type == 'copy') {
		$('.'+type+'Form-pricedisplay').text(LANG_PRICE+' € '+jQuery.number(parseFloat($('#'+type+'Form-price').val()),2));
	}	
	
	$("#"+type+"OrderForm select").each(function(){
		$(this).on("select2-focus", function(e) { 
			$(this).addClass('select2_focus');
		});
		$(this).on("select2-blur", function(e) { 
			$(this).removeClass('select2_focus');
		});
	});
	
	//ANKUNFTSZEIT ALS ABHOLZEIT
	//Time Picker explizit auf enabled weil gecached wird
	if(type == 'add') {
		night_price = 0;
		$("#"+type+"Form-time-picker").data("DateTimePicker").enable();
		$("#"+type+"Form-time-picker").data("DateTimePicker").setDate('');
	}
	//CHANGE Event fuer FLIGHT_TIME spinner, damit der Preis berechnet werden kann
	$("#"+type+"Form-time-picker").on("dp.change dp.show", function(e) {
		night_price = 0;
		if($(this).data("DateTimePicker").getDate() != null) {
			var hour = new Date($(this).data("DateTimePicker").getDate()).getHours();
			var minutes = new Date($(this).data("DateTimePicker").getDate()).getMinutes();
			if((hour == 0 && minutes > 0 || hour > 0) && (hour < 4)) {
				night_price = 5;
			}
		}
		displayPrice(null,type);
	});
	//CHANGE Event fuer FLIGHT_TIME spinner, damit der Preis berechnet werden kann
	$("#"+type+"Form-flight_time-picker").on("dp.change dp.show", function(e) {
		if($(this).data("DateTimePicker").getDate() == null) {
			$("#"+type+"Form-time-picker").data("DateTimePicker").enable();
			$("#"+type+"Form-time-picker").data("DateTimePicker").setDate('');
		}
		else {
			$("#"+type+"Form-time-picker").data("DateTimePicker").disable();
			$("#"+type+"Form-time-picker").data("DateTimePicker").setDate($(this).data("DateTimePicker").getDate());
		}
		$("#"+type+"Form-time-picker").trigger('dp.change');
	});
	
	//MAXIMAL insgesamt 3 MaxiCosi und Kindersitze
	//CHANGE Event fuer MaxiCosi
	$("#"+type+"Form-maxi_cosi").on("change", function(e) {
		/*if($(this).val() + $("#"+type+"Form-child_seat").val() > 3) {
			$(this).val(3 - $("#"+type+"Form-child_seat").val());
			alert("Es können insgesamt nur 3 Maxi Cosis und Kindersitze bestellt werden");
		}*/
		$("#"+type+"Form-child_seat").trigger("touchspin.updatesettings", {max: (3 - $(this).val())});
	});
	//CHANGE Event fuer Kindersitze
	$("#"+type+"Form-child_seat").on("change", function(e) {
		/*if($(this).val() + $("#"+type+"Form-maxi_cosi").val() > 3) {
			$(this).val(3 - $("#"+type+"Form-maxi_cosi").val());
			alert("Es können insgesamt nur 3 Maxi Cosis und Kindersitze bestellt werden");
		}*/
		$("#"+type+"Form-maxi_cosi").trigger("touchspin.updatesettings", {max: (3 - $(this).val())}); 
	});
	//touchspin.on.stopspin Event fuer MaxiCosi
	$("#"+type+"Form-maxi_cosi").on("touchspin.on.stopspin", function(e) {
		getPrice(type);
	});
	//touchspin.on.stopspin Event fuer Kindersitze
	$("#"+type+"Form-child_seat").on("touchspin.on.stopspin", function(e) {
		getPrice(type);
	});
	//touchspin.on.stopspin Event fuer Kindersitzerhoehungen
	$("#"+type+"Form-child_seat_elevation").on("touchspin.on.stopspin", function(e) {
		getPrice(type);
	});
	
	if(type == 'edit' || type == 'copy') {
		$("#"+type+"Form-child_seat").trigger("touchspin.updatesettings", {max: (3 - $("#"+type+"Form-maxi_cosi").val())});
		$("#"+type+"Form-maxi_cosi").trigger("touchspin.updatesettings", {max: (3 - $("#"+type+"Form-child_seat").val())});
	}
	
	//RUECKFAHRT
	$("#"+type+"Form-postorder").on('change', function() {
		if($("#"+type+"Form-postorder").select2('val') == 1) {
			$("#"+type+"Form-postorder_wrapper").show();
		}
		else if($("#"+type+"Form-postorder").select2('val') == 0) {
			$("#"+type+"Form-postorder_wrapper").hide();
		}
		getPrice(type);
	});
	$("#"+type+"Form-postorder_wrapper").hide();
	if(type == 'edit' || type == 'copy') {
		$("#"+type+"Form-postorder").trigger('change');
	}
	if(type == 'add' || type == 'copy') {
		$("#"+type+"Form-postorder_from_flight_id").select2('val','');
		$("#"+type+"Form-postorder_from_flight_id").select2('enable',false);
		$("#"+type+"Form-postorder_flight_number").val('');	
		$("#"+type+"Form-postorder_flight_number").prop('disabled',true);	
	}
	
	//PRESELECTION
	if(type == 'add') {
		if($('#getAddModalOrder').data('preselection') == 'from_airport') {
			$("#"+type+"Form-from_ordertype_id").select2('val',2).trigger('change');
		}
		else if($('#getAddModalOrder').data('preselection') == 'to_airport') {
			$("#"+type+"Form-to_ordertype_id").select2('val',2).trigger('change');
		}
	}
	
	//Wenn ADD dann den Preis initalisieren
	if(type == 'add') {
		displayPrice(0,type);
	}
}

//die aktuell ausgewaehlte ID speichern und je nachdem die Buttons aktivieren/deaktivieren
function changeSelectedOrderId(order_id,driver_id) 
{
	if(selected_order_id == order_id) 
	{
		selected_order_id = null;
		selected_driver_id = null;
		$('#getDeleteModalOrder').attr("disabled", true);
		$('#getEditModalOrder').attr("disabled", true);
		$('#getCopyFromModalOrder').attr("disabled", true);
		$('#getCopyToModalOrder').attr("disabled", true);
		$('#getSetDriverModalOrder').attr("disabled", true);
		$('#cancelOrder').attr("disabled", true);
	}
	else 
	{
		selected_order_id = order_id;
		selected_driver_id = driver_id;
		$('#getDeleteModalOrder').attr("disabled", false);
		$('#getEditModalOrder').attr("disabled", false);
		$('#getCopyFromModalOrder').attr("disabled", false);
		$('#getCopyToModalOrder').attr("disabled", false);
		$('#getSetDriverModalOrder').attr("disabled", false);
		$('#cancelOrder').attr("disabled", false);
	}
}

function deleteOrder(order_id)
{
	if(selected_order_id != null) {
		jQuery.ajax({
			url:'index.php?option=com_cabsystem&controller=delete&format=raw&tmpl=component',
			type:'POST',
			data: 'order_id='+selected_order_id+'&type=Order',
			dataType: 'JSON',
			success:function(result)
			{
				if(result.success)
				{
					jQuery.notify(result.msg, "success");
					var anSelected = getSelectedDataTableRow( oTable );
					if ( anSelected.length !== 0 ) {
						oTable.fnDeleteRow( anSelected[0] );
					}
					
					//Variable der aktuell selektierten TR auf null setzen und Buttons disablen
					selected_order_id = null;
					$('#getDeleteModalOrder').attr("disabled", true);
					$('#getEditModalOrder').attr("disabled", true);
					$('#getCopyToModalOrder').attr("disabled", true);
					$('#getSetDriverModalOrder').attr("disabled", true);
					$('#getSetDriverModalOrder').attr("disabled", true);
					$('#cancelOrder').attr("disabled", true);
					
					jQuery("#deleteConfirm").modal('hide');
				}
				else {
					jQuery.notify(result.msg, "error");
				}
			}
		});
	}
}

function getSetDriverOrderModal()
{
	if(selected_order_id != null) {
		jQuery.ajax({
			url:'index.php?option=com_cabsystem&controller=edit&task=getSetDriverModal&format=raw&tmpl=component',
			type:'POST',
			data: {
				order_id: selected_order_id
			},
			dataType: 'JSON',
			success:function(result)
			{
				if(result.html)
				{
					if($('#setDriverModal').length != 0) {
						$('#setDriverModal').replaceWith($(result.html));
					}
					else {
						$(result.html).insertAfter("#getSetDriverModalOrder");
					}
					
					//EVENT LISTENER fuer setDriver Modal
					$('#setDriverModal').on('show.bs.modal', function (e) {
						if(selected_driver_id != null && selected_driver_id != '') {
							$('#driverInfo').show();
						}
						else {
							$('#driverInfo').hide();
						}
					})
					
					$('#setDriverModal').modal('show');
					
					//ENTER Key abfangen
					$(".setDriver").each(function() {
						$(this).keypress(function( event ) 
						{
							if ( event.which == 13 ) {
								event.preventDefault();
								setDriver();
							}
						});
					});

					//SAVE Button
					$(".setDriver").each(function() {
						$(this).click( function(e) 
						{
							setDriver();
						});
					});
	
					$('#setDriverForm-driver_id').select2({allowClear:true});
				}
			}
		});
	}
}

function getEditOrderModal()
{
	if(selected_order_id != null) {
		jQuery.ajax({
			url:'index.php?option=com_cabsystem&controller=edit&task=getEditModal&format=raw&tmpl=component',
			type:'POST',
			data: {
				order_id: selected_order_id,
				view: 'order',
				model: 'Order',
				item: 'order',
				table: 'orders'	
			},
			dataType: 'JSON',
			success:function(result)
			{
				if(result.html)
				{
					if($('#editOrderModal').length != 0) {
						$('#editOrderModal').replaceWith($(result.html));
					}
					else {
						$(result.html).insertAfter( "#getEditModalOrder" );
					}
					
					//EDIT Wizard initialisieren
					initForm('edit');
					
					$('#editOrderModal').modal('show');
				}
			}
		});
	}
}

function getCopyFromOrderModal()
{
	if(selected_order_id != null) {
		jQuery.ajax({
			url:'index.php?option=com_cabsystem&controller=edit&task=getEditModal&format=raw&tmpl=component&layout=_copy&copy_type=from',
			type:'POST',
			data: {
				order_id: selected_order_id,
				view: 'order',
				model: 'Order',
				item: 'order',
				table: 'orders'
			},
			dataType: 'JSON',
			success:function(result)
			{
				if(result.html)
				{
					if($('#copyOrderModal').length != 0) {
						$('#copyOrderModal').replaceWith($(result.html));
					}
					else {
						$(result.html).insertAfter( "#getCopyFromModalOrder" );
					}

					//EDIT Wizard initialisieren
					initForm('copy');

					$('#copyOrderModal').modal('show');
				}
			}
		});
	}
}

function getCopyToOrderModal()
{
	if(selected_order_id != null) {
		jQuery.ajax({
			url:'index.php?option=com_cabsystem&controller=edit&task=getEditModal&format=raw&tmpl=component&layout=_copy&copy_type=to',
			type:'POST',
			data: {
				order_id: selected_order_id,
				view: 'order',
				model: 'Order',
				item: 'order',
				table: 'orders'
			},
			dataType: 'JSON',
			success:function(result)
			{
				if(result.html)
				{
					if($('#copyOrderModal').length != 0) {
						$('#copyOrderModal').replaceWith($(result.html));
					}
					else {
						$(result.html).insertAfter( "#getCopyToModalOrder" );
					}

					//EDIT Wizard initialisieren
					initForm('copy');

					$('#copyOrderModal').modal('show');
				}
			}
		});
	}
}