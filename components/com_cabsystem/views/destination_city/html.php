<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.view');

class CabsystemViewsDestination_cityHtml extends JView
{
	function render()
	{
		$app = JFactory::getApplication();
		$layout = $app->input->get('layout');

		//retrieve task list from model
		$cityModel = new CabsystemModelsDestination_city();

		switch($layout) {
			case "list":
			default:
				$this->destination_cities = $cityModel->listItems();
				$this->_cityListView = CabsystemHelpersView::load('destination_city', '_entry', 'phtml');
				$this->_cityAddView = CabsystemHelpersView::load('destination_city', '_add', 'phtml');
				break;
		}
		
		//display
		return parent::display();
	}
}