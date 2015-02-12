<?php

// no direct access

defined('_JEXEC') or die('Restricted access');



class CabsystemHelpersStyle

{

	function load()

	{

		$app = JFactory::getApplication();

		$document = JFactory::getDocument();

		$viewName = $app->input->getWord('view');
		$layoutName = $app->input->getWord('layout');
		
		//View spezifische Scripts die am anfang geladen werden muessen
		switch($viewName) {
			case 'order':
				switch($layoutName) {
					case 'websitefrom':
					case 'websiteto':
					case 'websiteprice':
						//$document->addStylesheet(JURI::base().'components/com_cabsystem/assets/css/bootstrap-theme.min.css');
						$document->addStylesheet(JURI::base().'components/com_cabsystem/assets/css/bootstrap.min.css');
						$document->addScript(JURI::base().'components/com_cabsystem/assets/js/jquery-1.10.2.min.js');
						$document->addScript(JURI::base().'components/com_cabsystem/assets/js/bootstrap.min.js');
					break;	
				}
			break;	
		}

		//stylesheets

		//$document->addStylesheet(JURI::base().'components/com_cabsystem/assets/libs/select2/select2-bootstrap.css');

		$document->addStylesheet(JURI::base().'components/com_cabsystem/assets/libs/select2/select2.css');

		$document->addStylesheet(JURI::base().'components/com_cabsystem/assets/libs/datetimepicker/bootstrap-datetimepicker.min.css');

		$document->addStylesheet(JURI::base().'components/com_cabsystem/assets/libs/wizard/bootstrap-wizard.css');

		$document->addStylesheet(JURI::base().'components/com_cabsystem/assets/libs/touchspin/jquery.bootstrap-touchspin.min.css');

		$document->addStylesheet(JURI::base().'components/com_cabsystem/assets/libs/yadcf-master/jquery.dataTables.yadcf.css');

		$document->addStylesheet(JURI::base().'components/com_cabsystem/assets/css/cabsystem.css');

		$document->addStylesheet(JURI::base().'components/com_cabsystem/assets/css/'.$viewName.'.css');

		$document->addStylesheet(JURI::base().'components/com_cabsystem/assets/css/select2.css');
		
		$document->addStylesheet("//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css");

		

		//javascripts

		//$document->addScript('http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js');
		$document->addScript('//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js');

		$document->addScript(JURI::base().'components/com_cabsystem/assets/libs/datatables/dataTables.bootstrap.js');
		
		$document->addScript('//cdn.datatables.net/plug-ins/be7019ee387/api/fnAddTr.js');		

		$document->addScript(JURI::base().'components/com_cabsystem/assets/libs/select2/select2.min.js');

		$document->addScript(JURI::base().'components/com_cabsystem/assets/libs/moment/moment-with-langs.min.js');

		$document->addScript(JURI::base().'components/com_cabsystem/assets/libs/datetimepicker/bootstrap-datetimepicker.min.js');
		
		$document->addScript(JURI::base().'components/com_cabsystem/assets/libs/jqueryvalidation/jquery.validate.min.js');

		$document->addScript(JURI::base().'components/com_cabsystem/assets/libs/notify/notify.min.js');

		$document->addScript(JURI::base().'components/com_cabsystem/assets/js/cabsystem.js');

		$document->addScript(JURI::base().'components/com_cabsystem/assets/libs/wizard/bootstrap-wizard.min.js');

		$document->addScript(JURI::base().'components/com_cabsystem/assets/libs/touchspin/jquery.bootstrap-touchspin.min.js');
		
		$document->addScript(JURI::base().'components/com_cabsystem/assets/libs/jquery-number-master/jquery.number.min.js');
		
		$document->addScript(JURI::base().'components/com_cabsystem/assets/libs/yadcf-master/jquery.dataTables.yadcf.js');
		
		$document->addScript(JURI::base().'components/com_cabsystem/assets/js/'.$viewName.'.js');
		
		//View spezifische Scripts
		switch($viewName) {
			case 'statistic':
				$document->addScript(JURI::base().'components/com_cabsystem/assets/libs/jspdf/jspdf.js');
				$document->addScript(JURI::base().'components/com_cabsystem/assets/libs/jspdf/libs/Deflate/adler32cs.js');
				$document->addScript(JURI::base().'components/com_cabsystem/assets/libs/jspdf/libs/FileSaver.js/FileSaver.js');
				$document->addScript(JURI::base().'components/com_cabsystem/assets/libs/jspdf/libs/Blob.js/BlobBuilder.js');
				$document->addScript(JURI::base().'components/com_cabsystem/assets/libs/jspdf/jspdf.plugin.from_html.js');
			break;	
			case 'order':
				switch($layoutName) {
					case 'websitefrom':
					break;	
				}
			break;	
		}

	}

}