<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); 

class CabsystemModelsPrice extends CabsystemModelsDefault
{

	/**
	* Protected fields
	**/

	var $_price_id = null;
	var $_price = null;
	var $_district_id = null;
	var $_cartype_id = null;
	var $_created = null;
	var $_modified = null;
	var $_deleted = null;

	function __construct()
	{
		parent::__construct();       
	}

	protected function _buildQuery()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(TRUE);

		$query->select('*');
		$query->from('#__cabsystem_prices as p');

		return $query;
	}

	protected function _buildWhere($query)
	{
		if(is_numeric($this->_price_id)) 
		{
		  $query->where('p.price_id = ' . (int) $this->_price_id);
		}

		if($this->_cartype_id) 
		{
		  $query->where('p.cartype_id = ' . (int) $this->_cartype_id);
		}

		if($this->_district_id) 
		{
		  $query->where('p.district_id = ' . (int) $this->_district_id);
		}

		return $query;
	}

}