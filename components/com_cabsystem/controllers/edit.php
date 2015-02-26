<?php
defined( '_JEXEC' ) or die( 'Restricted access' ); 
jimport('joomla.application.component.controller');

class CabsystemControllersEdit extends JController
{
	public function execute()
	{
		$app      = JFactory::getApplication();
		$return   = array("success" => false);

		$modelName  = $app->input->get('model');
		$view       = $app->input->get('view');
		$layout     = $app->input->get('layout', '_entry');
		$item       = $app->input->get('item');

		$modelName  = 'CabsystemModels'.ucwords($modelName);

		$model = new $modelName();
		if ($result = $model->store())
		{
			$return['success'] = true;
			$return['msg'] = JText::_('COM_CABSYSTEM_'.strtoupper($item).'_EDIT_SUCCESS');
			
			$return['tr_amount'] = is_array($result['tr']) ? count($result['tr']) : 1;
			$return['tr'] = $result['tr'];
			$return['datatable_data'] = $result['row'];
		}
		else
		{
			$return['msg'] = JText::_('COM_CABSYSTEM_'.strtoupper($item).'_EDIT_FAILURE');
		}

		echo json_encode($return);
	}
	
	public function getEditModal()
	{
		$app      = JFactory::getApplication();
		$return   = array("success" => false);

		$modelName  = $app->input->get('model');
		//TODO additional models dynamisch machen
		//$additional_models  = $app->input->get('additional_models');
		$view       = $app->input->get('view');
		$layout     = $app->input->get('layout', '_edit');
		$item       = $app->input->get('item');

		$modelName  = 'CabsystemModels'.ucwords($modelName);

		$model = new $modelName();
		if ($row = $model->getItem())
		{
			$return['success'] = true;
			//TODO additional models dynamisch machen
			/*
			$additional_vars = array();
			foreach($additional_models as $additional_model) {
				$additional_model_name = 'CabsystemModels'.ucwords($additional_model);
				$additional_model_model = new $additional_model_name();
				$additional_vars['<<MEHRZAHL DES MODELTITELS>>'] = $additional_model_model->listItems();
			}
			*/

			$additional_vars = array();
			switch ($item) 
			{
				case 'additionaladdress':
					$cartypeModel = new CabsystemModelsCartype();
					$additional_vars['cartypes'] = $cartypeModel->listItems();
				break;
				case 'city':
				break;
				case 'destination_city':
				break;
				case 'driver':
					$cartypeModel = new CabsystemModelsCartype();
					$additional_vars['cartypes'] = $cartypeModel->listItems();
				break;
				case 'flight':
					$destionation_cityModel = new CabsystemModelsDestination_city();
					$additional_vars['cities'] = $destionation_cityModel->listItems();
				break;				
				case 'district':
					$cartypeModel = new CabsystemModelsCartype();
					$cityModel = new CabsystemModelsCity();
					$additional_vars['cartypes'] = $cartypeModel->listItems();
					$additional_vars['cities'] = $cityModel->listItems();
				break;			
				case 'street':
					$districtModel = new CabsystemModelsDistrict();
					$additional_vars['districts'] = $districtModel->listItems();
				break;	
				case 'customer':
					$cityModel = new CabsystemModelsCity();
					$districtModel = new CabsystemModelsDistrict();
					$streetModel = new CabsystemModelsStreet();
					$salutationModel = new CabsystemModelsSalutation();
					$titleModel = new CabsystemModelsTitle();
					$additional_vars['cities'] = $cityModel->listItems();
					$additional_vars['districts'] = $districtModel->listItems();
					$additional_vars['streets'] = $streetModel->listItems();
					$additional_vars['salutations'] = $salutationModel->listItems();
					$additional_vars['titles'] = $titleModel->listItems();
				break;
				case 'order':
					//Wenn COPY dann evtl FROM und TO vertauschen
					if($layout == '_copy') {
						//Angeforderte Richtung holen
						$copy_type = $app->input->get('copy_type');

						//Aktuelle Richtung ermitteln
						$act_direction = '';
						if($row->from_ordertype_id == 2) {
							$act_direction = 'from';
						}
						else if($row->to_ordertype_id == 2) {
							$act_direction = 'to';
						}

						//Wenn geforderte Richtung mit aktueller nicht uebereinstimmt - WECHSEL
						if($copy_type != $act_direction) {
							$TMP_from_ordertype_id = $row->from_ordertype_id;
							$TMP_from_street_id = $row->from_street_id;
							$TMP_from_street_name = $row->from_street_name;
							$TMP_from_district_id = $row->from_district_id;
							$TMP_from_district_name = $row->from_district_name;
							$TMP_from_district_zip = $row->from_district_zip;
							$TMP_from_city_id = $row->from_city_id;
							$TMP_from_city_name = $row->from_city_name;
							$TMP_from_house = $row->from_house;
							$TMP_from_stair = $row->from_stair;
							$TMP_from_door = $row->from_door;

							$row->from_ordertype_id = $row->to_ordertype_id;
							$row->from_street_id = $row->to_street_id;
							$row->from_street_name = $row->to_street_name;
							$row->from_district_id = $row->to_district_id;
							$row->from_district_name = $row->to_district_name;
							$row->from_district_zip = $row->to_district_zip;
							$row->from_city_id = $row->to_city_id;
							$row->from_city_name = $row->to_city_name;
							$row->from_house = $row->to_house;
							$row->from_stair = $row->to_stair;
							$row->from_door = $row->to_door;

							$row->to_ordertype_id = $TMP_from_ordertype_id;
							$row->to_street_id = $TMP_from_street_id;
							$row->to_street_name = $TMP_from_street_name;
							$row->to_district_id = $TMP_from_district_id;
							$row->to_district_name = $TMP_from_district_name;
							$row->to_district_zip = $TMP_from_district_zip;
							$row->to_city_id = $TMP_from_city_id;
							$row->to_city_name = $TMP_from_city_name;
							$row->to_house = $TMP_from_house;
							$row->to_stair = $TMP_from_stair;
							$row->to_door = $TMP_from_door;

							//Rueckfahrtoptionen zuruecksetzen
							unset($row->postorder_id);
							unset($row->preorder_id);

							//Customer ID zuruecksetzen
							unset($row->customer_id);

							//ID zuruecksetzen
							unset($row->order_id);

							//created/modified zuruecksetzen
							unset($row->created);
							unset($row->modified);
						}
					}
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
					$flightModel = new CabsystemModelsFlight();
					$lockoutModel = new CabsystemModelsLockout();
					
					$additional_vars['streets'] = $streetModel->listItems();
					$additional_vars['ordertypes'] = $ordertypeModel->listItems();
					$additional_vars['cities'] = $cityModel->listItems();
					$additional_vars['districts'] = $districtModel->listItems();
					$additional_vars['destination_cities'] = $destinationCityModel->listItems();
					$additional_vars['cartypes'] = $cartypeModel->listItems();
					$additional_vars['additionaladdresses'] = $additionaladdressModel->listItems();
					$additional_vars['paymentmethods'] = $paymentmethodModel->listItems();
					$additional_vars['salutations'] = $salutationModel->listItems();
					$additional_vars['titles'] = $titleModel->listItems();
					$additional_vars['lockouts'] = $lockoutModel->listItems();
					
					//Alle Orte durchlaufen
					foreach($additional_vars['cities'] as &$city) {
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
					foreach($additional_vars['destination_cities'] as &$destination_city) {
						//Zu jedem Ort die Bezirke laden
						$flightModel->_city_id = $destination_city->city_id;
						$destination_city->flightnumbers = $flightModel->listItems();
					}
				break;	
			}
			$return['html'] = CabsystemHelpersView::getHtml($view, $layout, $item, $row, $additional_vars);
		}

		echo json_encode($return);
	}
	
	public function getSetDriverModal()
	{
		$app      = JFactory::getApplication();
		$return   = array("success" => false);

		$modelName  = 'CabsystemModelsOrder';
		$view       = 'order';
		$layout     = '_setdriver';
		$item       = 'order';
		$order_id = $app->input->get('order_id');

		$model = new $modelName();
		$model->set('order_id',$order_id);
		if ($row = $model->getItem())
		{
			$return['success'] = true;
			$driverModel = new CabsystemModelsDriver();
			$driverModel->set('_cartype_id',$row->cartype_id);
			$additional_vars['drivers'] = $driverModel->listItems();
			$return['html'] = CabsystemHelpersView::getHtml($view, $layout, $item, $row, $additional_vars);
		}

		echo json_encode($return);
	}
}