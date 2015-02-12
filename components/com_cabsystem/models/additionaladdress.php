<?php

// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' ); 

 

class CabsystemModelsAdditionaladdress extends CabsystemModelsDefault
{

	/**

	* Protected fields

	**/

	var $_additionaladdress_id = null;

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
		$id = $this->id ? $this->id : $app->input->get('additionaladdress_id');
		$this->_additionaladdress_id = $id;
		$item = parent::getItem();
		self::getAdditionalprices($item);
		return $item;
	}
	
	function getAdditionalprices(&$row) 
	{
		if(is_numeric($row->additionaladdress_id))  
		{
			$db = JFactory::getDBO();
			$query = $db->getQuery(TRUE);
			
			$query->select('p.additionalprice_id, p.price AS cartype_price, c.name AS cartype_name, c.cartype_id');
	
			$query->from('#__cabsystem_additionaladdresses as a');
			$query->leftjoin('#__cabsystem_additionalprices as p on p.additionaladdress_id = a.additionaladdress_id');
			$query->leftjoin('#__cabsystem_cartypes as c on c.cartype_id = p.cartype_id');
			$query->where('a.additionaladdress_id = ' . (int) $row->additionaladdress_id);
			
			$db->setQuery($query);
			$items = $db->loadObjectList();
			if(count($items) > 0) 
			{
				$row->cartype_prices = array();
				foreach($items as $item) 
				{
					$row->cartype_prices[''.$item->cartype_id]['additionalprice_id'] = $item->additionalprice_id;
					$row->cartype_prices[''.$item->cartype_id]['price'] = $item->cartype_price;
				}
			}
		}
	}
	
	private function storeAdditionalprices(&$row, $data) 
	{
		//Cartypes holen
		$db = JFactory::getDBO();
		$query = $db->getQuery(TRUE);
		$query->select('*');
		$query->from('#__cabsystem_cartypes as c');		
		$db->setQuery($query);
		$cartypes = $db->loadObjectList();
		
		$row->cartype_prices = array();
		foreach($cartypes as $cartype) 
		{
			
			//Wenn der Preis ausgefuellt wurde
			if(isset($data['addForm-cartype_price'.$cartype->cartype_id]))
			{
				unset($additionalprice_tmp);
				//Wenn es ein hidden Field mit dem namen editForm-cartype_price<<CARTYPE_ID>>-id gibt, dann wurde zu diesem Cartype schon
				//ein Preis gespeichert und es wird die ID gesetzt, sodass der DB Eintrag ueberspeichert wird
				if(isset($data['editForm-cartype_price'.$cartype->cartype_id.'-id']) && is_numeric($data['editForm-cartype_price'.$cartype->cartype_id.'-id']))
				{
					$additionalprice_tmp->additionalprice_id = $data['editForm-cartype_price'.$cartype->cartype_id.'-id'];
				}
				if(!empty($data['addForm-cartype_price'.$cartype->cartype_id]))
				{
					$additionalprice_tmp->price = number_format($data['addForm-cartype_price'.$cartype->cartype_id],2);
				}
				else
				{
					$additionalprice_tmp->price = NULL;
				}
				$additionalprice_tmp->additionaladdress_id = $row->additionaladdress_id;
				$additionalprice_tmp->cartype_id = $cartype->cartype_id;
	
				$date = date("Y-m-d H:i:s");
		
				$additionalprice = JTable::getInstance('additionalprices', 'Table');
				if (!$additionalprice->bind($additionalprice_tmp))
				{
					return false;
				}		
				
				$additionalprice->modified = $date;
		
				if (!$additionalprice->created)
				{
					$additionalprice->created = $date;
				}
		
				if (!$additionalprice->check())
				{
					return false;
				}
		
				//Hier true bei Paramter updateNulls, da auch additional Prices zurueckgesetzt werden muessen
				if (!$additionalprice->store(true))
				{
					return false;
				}
		
				$row->cartype_prices[''.$additionalprice->cartype_id]['additionalprice_id'] = $additionalprice->additionalprice_id;
				$row->cartype_prices[''.$additionalprice->cartype_id]['price'] = $additionalprice->price;
			}
		}
		return true;
	}

	function store($data=null) {
		$row = parent::store($data);
		
		$data = $data ? $data : JRequest::get('post');
		if(self::storeAdditionalprices($row, $data)) {
			$result = array();
			$result['row'] = $row;
			$result['tr'] = CabsystemHelpersView::getHtml('additionaladdress', '_entry', 'additionaladdress', $this->getItem());
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
		
		$query->select('a.*');

		$query->from('#__cabsystem_additionaladdresses as a');
		$query->where('a.deleted IS NULL');
		
		return $query;
	}

	protected function _buildWhere($query)
	{
		if(is_numeric($this->_additionaladdress_id)) 
		{
		  $query->where('a.additionaladdress_id = ' . (int) $this->_additionaladdress_id);
		}
		
		return $query;
	}
	
	/**
	* Delete a additionaladdress
	* @param int ID of the additionaladdress to delete
	* @return boolean True if successfully deleted
	*/
	public function delete($id = null)
	{
		$app = JFactory::getApplication();
		$id = $id ? $id : $app->input->get('additionaladdress_id');
		
		$additionaladdress = JTable::getInstance('additionaladdresses','Table');
		$additionaladdress->load($id);
		
		$additionaladdress->deleted = date('Y-m-d H:i:s');
		
		if($additionaladdress->store())
		{
			return true;
		} else {
			return false;
		}
	}
}