<?php

// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' ); 

 

class CabsystemModelsDriver extends CabsystemModelsDefault
{

	/**

	* Protected fields

	**/

	var $_driver_id = null;

	var $_name = null;

	var $_email = null;

	var $_active = null;

	var $_cartype_id = null;
	
	var $_cartype_name = null;

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
		$id = $this->id ? $this->id : $app->input->get('driver_id');
		$this->_driver_id = $id;
		return parent::getItem();
	}

	function store($data=null) {
		$row = parent::store($data);
		
		if(is_numeric($row->cartype_id))  
		{
			$db = JFactory::getDBO();
			$query = $db->getQuery(TRUE);
			
			$query->select('c.name AS cartype_name');
	
			$query->from('#__cabsystem_cartypes as c');
			$query->where('c.cartype_id = ' . (int) $row->cartype_id);
			
			$db->setQuery($query);
			$item = $db->loadObject();
			if(!empty($item->cartype_name)) 
			{
				$row->cartype_name = $item->cartype_name;
			}
		}
		
		$result = array();
		$result['row'] = $row;
		$result['tr'] = CabsystemHelpersView::getHtml('driver', '_entry', 'driver', $this->getItem());
		return $result;
	}

	protected function _buildQuery()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(TRUE);
		
		$query->select('d.*,c.name AS cartype_name');

		$query->from('#__cabsystem_drivers as d');
		$query->leftjoin('#__cabsystem_cartypes as c on c.cartype_id = d.cartype_id');
		$query->where('d.deleted IS NULL');
		
		return $query;
	}

	protected function _buildWhere($query)
	{
		if(is_numeric($this->_driver_id)) 
		{
		  $query->where('d.driver_id = ' . (int) $this->_driver_id);
		}

		if(is_numeric($this->_cartype_id)) 
		{
		  $query->where('d.cartype_id = ' . (int) $this->_cartype_id);

		}

		return $query;
	}
	
	/**
	* Delete a driver
	* @param int ID of the driver to delete
	* @return boolean True if successfully deleted
	*/
	public function delete($id = null)
	{
		$app = JFactory::getApplication();
		$id = $id ? $id : $app->input->get('driver_id');
		
		$driver = JTable::getInstance('drivers','Table');
		$driver->load($id);
		
		$driver->deleted = date('Y-m-d H:i:s');
		
		if($driver->store())
		{
			return true;
		} else {
			return false;
		}
	}
	
	/**
	* Lock a driver
	* @param int ID of the driver to lock
	* @return boolean True if successfully locked
	*/
	public function lock($id = null)
	{
		$app = JFactory::getApplication();
		$id = $id ? $id : $app->input->get('driver_id');
		
		$driver = JTable::getInstance('drivers','Table');
		$driver->load($id);
		
		$driver->active = 0;
		
		if($driver->store())
		{
			return true;
		} else {
			return false;
		}
	}
	
	/**
	* Unlock a driver
	* @param int ID of the driver to unlock
	* @return boolean True if successfully unlocked
	*/
	public function unlock($id = null)
	{
		$app = JFactory::getApplication();
		$id = $id ? $id : $app->input->get('driver_id');
		
		$driver = JTable::getInstance('drivers','Table');
		$driver->load($id);
		
		$driver->active = 1;
		
		if($driver->store())
		{
			return true;
		} else {
			return false;
		}
	}
}