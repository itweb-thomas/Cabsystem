<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.view');

class CabsystemViewsOrderHtml extends JView
{
	function render()
	{
		$app = JFactory::getApplication();
		$layout = $app->input->get('layout');

		//retrieve task list from model
		$orderModel = new CabsystemModelsOrder();
		$cityModel = new CabsystemModelsCity();
		$destinationCityModel = new CabsystemModelsDestination_city();
		$districtModel = new CabsystemModelsDistrict();
		$streetModel = new CabsystemModelsStreet();
		$ordertypeModel = new CabsystemModelsOrdertype();
		$cartypeModel = new CabsystemModelsCartype();
		$paymentmethodModel = new CabsystemModelsPaymentmethod();
		$additionaladdressModel = new CabsystemModelsAdditionaladdress();
		$salutationModel = new CabsystemModelsSalutation();
		$titleModel = new CabsystemModelsTitle();
		$driverModel = new CabsystemModelsDriver();
		$flightModel = new CabsystemModelsFlight();

		switch($layout) {
			case "list":
			default:
				$this->orders = $orderModel->listItems();
				$this->cities = $cityModel->listItems();
				$this->districts = $districtModel->listItems();
				$this->streets = $streetModel->listItems();
				$this->destination_cities = $destinationCityModel->listItems();
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

				//Alle Destinationen durchlaufen
				foreach($this->destination_cities as &$destination_city) {
					//Zu jedem Ort die Bezirke laden
					$flightModel->_city_id = $destination_city->city_id;
					$destination_city->flightnumbers = $flightModel->listItems();
				}
				
				$this->salutations = $salutationModel->listItems();
				$this->titles = $titleModel->listItems();
				$this->ordertypes = $ordertypeModel->listItems();
				$this->cartypes = $cartypeModel->listItems();
				$this->additionaladdresses = $additionaladdressModel->listItems();
				$this->paymentmethods = $paymentmethodModel->listItems();
				$this->drivers = $driverModel->listItems();
				
				$this->_orderListView = CabsystemHelpersView::load('order', '_entry', 'phtml');

				$this->_orderAddView = CabsystemHelpersView::load('order', '_add', 'phtml', array('streets'=>$this->streets,'ordertypes'=>$this->ordertypes,'cities'=>$this->cities,'districts'=>$this->districts,'destination_cities'=>$this->destination_cities,'cartypes'=>$this->cartypes,'additionaladdresses'=>$this->additionaladdresses,'paymentmethods'=>$this->paymentmethods,'salutations'=>$this->salutations,'titles'=>$this->titles,'drivers'=>$this->drivers));

				break;
		}

		//display
		return parent::display();
	}
}