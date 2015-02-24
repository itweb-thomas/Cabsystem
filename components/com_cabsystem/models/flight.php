<?php

// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' ); 

 

class CabsystemModelsFlight extends CabsystemModelsDefault
{

	/**

	* Protected fields

	**/

	var $_flight_id = null;

	var $_flightnumber = null;

	var $_time = null;

	var $_city_id = null;
	
	var $_city_name = null;

	var $_created = null;

	var $_modified = null;

	var $_deleted = null;


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
		$id = $this->id ? $this->id : $app->input->get('flight_id');
		$this->_flight_id = $id;
		return parent::getItem();
	}

	function store($data=null) {
		$row = parent::store($data);
		$date = new DateTime($row->time);
		$row->time = $date->format('H:i:s');
		if(is_numeric($row->city_id))  
		{
			$db = JFactory::getDBO();
			$query = $db->getQuery(TRUE);
			
			$query->select('c.name AS city_name');
	
			$query->from('#__cabsystem_destination_cities as c');
			$query->where('c.city_id = ' . (int) $row->city_id);
			
			$db->setQuery($query);
			$item = $db->loadObject();
			if(!empty($item->city_name)) 
			{
				$row->city_name = $item->city_name;
			}
		}
		
		$result = array();
		$result['row'] = $row;
		$result['tr'] = CabsystemHelpersView::getHtml('flight', '_entry', 'flight', $this->getItem());
		return $result;
	}

	protected function _buildQuery()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(TRUE);
		
		$query->select('f.*,c.name AS city_name');

		$query->from('#__cabsystem_flights as f');
		$query->leftjoin('#__cabsystem_destination_cities as c on c.city_id = f.city_id');
		$query->where('f.deleted IS NULL');
		
		return $query;
	}

	protected function _buildWhere($query)
	{
		if(is_numeric($this->_flight_id)) 
		{
		  $query->where('f.flight_id = ' . (int) $this->_flight_id);
		}

		if(is_numeric($this->_city_id))
		{
		  $query->where('f.city_id = ' . (int) $this->_city_id);

		}

		if($this->_flightnumber)
		{
			$query->where('f.flightnumber = "' . $this->_flightnumber.'"');
		}

		return $query;
	}
	
	/**
	* Delete a flight
	* @param int ID of the flight to delete
	* @return boolean True if successfully deleted
	*/
	public function delete($id = null)
	{
		$app = JFactory::getApplication();
		$id = $id ? $id : $app->input->get('flight_id');
		
		$flight = JTable::getInstance('flights','Table');
		$flight->load($id);
		
		$flight->deleted = date('Y-m-d H:i:s');
		
		if($flight->store())
		{
			return true;
		} else {
			return false;
		}
	}
}