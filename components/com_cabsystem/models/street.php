<?php

// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' ); 

 

class CabsystemModelsStreet extends CabsystemModelsDefault
{

	/**

	* Protected fields

	**/

	var $_street_id = null;

	var $_name = null;

	var $_district_id = null;
	
	var $_district_name = null;
	
	var $_district_zip = null;

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
		$id = $this->id ? $this->id : $app->input->get('street_id');
		$this->_street_id = $id;
		return parent::getItem();
	}

	function store($data=null) {
		$row = parent::store($data);
		$date = new DateTime($row->time);
		$row->time = $date->format('H:i:s');
		if(is_numeric($row->district_id))  
		{
			$db = JFactory::getDBO();
			$query = $db->getQuery(TRUE);
			
			$query->select('d.district AS district_name,d.zip AS district_zip');
	
			$query->from('#__cabsystem_districts as d');
			$query->where('d.district_id = ' . (int) $row->district_id);
			
			$db->setQuery($query);
			$item = $db->loadObject();
			if(!empty($item->district_name)) 
			{
				$row->district_name = $item->district_name;
			}
			if(!empty($item->district_zip)) 
			{
				$row->district_zip = $item->district_zip;
			}
		}
		
		$result = array();
		$result['row'] = $row;
		$result['tr'] = CabsystemHelpersView::getHtml('street', '_entry', 'street', $this->getItem());
		return $result;
	}

	protected function _buildQuery()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(TRUE);
		
		$query->select('s.*,d.district AS district_name,d.zip AS district_zip');

		$query->from('#__cabsystem_streets as s');
		$query->leftjoin('#__cabsystem_districts as d on d.district_id = s.district_id');
		$query->where('s.deleted IS NULL');
		$query->order('s.name');
		
		return $query;
	}

	protected function _buildWhere($query)
	{
		if(is_numeric($this->_street_id)) 
		{
		  $query->where('s.street_id = ' . (int) $this->_street_id);
		}

		if(is_numeric($this->_district_id)) 
		{
		  $query->where('s.district_id = ' . (int) $this->_district_id);

		}

		if($this->_name)
		{
			$query->where('s.name = "' . $this->_name.'"');
		}

		return $query;
	}
	
	/**
	* Delete a street
	* @param int ID of the street to delete
	* @return boolean True if successfully deleted
	*/
	public function delete($id = null)
	{
		$app = JFactory::getApplication();
		$id = $id ? $id : $app->input->get('street_id');
		
		$street = JTable::getInstance('streets','Table');
		$street->load($id);
		
		$street->deleted = date('Y-m-d H:i:s');
		
		if($street->store())
		{
			return true;
		} else {
			return false;
		}
	}
}