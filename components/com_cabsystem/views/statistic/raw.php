<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.view');

//Display partial views
class CabsystemViewsStatisticRaw extends JView
{
	function render()
	{
		$app = JFactory::getApplication();
		$layout = $app->input->get('layout');

		//retrieve task list from model
		$driverModel = new CabsystemModelsDriver();

		switch($layout) {
			case "listpdf":
			default:
				$this->drivers = $driverModel->listItems();
				$driver_id = urldecode($app->input->getInt('driver_id'));
				if(!empty($driver_id)) {
					$driverModel->driver_id = $driver_id;
					$this->driver_name = $driverModel->getItem()->name;	
				}				
				$this->orderModel = new CabsystemModelsOrder();
				
				$this->_statisticListView = CabsystemHelpersView::load('statistic', '_entrypdf', 'phtml');

				break;
		}

		//display
		return parent::display();
	}
}