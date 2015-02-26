<?php

defined( '_JEXEC' ) or die( 'Restricted access' );



jimport('joomla.application.component.view');



class CabsystemViewsLockoutHtml extends JView

{

	function render()

	{

		$app = JFactory::getApplication();

		$layout = $app->input->get('layout');

		

		//retrieve task list from model
		$lockoutModel = new CabsystemModelsLockout();

		switch($layout) {

			case "list":

			default:

				$this->lockouts = $lockoutModel->listItems();

				$this->_lockoutListView = CabsystemHelpersView::load('lockout', '_entry', 'phtml');

				$this->_lockoutAddView = CabsystemHelpersView::load('lockout', '_add', 'phtml', array());

				break;

		}

		

		//display

		return parent::display();

	}

}