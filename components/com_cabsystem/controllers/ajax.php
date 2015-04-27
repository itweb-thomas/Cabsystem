<?php
defined( '_JEXEC' ) or die( 'Restricted access' ); 
jimport('joomla.application.component.controller');

class CabsystemControllersAjax extends JController
{
	public function execute()
	{
	}
	
	public function exportOrders() {
		function cleanData(&$str) { 
			$str = preg_replace("/\t/", "\\t", $str); 
			$str = preg_replace("/\r?\n/", "\\n", $str); 
			// convert 't' and 'f' to boolean values 
			if($str == 't') $str = 'TRUE'; 
			if($str == 'f') $str = 'FALSE'; 
			// force certain number/date formats to be imported as strings 
			if(preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{1,2}.\d{1,2}.\d{4}/", $str)) { $str = "$str"; }
			
			if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
			$str = mb_convert_encoding($str, 'UTF-16LE', 'UTF-8');
		}
		
		$data = JRequest::get('post');
		$data_field_names = array(
			"id"=>"Nummer",
			"date"=>"Datum",
			"time"=>"Zeit",
			"comment"=>"Anmerkung",
			"from"=>"Von",
			"to"=>"Nach",
			"additional_addresses"=>"Zusatzadressen",
			"customer"=>"Kunde",
			"price"=>"Preis",
			"paymentmethod"=>"Bezahlvariante",
			"luggage"=>"Koffer",
			"handluggage"=>"Handgepäck",
			"child_seat"=>"Kindersitze",
			"child_seat_elevation"=>"Kindersitzerhöhungen",
			"maxi_cosi"=>"Maxi Cosis",
			"creator"=>"Erstellt von",
			"cartype"=>"Autotyp",
			"driver"=>"Fahrer"
		);
		
		if(isset($data['fields'])) {
			$list = array();
			
			//Header bauen
			$header = array();
			foreach($data['fields'] as $field) {
				array_push($header,$data_field_names[$field]);
			}
			
			array_push($list,$header);
			
			$orderModel = new CabsystemModelsOrder();
			$orderModel->set('_where_only_in_past',true);
			$orderModel->set('_status',2);
			if(!empty($data['from-date'])) {
				$orderModel->set('_from_date',$data['from-date']);
			}
			if(!empty($data['to-date'])) {
				$orderModel->set('_to_date',$data['to-date']);
			}
			if(!empty($data['driver'])) {
				$orderModel->set('_driver_id',$data['driver']);
			}
			if(!empty($data['paymentmethod'])) {
				$orderModel->set('_paymentmethod_id',$data['paymentmethod']);
			}
			
			$orders = $orderModel->listItems();
				
			if(count($orders)>0) {
				//Elemente hinzufuegen
				foreach($orders as $order) {
					$item = array();
					foreach($data['fields'] as $field) {	
						$field_value = '""';
						switch($field) {
							case 'id':
								$field_value = $order->order_id;
							break;
							case 'date':
								$field_value = date("d.m.Y", strtotime($order->datetime));
							break;
							case 'time':
								$field_value = date("H:i", strtotime($order->datetime));
							break;
							case 'comment':
								$field_value = $order->comment;
							break;
							case 'from':
								$field_value = JText::_($order->from_ordertype_language_string);
								if ($order->from_ordertype_type != 'airport') {
									$field_value .= $order->from_street_name.' ';
									$field_value .= !empty($order->from_house) ? $order->from_house : ''; 
									$field_value .= !empty($order->from_stair) ? '/'.$order->from_stair : ''; 
									$field_value .= !empty($order->from_door) ? '/'.$order->from_door : '';
									$field_value .= ', ';
									$field_value .= $order->from_district_zip.' '.$order->from_city_name.' '.$order->from_district_name;
								}
								elseif ($order->from_ordertype_type == 'airport') {
									$field_value .= $order->flight_number.' '.$order->destionation_city_name.' '.date("H:i", strtotime($order->flight_time));
								}
							break;
							case 'to':
								$field_value = JText::_($order->to_ordertype_language_string);
								if ($order->to_ordertype_type != 'airport') {
									$field_value .= ' | ';
									$field_value .= $order->to_street_name.' ';
									$field_value .= !empty($order->to_house) ? $order->to_house : ''; 
									$field_value .= !empty($order->to_stair) ? '/'.$order->to_stair : ''; 
									$field_value .= !empty($order->to_door) ? '/'.$order->to_door : '';
									$field_value .= ', ';
									$field_value .= $order->to_district_zip.' '.$order->to_city_name.' '.$order->to_district_name;
								}
							break;
							case 'additional_addresses':
								$field_value = '';
								if(!empty($order->additionaladdresses_name)) {
									$field_value .= $order->additionaladdresses_name;
								}
								else {
									$field_value .= 'keine';
								}
								if(!empty($order->additional_address_districts)) {
									$field_value .= ' ';
									foreach(json_decode($order->additional_address_districts) as $additional_address_district) {
										$district = JTable::getInstance('districts','Table');
										$district->load($additional_address_district);
										$field_value .= $district->zip.' '.$district->district.' | ';
									}
								}
							break;
							case 'customer':
								$field_value = '';
								$field_value .= !empty($order->salutation_language_string) ? JText::_($order->salutation_language_string) : '';
								$field_value .= !empty($order->title_name) ? ' '.$order->title_name : ''; 
								$field_value .= ' '.$order->name;
								$field_value .= ' | ';
								
								$field_value .= !empty($order->phone) ? ' '.$order->phone : ''; 
								$field_value .= !empty($order->email) ? ' '.$order->email : ''; 
							break;
							case 'price':
								$field_value = '';
								$field_value .= 'EUR '.$order->price;
								$field_value .= ($order->price_override) ? ' (Spezialpreis)' : ''; 
							break;
							case 'paymentmethod':
								$field_value = !empty($order->paymentmethod_language_string) ? JText::_($order->paymentmethod_language_string) : '';
							break;
							case 'luggage':
								$field_value = !empty($order->luggage) ? $order->luggage.' Koffer' : 'keine Angaben'; 
							break;
							case 'handluggage':
								$field_value = !empty($order->handluggage) ? $order->handluggage.' Handgepäckstücke' : 'keine Angaben'; 
							break;
							case 'child_seat':
								$field_value = !empty($order->child_seat) ? $order->child_seat.' Kindersitze' : 'keine Angaben'; 
							break;
							case 'child_seat_elevation':
								$field_value = !empty($order->child_seat_elevation) ? $order->child_seat_elevation.' Kindersitzerhöhungen' : 'keine Angaben'; 
							break;
							case 'maxi_cosi':
								$field_value = !empty($order->maxi_cosi) ? $order->maxi_cosi.' Maxi Cosis' : 'keine Angaben'; 
							break;
							case 'creator':
								if(!empty($order->creator)) {
									$creator_object = JFactory::getUser($order->creator);
									$field_value = $creator_object->name;
								}
							break;
							case 'cartype':
								$field_value = $order->cartype_name;
							break;
							case 'driver':
								$field_value = !empty($order->driver_name) ? $order->driver_name : 'keiner'; 
							break;
							default:
						}
						array_push($item,$field_value);
					}
					array_push($list,$item);
				}
			}
				
			if(count($list)>0) {
				$filename = 'com_cabsystem_export.xls';
				
				header("Content-Disposition: attachment; filename=\"$filename\""); 
				header("Content-Type: text/csv; charset=UTF-16LE");
				
				foreach($list as $row) { 
					array_walk($row, 'cleanData'); 
					echo implode("\t", $row) . "\r\n"; 
				} 
				exit;
			}
		}
	}
	
	public function getSelection()
	{
		$app      = JFactory::getApplication();
		$return   = array("success" => false);

		$modelName  = $app->input->get('model');
		$modelName  = 'CabsystemModels'.ucwords($modelName);

		$model = new $modelName();
		$return['data'] = $model->getItemList();
		
		echo json_encode($return);
	}
	
	public function getPrice()
	{
		$app      = JFactory::getApplication();
		$return   = array("success" => true,"error" => array());
		
		$sum_price = 0;
		
		//Preis fuer Bezirk/Cartype holen
		$district  = $app->input->get('district');
		$cartype  = $app->input->get('cartype');
		
		if(!empty($district) && $district != 'null' && !empty($cartype) && $cartype != 'null') {
			$model = new CabsystemModelsPrice();
			$model->set('_district_id',$district);
			$model->set('_cartype_id',$cartype);
			if($item = $model->getItem()) {
				$sum_price += $item->price;
			}
			else {
				$return['success'] = false;
				array_push($return['error'],'FEHLER BEI DISTRICT/CARTYPE: '.$district.', '.$cartype);
			}
		}
		
		//Preis fuer Bezahlvariante holen
		/*$paymentmethod  = $app->input->get('paymentmethod');
		
		if(!empty($paymentmethod) && $paymentmethod != 'null') {
			$model = new CabsystemModelsPaymentmethod();
			$model->set('_paymentmethod_id',$paymentmethod);
			if($item = $model->getItem()) {
				$sum_price += $item->price;
			}
			else {
				$return['success'] = false;
				array_push($return['error'],'FEHLER BEI PAYMENTMETHOD');
			}
		}*/
		
		//Preise fuer AdditionalAddresses Bezirke holen
		$additionaladdress_districts  = $app->input->get('additionaladdress_districts',array(),'array');
		
		if(!empty($additionaladdress_districts) && count($additionaladdress_districts)>0) {
			foreach($additionaladdress_districts as $additionaladdress_district) {
				if(!empty($additionaladdress_district)) {
					$model = new CabsystemModelsPrice();
					$model->set('_district_id',$additionaladdress_district);
					$model->set('_cartype_id',$cartype);
					if($item = $model->getItem()) {
						$sum_price += $item->additional_address_price;
					}
					else {
						$return['success'] = false;
						array_push($return['error'],'FEHLER BEI ADDITIONALADDRESS_DISTRICTS');
					}
				}
			}
		}
		
		//Preise fuer AdditionalAddresses holen (falls dort ein Preis eingetragen wurde)
		$additionaladdress  = $app->input->get('additionaladdress');
		
		if(!empty($additionaladdress) && $additionaladdress != 'null') {
			$model = new CabsystemModelsAdditionalprice();
			$model->set('_additionaladdress_id',$additionaladdress);
			$model->set('_cartype_id',$cartype);
			if($item = $model->getItem()) {
				$sum_price += $item->price;
			}
			else {
				$return['success'] = false;
				array_push($return['error'],'FEHLER BEI ADDITIONALADDRESS');
			}
		}
		
		//Preis fuer Kindersitze berechnen
		$child_seat_amount  = $app->input->get('child_seat_amount');
		$sum_price += $child_seat_amount*5;
		//Preis fuer MaxiCosis berechnen
		$maxi_cosi_amount  = $app->input->get('maxi_cosi_amount');
		$sum_price += $maxi_cosi_amount*5;
		//Preis fuer Kindersitzerhoehung berechnen
		$child_seat_elevation_amount  = $app->input->get('child_seat_elevation_amount');
		$sum_price += $child_seat_elevation_amount*2;

		if($return['success']) {
			$return['price'] = $sum_price;;
		}
		else {
			$return['msg'] = JText::_('COM_CABSYSTEM_PRICE_GETPRICE_FAILURE');
		}
		
		echo json_encode($return);
	}
	
	public function setDriverToOrder() {
		$app = JFactory::getApplication();
		
		$return = array("success"=>false);
		
		$order_id = $app->input->get('order_id');
		$driver_id = $app->input->get('driver_id');
		
		$modelName = 'CabsystemModelsOrder';
		$model = new $modelName();	
		if ( $row = $model->setDriver($order_id,$driver_id) )
		{
			$return['success'] = true;			
			
			//Wenn ein Fahrer angegeben wurde
			if(!is_null($driver_id) && !empty($driver_id) && $driver_id != '') {
				//Status auf PENDING setzen
				$model->setStatus($order_id,1);
				$return['msg'] = JText::_('COM_CABSYSTEM_ORDER_SET_DRIVER_SUCCESS');
			}
			//Wenn kein Fahrer angegeben wurde
			else {
				//Status auf NEU setzen
				$model->setStatus($order_id,0);
				$return['msg'] = JText::_('COM_CABSYSTEM_ORDER_SET_DRIVER_SUCCESS_NO_DRIVER');
			}
			
			$return['tr'] = CabsystemHelpersView::getHtml('order', '_entry', 'order', $model->getItem());
		}else
		{
			$return['msg'] = JText::_('COM_CABSYSTEM_ORDER_SET_DRIVER_FAILURE');
		}
		
		echo json_encode($return);
	}
	
	public function cancelOrder()
	{
		$app = JFactory::getApplication();
		$return = array("success"=>false);

		$order_id 	= $app->input->get('order_id');
		
		$model = new CabsystemModelsOrder();
	
		if ( $row = $model->setStatus($order_id,3) )
		{
			$return['success'] = true;
			$return['msg'] = JText::_('COM_CABSYSTEM_ORDER_CANCEL_SUCCESS');
			$return['tr'] = CabsystemHelpersView::getHtml('order', '_entry', 'order', $model->getItem());
			$return['datatable_data'] = $row;
		}else
		{
			$return['msg'] = JText::_('COM_CABSYSTEM_ORDER_CANCEL_FAILURE');
		}
		
		echo json_encode($return);
	}
}