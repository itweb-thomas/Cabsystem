<?php

defined( '_JEXEC' ) or die( 'Restricted access' );



jimport('joomla.application.component.view');



class CabsystemViewsFlightHtml extends JView

{

	function render()

	{

		$app = JFactory::getApplication();

		$layout = $app->input->get('layout');

		

		//retrieve task list from model
		$flightModel = new CabsystemModelsFlight();
		$destionation_cityModel = new CabsystemModelsDestination_city();

		switch($layout) {

			case "list":

			default:

				$this->flights = $flightModel->listItems();
				$this->cities = $destionation_cityModel->listItems();

				$this->_flightListView = CabsystemHelpersView::load('flight', '_entry', 'phtml');

				$this->_flightAddView = CabsystemHelpersView::load('flight', '_add', 'phtml', array('cities'=>$this->cities));

				break;

		}

		

		//display

		return parent::display();

	}

}