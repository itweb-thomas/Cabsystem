<?php

// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' ); 

 

class CabsystemModelsLockout extends CabsystemModelsDefault
{

	/**

	* Protected fields

	**/

	var $_lockout_id = null;

	var $_hour = null;

	var $_active = null;

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
		$id = $this->id ? $this->id : $app->input->get('lockout_id');
		$this->_lockout_id = $id;
		return parent::getItem();
	}

	function store($data=null) {
		$data = $data ? $data : JRequest::get('post');
		if(isset($data['active'])) {
			$data['active'] = 1;
		}
		else {
			$data['active'] = 0;
		}

		$row = parent::store($data);
		$this->id = $row->lockout_id;
		$this->_lockout_id = $row->lockout_id;
		
		$result = array();
		$result['row'] = $row;
		$result['tr'] = CabsystemHelpersView::getHtml('lockout', '_entry', 'lockout', self::getItem());
		return $result;
	}

	protected function _buildQuery()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(TRUE);
		
		$query->select('l.*');

		$query->from('#__cabsystem_lockouts as l');
		$query->where('l.deleted IS NULL');
		
		return $query;
	}

	protected function _buildWhere($query)
	{
		if(is_numeric($this->_lockout_id)) 
		{
		  $query->where('l.lockout_id = ' . (int) $this->_lockout_id);
		}

		return $query;
	}
	
	/**
	* Delete a lockout
	* @param int ID of the lockout to delete
	* @return boolean True if successfully deleted
	*/
	public function delete($id = null)
	{
		$app = JFactory::getApplication();
		$id = $id ? $id : $app->input->get('lockout_id');
		
		$lockout = JTable::getInstance('lockouts','Table');
		$lockout->load($id);
		
		$lockout->deleted = date('Y-m-d H:i:s');
		
		if($lockout->store())
		{
			return true;
		} else {
			return false;
		}
	}
	
	/**
	* Lock a lockout
	* @param int ID of the lockout to lock
	* @return boolean True if successfully locked
	*/
	public function lock($id = null)
	{
		$app = JFactory::getApplication();
		$id = $id ? $id : $app->input->get('lockout_id');
		
		$lockout = JTable::getInstance('lockouts','Table');
		$lockout->load($id);
		
		$lockout->active = 1;
		
		if($lockout->store())
		{
			return true;
		} else {
			return false;
		}
	}
	
	/**
	* Unlock a lockout
	* @param int ID of the lockout to unlock
	* @return boolean True if successfully unlocked
	*/
	public function unlock($id = null)
	{
		$app = JFactory::getApplication();
		$id = $id ? $id : $app->input->get('lockout_id');
		
		$lockout = JTable::getInstance('lockouts','Table');
		$lockout->load($id);
		
		$lockout->active = 0;
		
		if($lockout->store())
		{
			return true;
		} else {
			return false;
		}
	}
}