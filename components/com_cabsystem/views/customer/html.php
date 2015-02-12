<?php

defined( '_JEXEC' ) or die( 'Restricted access' );



jimport('joomla.application.component.view');



class CabsystemViewsCustomerHtml extends JView

{

	function render()

	{

		$app = JFactory::getApplication();

		$layout = $app->input->get('layout');

		

		//retrieve task list from model
		$customerModel = new CabsystemModelsCustomer();
		$cityModel = new CabsystemModelsCity();
		$districtModel = new CabsystemModelsDistrict();
		$streetModel = new CabsystemModelsStreet();
		$salutationModel = new CabsystemModelsSalutation();
		$titleModel = new CabsystemModelsTitle();

		switch($layout) {

			case "list":

			default:

				$this->customers = $customerModel->listItems();
				$this->cities = $cityModel->listItems();
				$this->districts = $districtModel->listItems();
				$this->streets = $streetModel->listItems();
				//Alle Orte durchlaufen
				foreach($this->cities as &$city) {
					//Zu jedem Ort die Bezirke laden
					$districtModel->_city_id = $city->city_id;
					$city->districts = $districtModel->listItems();
					//Alle Bezirke des Ortes durchlaufen
					foreach($city->districts as &$district) {
						//Zu jedem Bezirk die Strassen laden
						$streetModel->_district_id = $district->district_id;
						$district->streets = $streetModel->listItems();
					}
				}
				
				$this->salutations = $salutationModel->listItems();
				$this->titles = $titleModel->listItems();
				
				$this->_customerListView = CabsystemHelpersView::load('customer', '_entry', 'phtml');

				$this->_customerAddView = CabsystemHelpersView::load('customer', '_add', 'phtml', array('streets'=>$this->streets,'salutations'=>$this->salutations,'titles'=>$this->titles,'cities'=>$this->cities,'districts'=>$this->districts));

				break;

		}

		

		//display

		return parent::display();

	}

}