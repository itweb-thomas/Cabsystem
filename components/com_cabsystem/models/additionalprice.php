<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); 

class CabsystemModelsAdditionalprice extends CabsystemModelsDefault
{

	/**
	* Protected fields
	**/

	var $_additionalprice_id = null;
	var $_price = null;
	var $_additionaladdress_id = null;
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
		$query->from('#__cabsystem_additionalprices as p');

		return $query;
	}

	protected function _buildWhere($query)
	{
		if(is_numeric($this->_additionalprice_id)) 
		{
		  $query->where('p.additionalprice_id = ' . (int) $this->_additionalprice_id);
		}

		if($this->_cartype_id) 
		{
		  $query->where('p.cartype_id = ' . (int) $this->_cartype_id);
		}

		if($this->_additionaladdress_id) 
		{
		  $query->where('p.additionaladdress_id = ' . (int) $this->_additionaladdress_id);
		}

		return $query;
	}

}