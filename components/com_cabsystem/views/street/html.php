<?php

defined( '_JEXEC' ) or die( 'Restricted access' );



jimport('joomla.application.component.view');



class CabsystemViewsStreetHtml extends JView

{

	function render()

	{

		$app = JFactory::getApplication();

		$layout = $app->input->get('layout');

		

		//retrieve task list from model
		$streetModel = new CabsystemModelsStreet();
		$districtModel = new CabsystemModelsDistrict();

		switch($layout) {

			case "list":

			default:

				$this->streets = $streetModel->listItems();
				$this->districts = $districtModel->listItems();

				$this->_streetListView = CabsystemHelpersView::load('street', '_entry', 'phtml');

				$this->_streetAddView = CabsystemHelpersView::load('street', '_add', 'phtml', array('districts'=>$this->districts));

				break;

		}

		

		//display

		return parent::display();

	}

}