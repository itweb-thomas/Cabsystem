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
					case 'websiteall':
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
		$document->addStylesheet(JURI::base().'components/com_cabsystem/assets/libs/select2/select2.css');

		$document->addStylesheet(JURI::base().'components/com_cabsystem/assets/libs/datetimepicker/bootstrap-datetimepicker.min.css');

		$document->addStylesheet(JURI::base().'components/com_cabsystem/assets/libs/touchspin/jquery.bootstrap-touchspin.min.css');

		$document->addStylesheet(JURI::base().'components/com_cabsystem/assets/libs/yadcf-master/jquery.dataTables.yadcf.css');

		$document->addStylesheet(JURI::base().'components/com_cabsystem/assets/css/cabsystem.css');

		$document->addStylesheet(JURI::base().'components/com_cabsystem/assets/css/'.$viewName.'.css');

		$document->addStylesheet(JURI::base().'components/com_cabsystem/assets/css/select2.css');
		
		$document->addStylesheet("//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css");

		

		//javascripts

		$document->addScript('//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js');

		$document->addScript(JURI::base().'components/com_cabsystem/assets/libs/datatables/dataTables.bootstrap.js');

		$document->addScript(JURI::base().'components/com_cabsystem/assets/libs/fnaddtr/fnAddTr.js');

		$document->addScript(JURI::base().'components/com_cabsystem/assets/libs/select2/select2.min.js');

		$document->addScript(JURI::base().'components/com_cabsystem/assets/libs/moment/moment-with-langs.min.js');

		$document->addScript(JURI::base().'components/com_cabsystem/assets/libs/datetimepicker/bootstrap-datetimepicker.min.js');
		
		$document->addScript(JURI::base().'components/com_cabsystem/assets/libs/jqueryvalidation/jquery.validate.min.js');

		$document->addScript(JURI::base().'components/com_cabsystem/assets/libs/notify/notify.min.js');

		$document->addScript(JURI::base().'components/com_cabsystem/assets/js/cabsystem.js');

		$document->addScript(JURI::base().'components/com_cabsystem/assets/libs/touchspin/jquery.bootstrap-touchspin.min.js');
		
		$document->addScript(JURI::base().'components/com_cabsystem/assets/libs/jquery-number-master/jquery.number.min.js');
		
		$document->addScript(JURI::base().'components/com_cabsystem/assets/libs/yadcf-master/jquery.dataTables.yadcf.js');


		//LANGUAGE FILES

		$document->addScriptDeclaration('var lang = {
			COM_CABSYSTEM_PRICE:"'.JText::_('COM_CABSYSTEM_PRICE').'",
			COM_CABSYSTEM_INVALID_SAME_TYPE:"'.JText::_('COM_CABSYSTEM_INVALID_SAME_TYPE').'",
			COM_CABSYSTEM_INVALID_NO_AIRPORT:"'.JText::_('COM_CABSYSTEM_INVALID_NO_AIRPORT').'",
			COM_CABSYSTEM_INVALID_A_TO_B:"'.JText::_('COM_CABSYSTEM_INVALID_A_TO_B').'",
			COM_CABSYSTEM_LANG_PRICE_WILL_BE_CALCULATED:"'.JText::_('COM_CABSYSTEM_LANG_PRICE_WILL_BE_CALCULATED').'",
			COM_CABSYSTEM_LANG_PRICE_ON_REQUEST:"'.JText::_('COM_CABSYSTEM_LANG_PRICE_ON_REQUEST').'",
			COM_CABSYSTEM_ERROR_LOCKOUT:"'.JText::_('COM_CABSYSTEM_ERROR_LOCKOUT').'",
			COM_CABSYSTEM_ERROR_REQUIRED_SALUTATION_ID:"'.JText::_(COM_CABSYSTEM_ERROR_REQUIRED_SALUTATION_ID).'",
			COM_CABSYSTEM_ERROR_REQUIRED_NAME:"'.JText::_('COM_CABSYSTEM_ERROR_REQUIRED_NAME').'",
			COM_CABSYSTEM_ERROR_REQUIRED_PHONE:"'.JText::_('COM_CABSYSTEM_ERROR_REQUIRED_PHONE').'",
			COM_CABSYSTEM_ERROR_REQUIRED_EMAIL:"'.JText::_('COM_CABSYSTEM_ERROR_REQUIRED_EMAIL').'",
			COM_CABSYSTEM_ERROR_EMAIL_EMAIL:"'.JText::_('COM_CABSYSTEM_ERROR_EMAIL_EMAIL').'",
			COM_CABSYSTEM_ERROR_REQUIRED_FROM_CITY_ID:"'.JText::_('COM_CABSYSTEM_ERROR_REQUIRED_FROM_CITY_ID').'",
			COM_CABSYSTEM_ERROR_REQUIRED_FROM_DISTRICT_ID:"'.JText::_('COM_CABSYSTEM_ERROR_REQUIRED_FROM_DISTRICT_ID').'",
			COM_CABSYSTEM_ERROR_REQUIRED_FROM_STREET_ID:"'.JText::_('COM_CABSYSTEM_ERROR_REQUIRED_FROM_STREET_ID').'",
			COM_CABSYSTEM_ERROR_REQUIRED_FROM_ORDERTYPE_ID:"'.JText::_('COM_CABSYSTEM_ERROR_REQUIRED_FROM_ORDERTYPE_ID').'",
			COM_CABSYSTEM_ERROR_REQUIRED_TO_ORDERTYPE_ID:"'.JText::_('COM_CABSYSTEM_ERROR_REQUIRED_TO_ORDERTYPE_ID').'",
			COM_CABSYSTEM_ERROR_REQUIRED_TO_CITY_ID:"'.JText::_('COM_CABSYSTEM_ERROR_REQUIRED_TO_CITY_ID').'",
			COM_CABSYSTEM_ERROR_REQUIRED_TO_DISTRICT_ID:"'.JText::_('COM_CABSYSTEM_ERROR_REQUIRED_TO_DISTRICT_ID').'",
			COM_CABSYSTEM_ERROR_REQUIRED_TO_STREET_ID:"'.JText::_('COM_CABSYSTEM_ERROR_REQUIRED_TO_STREET_ID').'",
			COM_CABSYSTEM_ERROR_REQUIRED_FROM_FLIGHT_ID:"'.JText::_('COM_CABSYSTEM_ERROR_REQUIRED_FROM_FLIGHT_ID').'",
			COM_CABSYSTEM_ERROR_REQUIRED_TO_FLIGHT_ID:"'.JText::_('COM_CABSYSTEM_ERROR_REQUIRED_TO_FLIGHT_ID').'",
			COM_CABSYSTEM_ERROR_REQUIRED_POSTORDER_FROM_FLIGHT_ID:"'.JText::_('COM_CABSYSTEM_ERROR_REQUIRED_POSTORDER_FROM_FLIGHT_ID').'",
			COM_CABSYSTEM_ERROR_REQUIRED_POSTORDER_FLIGHT_NUMBER:"'.JText::_('COM_CABSYSTEM_ERROR_REQUIRED_POSTORDER_FLIGHT_NUMBER').'",
			COM_CABSYSTEM_ERROR_REQUIRED_DATE:"'.JText::_('COM_CABSYSTEM_ERROR_REQUIRED_DATE').'",
			COM_CABSYSTEM_ERROR_REQUIRED_TIME:"'.JText::_('COM_CABSYSTEM_ERROR_REQUIRED_TIME').'",
			COM_CABSYSTEM_ERROR_LOCKOUT_TIME:"'.JText::_('COM_CABSYSTEM_ERROR_LOCKOUT_TIME').'",
			COM_CABSYSTEM_ERROR_REQUIRED_POSTORDER_DATE:"'.JText::_('COM_CABSYSTEM_ERROR_REQUIRED_POSTORDER_DATE').'",
			COM_CABSYSTEM_ERROR_REQUIRED_POSTORDER_TIME:"'.JText::_('COM_CABSYSTEM_ERROR_REQUIRED_POSTORDER_TIME').'",
			COM_CABSYSTEM_ERROR_REQUIRED_TOS_ACCEPTED:"'.JText::_('COM_CABSYSTEM_ERROR_REQUIRED_TOS_ACCEPTED').'",
			COM_CABSYSTEM_ERROR_REQUIRED_ADDITIONALADDRESSES_DISTRICT:"'.JText::_('COM_CABSYSTEM_ERROR_REQUIRED_ADDITIONALADDRESSES_DISTRICT').'",
			COM_CABSYSTEM_ERROR_REQUIRED_GENERAL:"'.JText::_('COM_CABSYSTEM_ERROR_REQUIRED_GENERAL').'",
			COM_CABSYSTEM_ERROR_LUGGAGE1:"'.JText::_('COM_CABSYSTEM_ERROR_LUGGAGE1').'",
			COM_CABSYSTEM_ERROR_LUGGAGE2:"'.JText::_('COM_CABSYSTEM_ERROR_LUGGAGE2').'",
			COM_CABSYSTEM_ERROR_HANDLUGGAGE1:"'.JText::_('COM_CABSYSTEM_ERROR_HANDLUGGAGE1').'",
			COM_CABSYSTEM_ERROR_HANDLUGGAGE2:"'.JText::_('COM_CABSYSTEM_ERROR_HANDLUGGAGE2').'",
			COM_CABSYSTEM_ERROR_PERSONS1:"'.JText::_('COM_CABSYSTEM_ERROR_PERSONS1').'",
			COM_CABSYSTEM_ERROR_PERSONS2:"'.JText::_('COM_CABSYSTEM_ERROR_PERSONS2').'",
			COM_CABSYSTEM_LANG_PRICE_FOR_RETURN:"'.JText::_('COM_CABSYSTEM_LANG_PRICE_FOR_RETURN').'"
		}');

		$document->addScript(JURI::base().'components/com_cabsystem/assets/js/'.$viewName.'.js');

		//View spezifische Scripts
		switch($viewName) {
			case 'statistic':
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