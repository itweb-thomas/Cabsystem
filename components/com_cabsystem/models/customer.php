<?php

// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' ); 

 

class CabsystemModelsCustomer extends CabsystemModelsDefault
{

	/**

	* Protected fields

	**/

	var $_customer_id = null;

	var $_name = null;

	var $_phone = null;

	var $_email = null;

	var $_house = null;

	var $_stair = null;

	var $_door = null;

	var $_street_id = null;
	
	var $_street_name = null;

	var $_salutation_id = null;
	
	var $_salutation_language_string = null;

	var $_title_id = null;
	
	var $_title_name = null;

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
		$id = $this->id ? $this->id : $app->input->get('customer_id');
		$this->_customer_id = $id;
		return parent::getItem();
	}

	function store($data=null) {
		$data = $data ? $data : JRequest::get('post');
		if(empty($data['title_id'])) {
			$data['title_id'] = NULL;
		}
		$row = parent::store($data);
		
		if(is_numeric($row->street_id))  
		{
			$db = JFactory::getDBO();
			$query = $db->getQuery(TRUE);
			
			$query->select('s.name AS street_name,d.district_id,d.district AS district_name,d.zip AS district_zip,i.city_id,i.name AS city_name');
	
			$query->from('#__cabsystem_streets as s');
			$query->leftjoin('#__cabsystem_districts as d on d.district_id = s.district_id');
			$query->leftjoin('#__cabsystem_cities as i on i.city_id = d.city_id');
			$query->where('s.street_id = ' . (int) $row->street_id);
			
			$db->setQuery($query);
			$item = $db->loadObject();
			if(!empty($item->street_name)) 
			{
				$row->street_name = $item->street_name;
			}
			if(!empty($item->district_name)) 
			{
				$row->district_name = $item->district_name;
			}
			if(!empty($item->district_zip)) 
			{
				$row->district_zip = $item->district_zip;
			}
			if(!empty($item->city_name)) 
			{
				$row->city_name = $item->city_name;
			}
		}
		
		if(is_numeric($row->salutation_id))  
		{
			$db = JFactory::getDBO();
			$query = $db->getQuery(TRUE);
			
			$query->select('a.name AS salutation_language_string');
	
			$query->from('#__cabsystem_customer_salutations as a');
			$query->where('a.salutation_id = ' . (int) $row->salutation_id);
			
			$db->setQuery($query);
			$item = $db->loadObject();
			if(!empty($item->salutation_language_string))
			{
				$row->salutation_language_string = $item->salutation_language_string;
			}
		}
		
		if(!is_null($row->title_id) && is_numeric($row->title_id))  
		{
			$db = JFactory::getDBO();
			$query = $db->getQuery(TRUE);
			
			$query->select('t.name AS title_name');
	
			$query->from('#__cabsystem_customer_titles as t');
			$query->where('t.title_id = ' . (int) $row->title_id);
			
			$db->setQuery($query);
			$item = $db->loadObject();
			if(!empty($item->title_name)) 
			{
				$row->title_name = $item->title_name;
			}
		}
		
		$result = array();
		$result['row'] = $row;
		$result['tr'] = CabsystemHelpersView::getHtml('customer', '_entry', 'customer', $this->getItem());
		return $result;
	}

	protected function _buildQuery()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(TRUE);
		
		$query->select('c.*,s.name AS street_name,a.name AS salutation_language_string,t.name AS title_name,d.district_id,d.district AS district_name,d.zip AS district_zip,i.city_id,i.name AS city_name');

		$query->from('#__cabsystem_customers as c');
		$query->leftjoin('#__cabsystem_streets as s on s.street_id = c.street_id');
		$query->leftjoin('#__cabsystem_districts as d on d.district_id = s.district_id');
		$query->leftjoin('#__cabsystem_cities as i on i.city_id = d.city_id');
		$query->leftjoin('#__cabsystem_customer_salutations as a on a.salutation_id = c.salutation_id');
		$query->leftjoin('#__cabsystem_customer_titles as t on t.title_id = c.title_id');
		$query->where('c.deleted IS NULL');
		
		return $query;
	}

	protected function _buildWhere($query)
	{
		if(is_numeric($this->_customer_id)) 
		{
		  $query->where('c.customer_id = ' . (int) $this->_customer_id);
		}

		if(is_numeric($this->_street_id)) 
		{
		  $query->where('c.street_id = ' . (int) $this->_street_id);

		}

		return $query;
	}
	
	/**
	* Delete a customer
	* @param int ID of the customer to delete
	* @return boolean True if successfully deleted
	*/
	public function delete($id = null)
	{
		$app = JFactory::getApplication();
		$id = $id ? $id : $app->input->get('customer_id');
		
		$customer = JTable::getInstance('customers','Table');
		$customer->load($id);
		
		$customer->deleted = date('Y-m-d H:i:s');
		
		if($customer->store())
		{
			return true;
		} else {
			return false;
		}
	}
}