<?php

defined( '_JEXEC' ) or die( 'Restricted access' );



jimport('joomla.application.component.view');



class CabsystemViewsDriverHtml extends JView

{

	function render()

	{

		$app = JFactory::getApplication();

		$layout = $app->input->get('layout');

		

		//retrieve task list from model
		$driverModel = new CabsystemModelsDriver();
		$cartypeModel = new CabsystemModelsCartype();

		switch($layout) {

			case "list":

			default:

				$this->drivers = $driverModel->listItems();
				$this->cartypes = $cartypeModel->listItems();

				$this->_driverListView = CabsystemHelpersView::load('driver', '_entry', 'phtml');

				$this->_driverAddView = CabsystemHelpersView::load('driver', '_add', 'phtml', array('cartypes'=>$this->cartypes));

				break;

		}

		

		//display

		return parent::display();

	}

}