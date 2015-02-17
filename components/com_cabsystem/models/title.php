<?php

// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' ); 

 

class CabsystemModelsTitle extends CabsystemModelsDefault

{



	/**

	* Protected fields

	**/

	var $_title_id = null;

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

	

	/*function getItem()

	{

		

	}*/

	

	protected function _buildQuery()

	{

		$db = JFactory::getDBO();

		$query = $db->getQuery(TRUE);

	

		$query->select('*');

		$query->from('#__cabsystem_customer_titles as t');

		

		return $query;

	}

	

	protected function _buildWhere($query)

	{

		if(is_numeric($this->_title_id)) 

		{

		  $query->where('t.title_id = ' . (int) $this->_title_id);

		}

		

		if($this->_name) 

		{

		  $query->where('t.name = "' . $this->_name.'"');

		}

		return $query;

	}

}