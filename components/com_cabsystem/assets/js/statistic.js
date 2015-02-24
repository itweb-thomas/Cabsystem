$(document).ready(function()
{	
	//Select2 initialisieren
	$("#driver").select2();
	$("#paymentmethod").select2();
	
	//Datetimepicker initialisieren
	$('#from-date-input').datetimepicker({
		language: 'de',
		pick12HourFormat: false,
		minuteStepping:5,
		showToday: true,
		defaultDate:"",
		useCurrent: true,
		pickTime: false
	});
	
	$('#to-date-input').datetimepicker({
		language: 'de',
		pick12HourFormat: false,
		minuteStepping:5,
		showToday: true,
		defaultDate:"",
		useCurrent: true,
		pickTime: false
	});
	
	$("#check-all").click(function () {
		$(".checkbox input").prop('checked', true);
	});
	$("#uncheck-all").click(function () {
		$(".checkbox input").prop('checked', false);
	});
	
	/*$("#export").click(function () {
		jQuery.ajax({
			url:'index.php?option=com_cabsystem&controller=ajax&task=exportOrders&format=raw&tmpl=component',
			type:'POST',
			data: $('#export-form').serialize(),
			dataType: 'JSON',
			success:function(result)
			{
				if(result.success)
				{
					if(result.filename) {
						var url='/'+result.filename;    
						window.location.assign(url);
					}
				}
				else {
					$.notify(result.msg, "error");
				}
			}
		});
	});*/
});