<?php

defined( '_JEXEC' ) or die( 'Restricted access' );



jimport('joomla.application.component.view');



class CabsystemViewsDistrictHtml extends JView

{

	function render()

	{

		$app = JFactory::getApplication();

		$layout = $app->input->get('layout');

		

		//retrieve task list from model
		$this->_districtModel = new CabsystemModelsDistrict();
		$cartypeModel = new CabsystemModelsCartype();
		$cityModel = new CabsystemModelsCity();

		switch($layout) {

			case "list":

			default:

				$this->districts = $this->_districtModel->listItems();
				$this->cartypes = $cartypeModel->listItems();
				$this->cities = $cityModel->listItems();

				$this->_districtListView = CabsystemHelpersView::load('district', '_entry', 'phtml');

				$this->_districtAddView = CabsystemHelpersView::load('district', '_add', 'phtml', array('cartypes'=>$this->cartypes,'cities'=>$this->cities));

				break;

		}

		

		//display

		return parent::display();

	}

}