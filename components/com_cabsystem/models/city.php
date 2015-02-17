<?php

// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' ); 

 

class CabsystemModelsCity extends CabsystemModelsDefault
{

	/**

	* Protected fields

	**/

	var $_city_id = null;

	var $_name = null;
	
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
		$id = $this->id ? $this->id : $app->input->get('city_id');
		$this->_city_id = $id;
		return parent::getItem();
	}
	
	function store($data=null) {
		if($row = parent::store($data)) {
			$result = array();
			$result['row'] = $row;
			$result['tr'] = CabsystemHelpersView::getHtml('city', '_entry', 'city', $this->getItem());
			return $result;
		}
		else {
			return false;
		}
	}
	
	protected function _buildQuery()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(TRUE);
		
		$query->select('*');

		$query->from('#__cabsystem_cities as c');
		$query->where('c.deleted IS NULL');
		$query->order('-c.order DESC, c.name');
		
		return $query;
	}

	protected function _buildWhere($query)
	{
		if(is_numeric($this->_city_id)) 
		{
		  $query->where('c.city_id = ' . (int) $this->_city_id);
		}
		if($this->_name)
		{
			$query->where('c.name = "' . $this->_name.'"');
		}

		return $query;
	}
	
	/**
	* Delete a city
	* @param int ID of the city to delete
	* @return boolean True if successfully deleted
	*/
	public function delete($id = null)
	{
		$app = JFactory::getApplication();
		$id = $id ? $id : $app->input->get('city_id');
		
		$city = JTable::getInstance('cities','Table');
		$city->load($id);
		
		$city->deleted = date('Y-m-d H:i:s');
		
		if($city->store())
		{
			return true;
		} else {
			return false;
		}
	}
}