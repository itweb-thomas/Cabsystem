<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.view');

class CabsystemViewsCityHtml extends JView
{
	function render()
	{
		$app = JFactory::getApplication();
		$layout = $app->input->get('layout');

		//retrieve task list from model
		$cityModel = new CabsystemModelsCity();

		switch($layout) {
			case "list":
			default:
				$this->cities = $cityModel->listItems();
				$this->_cityListView = CabsystemHelpersView::load('city', '_entry', 'phtml');
				$this->_cityAddView = CabsystemHelpersView::load('city', '_add', 'phtml');
				break;
		}
		
		//display
		return parent::display();
	}
}