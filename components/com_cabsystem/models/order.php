<?php
//JOOMLA TITEL: Cabsystem Testseite - Airport City Cab - Flughafentaxi Wien
// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' ); 

 

class CabsystemModelsOrder extends CabsystemModelsDefault
{

	/**

	* Protected fields

	**/

	var $_order_id = null;

	var $_datetime = null;

	var $_comment = null;

	var $_price = null;

	var $_from_street_id = null;
	
	var $_from_street_name = null;

	var $_from_house = null;

	var $_from_stair = null;

	var $_from_door = null;

	var $_to_street_id = null;
	
	var $_to_street_name = null;

	var $_to_house = null;

	var $_to_stair = null;

	var $_to_door = null;

	var $_status = null;
	
	var $_customer_id = null;

	var $_driver_id = null;

	var $_driver_name = null;
	
	var $_flight_id = null;
	
	var $_cartype_id = null;
	
	var $_paymentmethod_id = null;
	
	var $_additionaladdresses_id = null;
	
	var $_additionaladdresses_name = null;
	
	var $_postorder_id = null;
	
	var $_preorder_id = null;
	
	var $_from_ordertype_id = null;
	
	var $_to_ordertype_id = null;
	
	var $_luggage = null;
	
	var $_handluggage = null;
	
	var $_child_seat = null;
	
	var $_maxi_cosi = null;
	
	var $_child_seat_elevation = null;

	var $_created = null;

	var $_modified = null;

	var $_deleted = null;

	var $_where_only_in_future = false;

	var $_where_only_in_past = false;

	var $_month = null;

	var $_year = null;
	
	var $_from_date = null;
	
	var $_to_date = null;
	


	//Define class level variables
	//var $_user_id     = null;

	function __construct()
	{
		//$app = JFactory::getApplication();

		//If no User ID is set to current logged in user
		//$this->_user_id = $app->input->get('profile_id', JFactory::getUser()->id);
		parent::__construct();       
	}

	function getItem()
	{
		$app = JFactory::getApplication();
		$id = $this->id ? $this->id : $app->input->get('order_id');
		$this->_order_id = $id;
		
		return parent::getItem();
	}

	function store($data=null) {
		$result = array();
		
		$data = $data ? $data : JRequest::get('post');
		//Customer speichern		
		$data['table'] = "customers";		
		$model = new CabsystemModelsCustomer();

		//LERNENDES SYSTEM: Other abfragen
		if($data['title_id'] == 'other') {
			if(!empty($data['title_id-other'])) {
				//neues Element erzeugen
				$tmp_model = new CabsystemModelsTitle();
				$tmp_model->set('_name',$data['title_id-other']);
				$tmp_element = $tmp_model->getItem();
				$data['title_id'] = $tmp_element->title_id;
				//Wenn kein Element existiert
				if(is_null($tmp_element)) {
					$tmp_data = array();
					$tmp_data['table'] = 'titles';
					$tmp_data['name'] = $data['title_id-other'];
					$tmp_model->store($tmp_data);
					$tmp_element = $tmp_model->getItem();
					$data['title_id'] = $tmp_element->title_id;
				}
			}
			else {
				$data['title_id'] = NULL;
			}
		}
		unset($tmp_element);

		/*if($data['from_city_id'] == 'other') {
			if(!empty($data['from_city_id-other'])) {
				//neues Element erzeugen
				$tmp_model = new CabsystemModelsCity();
				$tmp_model->set('_name',$data['from_city_id-other']);
				$tmp_element = $tmp_model->getItem();
				$data['from_city_id'] = $tmp_element->city_id;
				//Wenn kein Element existiert
				if(is_null($tmp_element)) {
					$tmp_data = array();
					$tmp_data['table'] = 'cities';
					$tmp_data['name'] = $data['from_city_id-other'];
					$tmp_model->store($tmp_data);
					$tmp_element = $tmp_model->getItem();
					$data['from_city_id'] = $tmp_element->city_id;
				}
			}
			else {
				$data['from_city_id'] = NULL;
			}
		}
		unset($tmp_element);

		if($data['to_city_id'] == 'other') {
			if(!empty($data['to_city_id-other'])) {
				//neues Element erzeugen
				$tmp_model = new CabsystemModelsCity();
				$tmp_model->set('_name',$data['to_city_id-other']);
				$tmp_element = $tmp_model->getItem();
				$data['to_city_id'] = $tmp_element->city_id;
				//Wenn kein Element existiert
				if(is_null($tmp_element)) {
					$tmp_data = array();
					$tmp_data['table'] = 'cities';
					$tmp_data['name'] = $data['to_city_id-other'];
					$tmp_model->store($tmp_data);
					$tmp_element = $tmp_model->getItem();
					$data['to_city_id'] = $tmp_element->city_id;
				}
			}
			else {
				$data['to_city_id'] = NULL;
			}
		}
		unset($tmp_element);

		if($data['from_district_id'] == 'other') {
			if(!empty($data['from_district_id-other']) && !empty($data['from_city_id'])) {
				//neues Element erzeugen
				$tmp_model = new CabsystemModelsDistrict();
				//ZIP TODO
				$tmp_model->set('_zip','0000');
				$tmp_model->set('_district',$data['from_district_id-other']);
				$tmp_model->set('_city_id',$data['from_city_id']);
				$tmp_element = $tmp_model->getItem();
				$data['from_district_id'] = $tmp_element->district_id;
				//Wenn kein Element existiert
				if(is_null($tmp_element)) {
					$tmp_data = array();
					$tmp_data['table'] = 'districts';
					//ZIP TODO
					$tmp_data['zip'] = '0000';
					$tmp_data['district'] = $data['from_district_id-other'];
					$tmp_data['city_id'] = $data['from_city_id'];
					$tmp_model->store($tmp_data);
					$tmp_element = $tmp_model->getItem();
					$data['from_district_id'] = $tmp_element->district_id;
				}
			}
			else {
				$data['from_district_id'] = NULL;
			}
		}
		unset($tmp_element);

		if($data['to_district_id'] == 'other') {
			if(!empty($data['to_district_id-other']) && !empty($data['to_city_id'])) {
				//neues Element erzeugen
				$tmp_model = new CabsystemModelsDistrict();
				//ZIP TODO
				$tmp_model->set('_zip','9999');
				$tmp_model->set('_district',$data['to_district_id-other']);
				$tmp_model->set('_city_id',$data['to_city_id']);
				$tmp_element = $tmp_model->getItem();
				$data['to_district_id'] = $tmp_element->district_id;
				//Wenn kein Element existiert
				if(is_null($tmp_element)) {
					$tmp_data = array();
					$tmp_data['table'] = 'districts';
					//ZIP TODO
					$tmp_data['zip'] = '9999';
					$tmp_data['district'] = $data['to_district_id-other'];
					$tmp_data['city_id'] = $data['to_city_id'];
					$tmp_model->store($tmp_data);
					$tmp_element = $tmp_model->getItem();
					$data['to_district_id'] = $tmp_element->district_id;
				}
			}
			else {
				$data['to_district_id'] = NULL;
			}
		}
		unset($tmp_element);*/

		if($data['from_street_id'] == 'other') {
			if(!empty($data['from_street_id-other']) && !empty($data['from_district_id'])) {
				//neues Element erzeugen
				$tmp_model = new CabsystemModelsStreet();
				$tmp_model->set('_name',$data['from_street_id-other']);
				$tmp_model->set('_district_id',$data['from_district_id']);
				$tmp_element = $tmp_model->getItem();
				$data['from_street_id'] = $tmp_element->street_id;
				//Wenn kein Element existiert
				if(is_null($tmp_element)) {
					$tmp_data = array();
					$tmp_data['table'] = 'streets';
					$tmp_data['name'] = $data['from_street_id-other'];
					$tmp_data['district_id'] = $data['from_district_id'];
					$tmp_model->store($tmp_data);
					$tmp_element = $tmp_model->getItem();
					$data['from_street_id'] = $tmp_element->street_id;
				}
			}
			else {
				$data['from_street_id'] = NULL;
			}
		}
		unset($tmp_element);

		if($data['to_street_id'] == 'other') {
			if(!empty($data['to_street_id-other']) && !empty($data['to_district_id'])) {
				//neues Element erzeugen
				$tmp_model = new CabsystemModelsStreet();
				$tmp_model->set('_name',$data['to_street_id-other']);
				$tmp_model->set('_district_id',$data['to_district_id']);
				$tmp_element = $tmp_model->getItem();
				$data['to_street_id'] = $tmp_element->street_id;
				//Wenn kein Element existiert
				if(is_null($tmp_element)) {
					$tmp_data = array();
					$tmp_data['table'] = 'streets';
					$tmp_data['name'] = $data['to_street_id-other'];
					$tmp_data['district_id'] = $data['to_district_id'];
					$tmp_model->store($tmp_data);
					$tmp_element = $tmp_model->getItem();
					$data['to_street_id'] = $tmp_element->street_id;
				}
			}
			else {
				$data['to_street_id'] = NULL;
			}
		}
		unset($tmp_element);

		if($data['from_flight_id'] == 'other') {
			if(!empty($data['from_flight_id-other'])) {
				//neues Element erzeugen
				$tmp_model = new CabsystemModelsDestination_city();
				$tmp_model->set('_name',$data['from_flight_id-other']);
				$tmp_element = $tmp_model->getItem();
				$data['from_flight_id'] = $tmp_element->city_id;
				//Wenn kein Element existiert
				if(is_null($tmp_element)) {
					$tmp_data = array();
					$tmp_data['table'] = 'destination_cities';
					$tmp_data['name'] = $data['from_flight_id-other'];
					$tmp_model->store($tmp_data);
					$tmp_element = $tmp_model->getItem();
					$data['from_flight_id'] = $tmp_element->city_id;
				}
			}
			else {
				$data['from_flight_id'] = NULL;
			}
		}
		unset($tmp_element);

		if($data['flight_number'] == 'other') {
			if(!empty($data['flight_number-other'])) {
				//neues Element erzeugen
				$tmp_model = new CabsystemModelsFlight();
				$tmp_model->set('_flightnumber',$data['flight_number-other']);
				$tmp_model->set('_city_id',$data['from_flight_id']);
				$tmp_element = $tmp_model->getItem();
				$data['flight_number'] = $tmp_element->flight_id;
				//Wenn kein Element existiert
				if(is_null($tmp_element)) {
					$tmp_data = array();
					$tmp_data['table'] = 'flights';
					$tmp_data['flightnumber'] = $data['flight_number-other'];
					$tmp_data['time'] = $data['time'];
					$tmp_data['city_id'] = $data['from_flight_id'];
					$tmp_model->store($tmp_data);
					$tmp_element = $tmp_model->getItem();
					$data['flight_number'] = $tmp_element->flight_id;
				}
			}
			else {
				$data['flight_number'] = NULL;
			}
		}
		unset($tmp_element);

		if($data['postorder_from_flight_id'] == 'other') {
			if(!empty($data['postorder_from_flight_id-other'])) {
				//neues Element erzeugen
				$tmp_model = new CabsystemModelsDestination_city();
				$tmp_model->set('_name',$data['postorder_from_flight_id-other']);
				$tmp_element = $tmp_model->getItem();
				$data['postorder_from_flight_id'] = $tmp_element->city_id;
				//Wenn kein Element existiert
				if(is_null($tmp_element)) {
					$tmp_data = array();
					$tmp_data['table'] = 'destination_cities';
					$tmp_data['name'] = $data['postorder_from_flight_id-other'];
					$tmp_model->store($tmp_data);
					$tmp_element = $tmp_model->getItem();
					$data['postorder_from_flight_id'] = $tmp_element->city_id;
				}
			}
			else {
				$data['postorder_from_flight_id'] = NULL;
			}
		}
		unset($tmp_element);

		if($data['postorder_flight_number'] == 'other') {
			if(!empty($data['postorder_flight_number-other'])) {
				//neues Element erzeugen
				$tmp_model = new CabsystemModelsFlight();
				$tmp_model->set('_flightnumber',$data['postorder_flight_number-other']);
				$tmp_model->set('_city_id',$data['postorder_from_flight_id']);
				$tmp_element = $tmp_model->getItem();
				$data['postorder_flight_number'] = $tmp_element->flight_id;
				//Wenn kein Element existiert
				if(is_null($tmp_element)) {
					$tmp_data = array();
					$tmp_data['table'] = 'flights';
					$tmp_data['flightnumber'] = $data['postorder_flight_number-other'];
					$tmp_data['city_id'] = $data['postorder_from_flight_id'];
					$tmp_model->store($tmp_data);
					$tmp_element = $tmp_model->getItem();
					$data['postorder_flight_number'] = $tmp_element->flight_id;
				}
			}
			else {
				$data['postorder_flight_number'] = NULL;
			}
		}
		unset($tmp_element);

		if ($customer_result = $model->store($data))
		{
			//Wenn neu
			if(!isset($data['order_id'])) {
				self::informAdmin('new-order');
			}
			
			$data['customer_id'] = $customer_result['row']->customer_id;
			$data['table'] = "orders";
			//Order speichern
			$data['datetime'] = $data['date'].' '.$data['time'];
			
			//Alle Fremdschluessel auf NULL setzen wenn sie leer sind
			if(empty($data['title_id'])) 
			{
				$data['title_id'] = NULL;
			}
			if(empty($data['from_street_id'])) 
			{
				$data['from_street_id'] = NULL;
			}
			if(empty($data['to_street_id'])) 
			{
				$data['to_street_id'] = NULL;
			}
			if(empty($data['additionaladdresses_id'])) 
			{
				$data['additionaladdresses_id'] = NULL;
			}
			if(empty($data['postorder_id'])) 
			{
				$data['postorder_id'] = NULL;
			}
			if(empty($data['flight_number']))
			{
				$data['flight_number'] = NULL;
			}
			if(empty($data['status'])) 
			{
				$data['status'] = 0;
			}
			
			if(empty($data['flight_id'])) {
				//Abfragen ob Fluhafen als ZIEL oder START gewaehlt wurde (spaeter auch Reisen von A nach B moeglich)
				//Wenn der Flughafen der START ist
				if(JRequest::getVar('from_ordertype_type') == 'airport') {
					$data['flight_id'] = $data['from_flight_id'];
				}
				//Wenn der Flughafen das ZIEL ist
				else if(JRequest::getVar('to_ordertype_type') == 'airport') {
					$data['flight_id'] = $data['to_flight_id'];
				}
				//Route von A NACH B
				else {
					$data['flight_id'] = NULL;
				}
			}
			
			//Creator hinzufuegen
			if (JFactory::getUser()->authorise('cabsystem.admin', 'com_cabsystem')) {
				$data['creator'] = JFactory::getUser()->id;
			}
			else {
				$data['creator'] = NULL;
			}
			
			//Price Override hinzufuegen
			$TMPprice = $data['price'];
			if (!empty($data['price_override'])) {
				$data['price'] = str_replace(",", ".", $data['price_override']);
				$data['price_override'] = 1;
			}
			
			//Bezirke der Zusatzaddressen speichern
			if(empty($data['additionaladdress_districts']) || empty($data['additionaladdress_districts_addresses'])) 
			{
				$data['additional_address_districts'] = NULL;
			}
			else {
				$district_db_data = array();
				for($i=0; $i<count($data['additionaladdress_districts']); $i++) {
					array_push($district_db_data,array($data['additionaladdress_districts'][$i],$data['additionaladdress_districts_addresses'][$i]));
				}
				$data['additional_address_districts'] = json_encode($district_db_data);
			}
			
			
			unset($row);
			
			if($row = parent::store($data)) {
				$this->id = $row->order_id;
				$this->_order_id = $row->order_id;
				
				//wenn Hakerl gesetzt wurde, dann Bestaetigungsemail schicken
				if(isset($data['send_email']) && !empty($data['send_email'])) {
					$row = self::getItem();
					if(self::informCustomer($row,$data['email'],'new-order') == false) {
						return false;
					}
				}
			
				//Wenn Rueckfahrt gebucht wurde, dann diese auch speichern
				if(isset($data['postorder']) && $data['postorder'] == "1") {					
					//Rueckfahrt speichern
					//ZU Daten der Rueckfahrt = VON Daten der aktuellen Fahrt
					//VON Daten der Rueckfahrt = ZU Daten der aktuellen Fahrt
					$TMP_from_ordertype_id = $data['from_ordertype_id'];
					$TMP_from_street_id = $data['from_street_id'];
					$TMP_from_house = $data['from_house'];
					$TMP_from_stair = $data['from_stair'];
					$TMP_from_door = $data['from_door'];
					
					$data['from_ordertype_id'] = $data['to_ordertype_id'];
					$data['from_street_id'] = $data['to_street_id'];
					$data['from_house'] = $data['to_house'];
					$data['from_stair'] = $data['to_stair'];
					$data['from_door'] = $data['to_door'];
					
					$data['to_ordertype_id'] = $TMP_from_ordertype_id;
					$data['to_street_id'] = $TMP_from_street_id;
					$data['to_house'] = $TMP_from_house;
					$data['to_stair'] = $TMP_from_stair;
					$data['to_door'] = $TMP_from_door;
					//PREorder_id der Rueckfahrt = order_id von aktueller Fahrt
					$data['preorder_id'] = $row->order_id;
					//Angaben aus dem Formular fuer die Rueckfahrt nehmen
					$data['time'] = $data['postorder_time'];
					$data['date'] = $data['postorder_date'];
					
					if(!empty($data['postorder_from_flight_id'])) {
						$data['flight_id'] = $data['postorder_from_flight_id'];
						$data['flight_time'] = $data['postorder_time'];
					}
					if(!empty($data['postorder_flight_number'])) {
						$data['flight_number'] = $data['postorder_flight_number'];
					}
					
					//falls Preisoverride = Preis zuruecksetzen
					if($data['price_override'] == 1) {
						$data['price_override'] = $data['price'];
					}
					$data['price'] = $TMPprice;
					
					//bei der Rueckfahrt UNBEDINGT data['postorder'] weggeben, da sonst ENDLOSSCHLEIFE
					unset($data['postorder']);
					$postoder_model = new CabsystemModelsOrder();
					if($row_postorder = $postoder_model->store($data)) {
						//postorder zum aktuellen Model speichern
						$this->postorder_id = $row_postorder['row'][0]->order_id;
						
						//aktuelle Fahrt nochmal speichern, damit postorder_id gesetzt wird
						$row = JTable::getInstance($data['table'], 'Table');
						$row->load($this->_order_id);
						$row->postorder_id = $row_postorder['row'][0]->order_id;
						$row->store(false);
					}
					$result['row'][1] = $row_postorder['row'][0];
					$result['tr'][1] = $row_postorder['tr'][0];
				}
				
				$result['row'][0] = $row;
				$result['tr'][0] = CabsystemHelpersView::getHtml('order', '_entry', 'order', self::getItem());
				return $result;
			}
			else {
				return false;
			}
		}
		else
		{
			return false;
		}
		
		/*if(!is_null($row->title_id) && is_numeric($row->title_id))  
		{
			$db = JFactory::getDBO();
			$query = $db->getQuery(TRUE);
			
			$query->select('t.name AS title_name');
	
			$query->from('#__cabsystem_order_titles as t');
			$query->where('t.title_id = ' . (int) $row->title_id);
			
			$db->setQuery($query);
			$item = $db->loadObject();
			if(!empty($item->title_name)) 
			{
				$row->title_name = $item->title_name;
			}
		}*/
	}

	protected function _buildQuery()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(TRUE);
		
		$query->select('
			o.*,
			sf.name AS from_street_name,
			df.district_id AS from_district_id,
			df.district AS from_district_name,
			df.zip AS from_district_zip,
			yf.city_id AS from_city_id,
			yf.name AS from_city_name, 
			st.name AS to_street_name,
			dt.district_id AS to_district_id,
			dt.district AS to_district_name,
			dt.zip AS to_district_zip,
			yt.city_id AS to_city_id,
			yt.name AS to_city_name, 
			c.*, 
			a.language_string AS salutation_language_string,
			t.name AS title_name,
			dc.name as destionation_city_name, 
			p.language_string as paymentmethod_language_string,
			tf.type AS from_ordertype_type, 
			tf.icon AS from_ordertype_icon, 
			tf.language_string AS from_ordertype_language_string, 
			tt.type AS to_ordertype_type, 
			tt.icon AS to_ordertype_icon,
			tt.language_string AS to_ordertype_language_string,
			ct.name AS cartype_name,
			ad.name AS additionaladdresses_name,
			ad.districts AS additional_address_districts_amount,
			dr.name AS driver_name,
			dr.email AS driver_email,
			fl.flightnumber AS flight_number,
			fl.flight_id AS flight_number_id'
		);

		$query->from('#__cabsystem_orders as o');
		$query->leftjoin('#__cabsystem_streets as sf on sf.street_id = o.from_street_id');
		$query->leftjoin('#__cabsystem_districts as df on df.district_id = sf.district_id');
		$query->leftjoin('#__cabsystem_cities as yf on yf.city_id = df.city_id');
		$query->leftjoin('#__cabsystem_streets as st on st.street_id = o.to_street_id');
		$query->leftjoin('#__cabsystem_districts as dt on dt.district_id = st.district_id');
		$query->leftjoin('#__cabsystem_cities as yt on yt.city_id = dt.city_id');
		$query->leftjoin('#__cabsystem_flights as fl on fl.flight_id = o.flight_number');
		$query->leftjoin('#__cabsystem_customers as c on c.customer_id = o.customer_id');
		$query->leftjoin('#__cabsystem_customer_salutations as a on a.salutation_id = c.salutation_id');
		$query->leftjoin('#__cabsystem_customer_titles as t on t.title_id = c.title_id');
		$query->leftjoin('#__cabsystem_destination_cities as dc on dc.city_id = o.flight_id');
		$query->leftjoin('#__cabsystem_paymentmethods as p on p.paymentmethod_id = o.paymentmethod_id');
		$query->leftjoin('#__cabsystem_ordertypes as tf on tf.ordertype_id = o.from_ordertype_id');
		$query->leftjoin('#__cabsystem_ordertypes as tt on tt.ordertype_id = o.to_ordertype_id');
		$query->leftjoin('#__cabsystem_cartypes as ct on ct.cartype_id = o.cartype_id');
		$query->leftjoin('#__cabsystem_additionaladdresses as ad on ad.additionaladdress_id = o.additionaladdresses_id');
		$query->leftjoin('#__cabsystem_drivers as dr on dr.driver_id = o.driver_id');
		$query->leftjoin('#__cabsystem_orders as po on po.order_id = o.postorder_id');
		$query->where('o.deleted IS NULL');
		
		return $query;
	}

	protected function _buildWhere($query)
	{
		if(is_numeric($this->_order_id)) 
		{
		  $query->where('o.order_id = ' . (int) $this->_order_id);
		}

		if(is_numeric($this->_customer_id)) 
		{
		  $query->where('o.customer_id = ' . (int) $this->_customer_id);
		}

		if(is_numeric($this->_driver_id)) 
		{
		  $query->where('o.driver_id = ' . (int) $this->_driver_id);
		}

		if(is_numeric($this->_paymentmethod_id)) 
		{
		  $query->where('o.paymentmethod_id = ' . (int) $this->_paymentmethod_id);
		}

		if(is_numeric($this->_status)) 
		{
		  $query->where('o.status = ' . (int) $this->_status);
		}

		if(is_numeric($this->_postorder_id)) 
		{
		  $query->where('o.postorder_id = ' . (int) $this->_postorder_id);
		}

		if(is_numeric($this->_preorder_id)) 
		{
		  $query->where('o.preorder_id = ' . (int) $this->_preorder_id);
		}
		
		if($this->_where_only_in_future) {
		  $query->where('o.datetime > CURDATE()');
		}
		
		if($this->_where_only_in_past) {
		  $query->where('o.datetime < CURDATE()');
		}
		
		if($this->_from_date) {
		  $query->where('o.datetime >= "'.$this->_from_date.' 00:00:00"');
		}
		
		if($this->_to_date) {
		  $query->where('o.datetime <= "'.$this->_to_date.' 23:59:59"');
		}
		
		if($this->_month) {
		  $query->where('MONTH(o.datetime) = '.(int)$this->_month);
		}
		
		if($this->_year) {
		  $query->where('YEAR(o.datetime) = '.(int)$this->_year);
		}

		return $query;
	}
	
	/**
	* Delete a order
	* @param int ID of the order to delete
	* @return boolean True if successfully deleted
	*/
	public function delete($id = null)
	{
		$app = JFactory::getApplication();
		$id = $id ? $id : $app->input->get('order_id');
		
		$order = JTable::getInstance('orders','Table');
		$order->load($id);
		
		//Wenn zuvor ein Fahrer zugeordnet war
		if(!is_null($order->driver_id)) {
			//Den Fahrer informieren, dass die Fahrt jetzt keinem Fahrer zugewiesen ist	
			self::informDriver($id,$order->driver_id,'now-no-driver');
		}
		
		$order->deleted = date('Y-m-d H:i:s');
		
		if($order->store())
		{			
			return true;
		} else {
			return false;
		}
	}
	
	/**
	* Set the driver
	* @param int ID of the order
	* @param int ID of the driver to set
	* @return boolean True if successfully set
	*/
	public function setDriver($order_id,$driver_id)
	{
		$app = JFactory::getApplication();
		$order_id = $order_id ? $order_id : $app->input->get('order_id');
		
		$order = JTable::getInstance('orders','Table');
		$order->load($order_id);
		$act_old_driver = $order->driver_id;
		
		//Wenn ein Fahrer angegeben wurde
		if(!is_null($driver_id) && !empty($driver_id) && $driver_id != '') {
			//Fahrer setzen
			$order->driver_id = $driver_id;
			//Wenn der Fahrer nicht der gleiche war wie zuvor
			if($act_old_driver != $driver_id) {
				//Den alten Fahrer informieren
				self::informDriver($order_id,$act_old_driver,'now-other-driver');
				//Den neuen Fahrer informieren
				self::informDriver($order_id,$driver_id,'new-order');
			}
			//Wenn wieder der gleiche Fahrer ausgewählt wurde
			else {
				//Den Fahrer informieren dass die Fahrt neu zugewiesen wurde
				self::informDriver($order_id,$driver_id,'same-order-again');
			}
		}
		//Wenn kein Fahrer angegeben wurde
		else {
			//Fahrer setzen
			$order->driver_id = NULL;
			//Wenn zuvor ein Fahrer zugeordnet war
			if(!is_null($act_old_driver)) {
				//Den Fahrer informieren, dass die Fahrt jetzt keinem Fahrer zugewiesen ist	
				self::informDriver($order_id,$act_old_driver,'now-no-driver');
			}
		}
		
		if($order->store(true))
		{
			return true;
		} else {
			return false;
		}
	}
	
	/**
	* Set the status
	* @param int ID of the order
	* @param int ID of the status to set
	* @return boolean True if successfully set
	*/
	public function setStatus($order_id,$status)
	{
		$app = JFactory::getApplication();
		$order_id = $order_id ? $order_id : $app->input->get('order_id');
		
		//Bestellung holen
		$order = JTable::getInstance('orders','Table');
		$order->load($order_id);
		$order->status = $status;
		//Kunde holen
		$customer = JTable::getInstance('customers','Table');
		$customer->load($order->customer_id);
		
		if($order->store())
		{
			switch($status) {
				case 3:
					if($order->driver_id) {
						//Den Fahrer informieren, dass die Fahrt storniert wurde
						self::informDriver($order_id,$order->driver_id,'order-canceled');
					}
					//Den Kunden informieren, dass die Fahrt storniert wurde
					self::informCustomer($order_id,$customer->email,'order-canceled');
				break;
			}
			return true;
		} else {
			return false;
		}
	}
	
	/**
	* Sends an email to a administrator
	* @param string type of the message
	* @return boolean True if successfully sent
	*/
	public function informAdmin($type)
	{
		$from_email_address = 'office@cabsystem-testseite.at';
		$from_email_name = 'Cabsystem Testseite';
		$subject = '';
		$body = '';
		$to = 'thomas.seif@it-web.at';
		
		$confirm = false;
		
		switch($type) {
			//Wenn eine Fahrt aufgegeben wurde
			case 'new-order':
				$subject = 'Cabsystem Testseite - Neue Bestellung';
				$body .= '<h1>'.$subject.'</h1>';
				$body .= '<p>Es wurde soeben eine neue Bestellung im Cabsystem von Cabsystem Testseite abgegeben. <a href="http://www.cabsystem-testseite.at/admin">Melden Sie sich hier an!</a></p>';
			break;
			default:
		}
		
		if(CabsystemHelpersView::sendMail($from_email_address, $from_email_name, $subject, $body, $to))
		{
			return true;
		} else {
			return false;
		}
	}
	
	/**
	* Sends an email to a customer
	* @param int ID of the order
	* @param string receiver_email of the customer
	* @param string type of the message
	* @return boolean True if successfully sent
	*/
	public function informCustomer($order,$receiver_email,$type)
	{
		if(is_numeric($order)) {
			$order_id = $order;
			$model = new CabsystemModelsOrder();
			$model->set('order_id',$order_id);
			$order = $model->getItem();
		}
		
		$from_email_address = 'office@cabsystem-testseite.at';
		$from_email_name = 'Cabsystem Testseite';
		$subject = '';
		$body = '';
		$to = $receiver_email;
		
		$confirm = false;
		
		switch($type) {
			//Wenn eine Fahrt aufgegeben wurde
			case 'new-order':
				$subject = 'Cabsystem Testseite - '.JText::_('COM_CABSYSTEM_EMAIL_ORDER_SUBJECT_CUSTOMER_DETAILS');
				$body .= '<h1>'.$subject.'</h1>';
				$body .= '<p>'.JText::_('COM_CABSYSTEM_EMAIL_ORDER_CUSTOMER_DETAILS_TEXT_BEFORE').'</p>';
			break;
			//Wenn die Fahrt explizit storniert wurde
			case 'order-canceled':
				$subject = 'Cabsystem Testseite - '.JText::_('COM_CABSYSTEM_EMAIL_ORDER_SUBJECT_CUSTOMER_CANCELED');
				$body .= '<h1>'.$subject.'</h1>';
				$body .= '<p>'.JText::_('COM_CABSYSTEM_EMAIL_ORDER_CUSTOMER_CANCELED_TEXT_BEFORE').'</p>';
			break;
			default:
		}
		
		$body .= self::getOrderMailInfo($order);
		
		$body .= '<p>'.JText::_('COM_CABSYSTEM_EMAIL_ORDER_CUSTOMER_TEXT_AFTER').'</p>';
		
		$body .= '<p>'.JText::_('COM_CABSYSTEM_EMAIL_ORDER_CUSTOMER_COMPANY_INFOS').'</p>';
		
		if(CabsystemHelpersView::sendMail($from_email_address, $from_email_name, $subject, $body, $to))
		{
			return true;
		} else {
			return false;
		}
	}
	
	/**
	* Sends an email to a driver
	* @param int ID of the order
	* @param int ID of the driver
	* @param string type of the message
	* @return boolean True if successfully sent
	*/
	public function informDriver($order_id,$driver_id,$type)
	{
		$driver = JTable::getInstance('drivers','Table');
		$driver->load($driver_id);
		
		$model = new CabsystemModelsOrder();
		$model->set('order_id',$order_id);
		$order = $model->getItem();
		
		$from_email_address = 'office@cabsystem-testseite.at';
		$from_email_name = 'Cabsystem Testseite';
		$subject = '';
		$body = '';
		$to = $driver->email;
		
		//Betreff: Domain DATUM-UHRZEIT | VON_STRING => TO_STRING
		$subject_details = '';
		//DATUM/ZEIT
		$subject_details .= date("d.m.Y", strtotime($order->datetime)).' '.date("H:i", strtotime($order->datetime));
		$subject_details .= ' | ';
		//VON NACH
		$subject_details .= JText::_($order->from_ordertype_language_string);
		$subject_details .= ' => ';
		$subject_details .= JText::_($order->to_ordertype_language_string);
		
		$confirm = false;
		
		switch($type) {
			//Wenn eine Fahrt zugewiesen wurde
			case 'new-order':
				$subject = 'Neue Fahrt';
				$body .= '<h1>'.$subject.'</h1>';
				$subject = $subject_details.': '.$subject;
				$body .= '<p>Es wurde Ihnen soeben folgende Fahrt zugewiesen. Bitte begeben Sie sich zum angegebenen Zeitpunkt zum Kunden.</p>';
				$confirm = true;
			break;
			//Wenn die Fahrt einem anderen Fahrer zugewiesen wurde
			case 'now-other-driver':
				$subject = 'Fahrt anderem Fahrer zugewiesen';
				$body .= '<h1>'.$subject.'</h1>';
				$body .= '<p>Folgende Fahrt wurde einem anderen Fahrer zugewiesen. Sie sind für diese Fahrt ab jetzt nicht mehr zuständig.</p>';
			break;
			//Wenn die Fahrt dem gleichen Fahrer nochmal zugewiesen wurde
			case 'same-order-again':
				$subject = 'Fahrt neu zugewiesen';
				$body .= '<h1>'.$subject.'</h1>';
				$body .= '<p>Es wurde Ihnen soeben folgende bereits zugewiesene Fahrt nochmals zugewiesen. Dies kann aufgrund einer Änderung durchgeführt worden sein. Beachten Sie somit bitte ausschließlich den neuen Auftrag und bestätigen Sie ihn nochmals.</p>';
				$confirm = true;
			break;
			//Wenn die Fahrt jetzt keinem Fahrer mehr zugewiesen ist
			case 'now-no-driver':
				$subject = 'Fahrt zurückgezogen';
				$body .= '<h1>'.$subject.'</h1>';
				$body .= '<p>Folgende Fahrt wurde soeben zurückgezogen. Es ist somit kein Fahrer mehr zugewiesen und Sie sind für diese Fahrt ab jetzt nicht mehr zuständig.</p>';
			break;
			//Wenn die Fahrt explizit storniert wurde
			case 'order-canceled':
				$subject = 'Fahrt storniert';
				$body .= '<h1>'.$subject.'</h1>';
				$body .= '<p>Folgende Fahrt wurde soeben storniert. Sie sind weiterhin als Fahrer zugewiesen, sollen die Fahrt jedoch nicht antreten.</p>';
			break;
			default:
		}
		
		$body .= self::getOrderMailInfo($order);
		
		if($confirm) {
			$body .= '<h2>Bestätigung</h2>';
			$body .= '<p>Bitte nehmen Sie die Fahrt an nachdem Sie alle Vorkehrungen dazu getroffen haben. Der Status der Fahrt verändert sich erst nachdem Sie den Links unten geklickt haben.</p>';
			$body .= '<a href="http://labor03.it-entwicklung.at/index.php?option=com_cabsystem&controller=email&format=raw&tmpl=component&task=acceptOrder&order_id='.$order_id.'&driver_id='.$driver_id.'" target="_blank">ANNEHMEN</a>';
		}
		
		if(CabsystemHelpersView::sendMail($from_email_address, $from_email_name, $subject, $body, $to))
		{
			return true;
		} else {
			return false;
		}
	}

	public function getOrderMailInfo($order) {
		$result = '';
		$result .= '<h2>'.JText::_('COM_CABSYSTEM_EMAIL_ORDER_HEADLINE_ORDER_DETAILS').'</h2>';
		$result .= '<table border="1" style="border:1px solid #000000" cellspacing="0" cellpadding="10">';
		$result .= '<tr>';
		//BESTELLNUMMER
		$result .= '<td width="30%" style="width:30%;"><strong>'.JText::_('COM_CABSYSTEM_EMAIL_ORDER_DETAILS_ORDER_ID').':</strong></td><td width="70%" style="width:70%;">'.$order->order_id.'</td>';
		$result .= '</tr>';
		//DATUM/ZEIT
		$result .= '<tr>';
		$result .= '<td><strong>'.JText::_('COM_CABSYSTEM_EMAIL_ORDER_DETAILS_DATETIME_FROM').':</strong></td><td>'.date("d.m.Y", strtotime($order->datetime)).' | '.date("H:i", strtotime($order->datetime)).'</td>';
		$result .= '</tr>';
		//VON
		$result .= '<tr>';
		$result .= '<td><strong>'.JText::_('COM_CABSYSTEM_EMAIL_ORDER_DETAILS_FROM').':</strong></td><td>'.JText::_($order->from_ordertype_language_string).' | ';
		if ($order->from_ordertype_type != 'airport') {
			$result .= $order->from_street_name.' ';
			$result .= !empty($order->from_house) ? $order->from_house : ''; 
			$result .= !empty($order->from_stair) ? '/'.$order->from_stair : ''; 
			$result .= !empty($order->from_door) ? '/'.$order->from_door : '';
			$result .= ', ';
			$result .= $order->from_district_zip.' '.$order->from_city_name.' '.$order->from_district_name;
		}
		elseif ($order->from_ordertype_type == 'airport') {
			$result .= $order->flight_number.' '.$order->destionation_city_name.' '.date("H:i", strtotime($order->flight_time));
		}
		$result .= '</td>';
		$result .= '</tr>';
		//NACH
		$result .= '<tr>';
		$result .= '<td><strong>'.JText::_('COM_CABSYSTEM_EMAIL_ORDER_DETAILS_TO').':</strong></td><td>'.JText::_($order->to_ordertype_language_string);
		if ($order->to_ordertype_type != 'airport') {
			$result .= ' | ';
			$result .= $order->to_street_name.' ';
			$result .= !empty($order->to_house) ? $order->to_house : ''; 
			$result .= !empty($order->to_stair) ? '/'.$order->to_stair : ''; 
			$result .= !empty($order->to_door) ? '/'.$order->to_door : '';
			$result .= ', ';
			$result .= $order->to_district_zip.' '.$order->to_city_name.' '.$order->to_district_name;
		}
		/*elseif ($order->to_ordertype_type == 'airport') {
			$result .= ' | ';
			$result .= $order->flightnumber.' '.$order->destionation_city_name.':'.$order->time;
		}*/
		$result .= '</td>';
		$result .= '</tr>';
		//ZUSATZADRESSEN
		$result .= '<tr>';
		$result .= '<td><strong>'.JText::_('COM_CABSYSTEM_EMAIL_ORDER_DETAILS_ADDITIONALADDRESSES').':</strong></td><td>';
		if(!empty($order->additionaladdresses_name)) {
			$result .= $order->additionaladdresses_name;
		}
		else {
			$result .= JText::_('COM_CABSYSTEM_NONE');
		}
		if(!empty($order->additional_address_districts)) {
			$result .= '<br/>';
			foreach(json_decode($order->additional_address_districts) as $additional_address_district) {
				if(is_array($additional_address_district)) {
					$district = JTable::getInstance('districts','Table');
					$district->load($additional_address_district[0]);
					$result .= $district->zip.' '.$district->district.'<br/>';
					if(!empty($additional_address_district[1])) {
						$result .= '<em>'.$additional_address_district[1].'</em><br/>';
					}
				}
				//Fuer die alten Restbestaende bei denen noch keine Adressen zu den Bezirken gespeichert wurden
				else {
					$district = JTable::getInstance('districts','Table');
					$district->load($additional_address_district);
					$result .= $district->zip.' '.$district->district.'<br/>';
				}
			}
		}
		$result .= '</td>';
		$result .= '</tr>';
		//KUNDE
		$result .= '<tr>';
		$result .= '<td><strong>'.JText::_('COM_CABSYSTEM_EMAIL_ORDER_DETAILS_CUSTOMER').':</strong></td><td>';
		$result .= !empty($order->salutation_language_string) ? JText::_($order->salutation_language_string) : '';
		$result .= !empty($order->title_name) ? ' '.$order->title_name : ''; 
		$result .= ' '.$order->name;
		$result .= ' | ';
		
		$result .= !empty($order->phone) ? ' '.$order->phone : ''; 
		$result .= !empty($order->email) ? ' '.$order->email : ''; 
		$result .= '</td>';
		$result .= '</tr>';
		//ANMERKUNG
		if(!empty($order->comment))  {
			$result .= '<tr>';
			$result .= '<td><strong>'.JText::_('COM_CABSYSTEM_EMAIL_ORDER_DETAILS_COMMENT').':</strong></td><td>'.$order->comment.'</td>';
			$result .= '</tr>';
		}
		//AUTOTYP
		$result .= '<tr>';
		$result .= '<td><strong>'.JText::_('COM_CABSYSTEM_EMAIL_ORDER_DETAILS_CARTYPE').':</strong></td><td>';
		$result .= $order->cartype_name;
		$result .= '</td>';
		$result .= '</tr>';
		//PERSONEN
		$result .= '<tr>';
		$result .= '<td><strong>'.JText::_('COM_CABSYSTEM_EMAIL_ORDER_DETAILS_PERSONS').':</strong></td><td>';
		$result .= !empty($order->persons) ? $order->persons.' '.JText::_('COM_CABSYSTEM_EMAIL_ORDER_DETAILS_PERSONS_VALUE') : JText::_('COM_CABSYSTEM_NO_DATA');
		$result .= '</td>';
		$result .= '</tr>';
		//KOFFER
		$result .= '<tr>';
		$result .= '<td><strong>'.JText::_('COM_CABSYSTEM_EMAIL_ORDER_DETAILS_LUGGAGE').':</strong></td><td>';
		$result .= !empty($order->luggage) ? $order->luggage.' '.JText::_('COM_CABSYSTEM_EMAIL_ORDER_DETAILS_LUGGAGE_VALUE') : JText::_('COM_CABSYSTEM_NO_DATA');
		$result .= '</td>';
		$result .= '</tr>';
		//HANDGEPAECK
		$result .= '<tr>';
		$result .= '<td><strong>'.JText::_('COM_CABSYSTEM_EMAIL_ORDER_DETAILS_HANDLUGGAGE').':</strong></td><td>';
		$result .= !empty($order->handluggage) ? $order->handluggage.' '.JText::_('COM_CABSYSTEM_EMAIL_ORDER_DETAILS_HANDLUGGAGE_VALUE') : JText::_('COM_CABSYSTEM_NO_DATA');
		$result .= '</td>';
		$result .= '</tr>';
		//KINDERSITZ
		$result .= '<tr>';
		$result .= '<td><strong>'.JText::_('COM_CABSYSTEM_EMAIL_ORDER_DETAILS_CHILD_SEAT').':</strong></td><td>';
		$result .= !empty($order->child_seat) ? $order->child_seat.' '.JText::_('COM_CABSYSTEM_EMAIL_ORDER_DETAILS_CHILD_SEAT_VALUE') : JText::_('COM_CABSYSTEM_NO_DATA');
		$result .= '</td>';
		$result .= '</tr>';
		//MAXI COSI
		$result .= '<tr>';
		$result .= '<td><strong>'.JText::_('COM_CABSYSTEM_EMAIL_ORDER_DETAILS_MAXI_COSI').':</strong></td><td>';
		$result .= !empty($order->maxi_cosi) ? $order->maxi_cosi.' '.JText::_('COM_CABSYSTEM_EMAIL_ORDER_DETAILS_MAXI_COSI_VALUE') : JText::_('COM_CABSYSTEM_NO_DATA');
		$result .= '</td>';
		$result .= '</tr>';
		//KINDERSITZERHOEHUNG
		$result .= '<tr>';
		$result .= '<td><strong>'.JText::_('COM_CABSYSTEM_EMAIL_ORDER_DETAILS_CHILD_SEAT_ELEVATION').':</strong></td><td>';
		$result .= !empty($order->child_seat_elevation) ? $order->child_seat_elevation.' '.JText::_('COM_CABSYSTEM_EMAIL_ORDER_DETAILS_CHILD_SEAT_ELEVATION') : JText::_('COM_CABSYSTEM_NO_DATA');
		$result .= '</td>';
		$result .= '</tr>';
		//RUECKFAHRT
		$result .= '<tr>';
		$result .= '<td><strong>'.JText::_('COM_CABSYSTEM_EMAIL_ORDER_DETAILS_POSTORDER').':</strong></td><td>';
		//Wenn das eine Rueckfahrt ist
		if(!empty($order->preorder_id)) {
			$result .= JText::sprintf('COM_CABSYSTEM_EMAIL_ORDER_DETAILS_POSTORDER_YES',$order->preorder_id);
		}
		else {
			$result .= JText::_('COM_CABSYSTEM_EMAIL_ORDER_DETAILS_POSTORDER_NO');
			
		}
		$result .= '</td>';
		$result .= '</tr>';
		//PREIS
		$result .= '<tr>';
		$result .= '<td><strong>'.JText::_('COM_CABSYSTEM_EMAIL_ORDER_DETAILS_PRICE').':</strong></td><td>';
		$result .= '€ '.$order->price;
		$result .= !empty($order->paymentmethod_language_string) ? ' ('.JText::_('COM_CABSYSTEM_EMAIL_ORDER_DETAILS_PAYMENTMETHOD').': '.JText::_($order->paymentmethod_language_string).')' : '';
		$result .= ($order->price_override) ? ' <strong>'.JText::_('COM_CABSYSTEM_EMAIL_ORDER_DETAILS_SPECIAL_PRICE').'</strong>' : '';
		$result .= '</td>';
		$result .= '</tr>';
		$result .= '</table>';
		return $result;
	}
}