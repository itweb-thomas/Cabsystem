<?php

// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' ); 

 

class CabsystemModelsSalutation extends CabsystemModelsDefault

{



	/**

	* Protected fields

	**/

	var $_salutation_id = null;

	var $_language_string = null;

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

		$query->from('#__cabsystem_customer_salutations as s');

		

		return $query;

	}

	

	protected function _buildWhere($query)

	{

		if(is_numeric($this->_salutation_id)) 

		{

		  $query->where('s.salutation_id = ' . (int) $this->_salutation_id);

		}

		return $query;

	}

}