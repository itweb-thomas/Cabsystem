var oTable;
var oTableD;
var oTables;
var icon_active = '<i class="icon-sm icon-active fa fa-check-circle"></i>';
var icon_inactive = '<i class="icon-sm icon-inactive fa fa-minus-circle"></i>';
var datatable_lang_array = null;

jQuery(document).ready(function()
{
	$.fn.modal.Constructor.prototype.enforceFocus = function() {};
	jQuery.validator.addMethod("time", function(value, element) {  
	return this.optional(element) || /^(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])(:([0-5]?[0-9]))?$/i.test(value);  
	});
	
	jQuery.notify.addStyle('cabsystem', {
	  html: '<div class="alert" data-notify-text></div>',
	  classes: {
		base: {
			"border-radius": "4px",
    		"margin-bottom": "5px",
    		"padding": "8px"
		},
		success: {
			"background-color": "#DFF0D8",
			"border-color": "#D6E9C6",
			"color": "#3C763D"
		},
		info: {
    		"background-color": "#D9EDF7",
    		"border-color": "#BCE8F1",
   			"color": "#31708F"
		},
		error: {
    		"background-color": "#F2DEDE",
    		"border-color": "#EBCCD1",
   			"color": "#A94442"
		}
	  }
	});
	
	jQuery.notify.defaults({className: 'success'});
	jQuery.notify.defaults({globalPosition: 'top center'});
	jQuery.notify.defaults({style: 'cabsystem'});
	
	datatable_lang_array = {
		"sProcessing":   "Bitte warten...",
		"sLengthMenu":   "_MENU_ Einträge anzeigen",
		"sZeroRecords":  "Keine Einträge vorhanden.",
		"sInfo":         "_START_ bis _END_ von _TOTAL_ Einträgen",
		"sInfoEmpty":    "0 bis 0 von 0 Einträgen",
		"sInfoFiltered": "(gefiltert von _MAX_  Einträgen)",
		"sInfoPostFix":  "",
		"sSearch":       "Suchen",
		"sUrl":          "",
		"oPaginate": {
			"sFirst":    "Erster",
			"sPrevious": "Zurück",
			"sNext":     "Nächster",
			"sLast":     "Letzter"
		}
	};
	
	if(jQuery('#dataTable').length) {
		oTable = jQuery('#dataTable').dataTable(
		{
			"oLanguage": datatable_lang_array
		}).on('draw.dt', function() {	
			$(".setDriver").each(function() {
				$(this).off('click'); 
				$(this).click( function(e) 
				{
					setDriver($(this).data('orderid'), $(this).data('driverid'));
				});
			});
			
			$('.show-tooltip-hover').tooltip({trigger:'hover focus'});
		});
	
		jQuery("#dataTable tbody").click(function(event) {
			var had_class = false;
			if(jQuery(event.target).parents('tr').hasClass('info'))
			{
				had_class = true;
			}
			jQuery(oTable.fnSettings().aoData).each(function (){
				jQuery(this.nTr).removeClass('info');
			});
			if(!had_class)
			{
				jQuery(event.target).parents('tr').addClass('info');
			}
		});
	
		jQuery('#dataTable_length select').select2(
		{
			minimumResultsForSearch: -1
		});	
	}
	
	jQuery('.btn-in-datatable').on('click',function(e) {
		//e.stopPropagation();
	});
	
	function clearAddForm() {
		jQuery(".add-form form :input").each(function(idx,ele)
		{
			if (jQuery(ele).attr('type') == 'checkbox')
			{
				jQuery(ele).attr('checked',true);
				//console.log("CB: "+jQuery(ele).attr('id'));
			}
			else if (jQuery(ele).attr('type') == 'text')
			{
				jQuery(ele).val('');
				//console.log("TEXT: "+jQuery(ele).attr('id'));
			}
		});
		jQuery(".add-form form :input")[0].focus();
	}
	
	jQuery('.add-form').on('shown.bs.modal', clearAddForm);
	
	$('.show-tooltip-hover').tooltip({trigger:'hover focus'});
});

function getSelectedDataTableRow( oTableLocal )
{
    return oTableLocal.$('tr.info');
}