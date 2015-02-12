
<?php

// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );



jimport('joomla.application.component.view');



//Display partial views

class CabsystemViewsStatisticPhtml extends JView
{
	function render()
	{
		$app = JFactory::getApplication();
		$layout = $app->input->get('layout');

		//retrieve task list from model
		$driverModel = new CabsystemModelsDriver();

		switch($layout) {
			case "list":
			default:
				$this->drivers = $driverModel->listItems();
				$this->orderModel = new CabsystemModelsOrder();
				
				$this->_statisticListView = CabsystemHelpersView::load('statistic', '_entry', 'phtml');
				break;
		}

		//display
		return parent::display();
	}
}