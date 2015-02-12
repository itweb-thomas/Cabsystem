<?php

defined( '_JEXEC' ) or die( 'Restricted access' );



jimport('joomla.application.component.view');



class CabsystemViewsAdditionaladdressHtml extends JView

{

	function render()

	{

		$app = JFactory::getApplication();

		$layout = $app->input->get('layout');

		

		//retrieve task list from model
		$this->_additionaladdressModel = new CabsystemModelsAdditionaladdress();
		$cartypeModel = new CabsystemModelsCartype();

		switch($layout) {

			case "list":

			default:

				$this->additionaladdresses = $this->_additionaladdressModel->listItems();
				$this->cartypes = $cartypeModel->listItems();

				$this->_additionaladdressListView = CabsystemHelpersView::load('additionaladdress', '_entry', 'phtml');

				$this->_additionaladdressAddView = CabsystemHelpersView::load('additionaladdress', '_add', 'phtml', array('cartypes'=>$this->cartypes));

				break;

		}

		

		//display

		return parent::display();

	}

}