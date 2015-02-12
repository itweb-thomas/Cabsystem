<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.view');

class CabsystemViewsStatisticHtml extends JView
{
	function render()
	{
		$app = JFactory::getApplication();
		$layout = $app->input->get('layout');

		$driverModel = new CabsystemModelsDriver();
		$paymentmethodModel = new CabsystemModelsPaymentmethod();

		switch($layout) {
			case "list":
			default:
				$this->drivers = $driverModel->listItems();
				$this->paymentmethods = $paymentmethodModel->listItems();
				break;
		}

		//display
		return parent::display();
	}
}