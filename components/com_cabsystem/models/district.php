<?php

// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' ); 

 

class CabsystemModelsDistrict extends CabsystemModelsDefault
{

	/**

	* Protected fields

	**/

	var $_district_id = null;

	var $_zip = null;

	var $_district = null;

	var $_city_id = null;

	var $_city_name = null;

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

	/*function getTest() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(TRUE);
		
		$query->select('*');

		$query->from('bo_bezirks as b');
		
		$db->setQuery($query);
		$items = $db->loadObjectList();
		if(count($items) > 0) 
		{
			echo 'INSERT INTO `s9w4g_cabsystem_prices` (`price`, `district_id`, `cartype_id`) VALUES<br/>';
			foreach($items as $item) 
			{
				echo '('.$item->limo.','.$item->id.',1),<br/>';
				echo '('.$item->kombi.','.$item->id.',2),<br/>';
				echo '('.$item->van.','.$item->id.',3),<br/>';
				echo '(-1,'.$item->id.',4),<br/>';
			}
		}
	}
	
	function getTest2() {
		$db = JFactory::getDBO();
		$query = $db->getQuery(TRUE);
		
		$query->select('*');

		$query->from('bo_streets');
		
		$db->setQuery($query);
		$items = $db->loadObjectList();
		if(count($items) > 0) 
		{
			echo 'INSERT INTO `s9w4g_cabsystem_streets` (`street_id`, `name`, `district_id`) VALUES<br/>';
			foreach($items as $item) 
			{
				echo '('.$item->id.',\''.str_ireplace("'","`",$item->name).'\','.$item->bezirk_id.'),<br/>';
			}
		}
	}*/

	function getItem()
	{
		$app = JFactory::getApplication();
		$id = $this->id ? $this->id : $app->input->get('district_id');
		$this->_district_id = $id;
		$item = parent::getItem();
		self::getPrices($item);
		return $item;
	}
	
	function getPrices(&$row) 
	{
		if(is_numeric($row->district_id))  
		{
			$db = JFactory::getDBO();
			$query = $db->getQuery(TRUE);
			
			$query->select('p.price_id, p.additional_address_price, p.price AS cartype_price, c.name AS cartype_name, c.cartype_id');
	
			$query->from('#__cabsystem_districts as d');
			$query->leftjoin('#__cabsystem_prices as p on p.district_id = d.district_id');
			$query->leftjoin('#__cabsystem_cartypes as c on c.cartype_id = p.cartype_id');
			$query->where('d.district_id = ' . (int) $row->district_id);
			
			$db->setQuery($query);
			$items = $db->loadObjectList();
			if(count($items) > 0) 
			{
				$row->cartype_prices = array();
				$row->cartype_prices_additional_address = array();
				foreach($items as $item) 
				{
					$row->cartype_prices[''.$item->cartype_id]['price_id'] = $item->price_id;
					$row->cartype_prices[''.$item->cartype_id]['price'] = $item->cartype_price;
					$row->cartype_prices_additional_address[''.$item->cartype_id]['price_id'] = $item->price_id;
					$row->cartype_prices_additional_address[''.$item->cartype_id]['additional_address_price'] = $item->additional_address_price;
				}
			}
		}
	}
	
	private function storePrices(&$row, $data) 
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
			if(isset($data['addForm-cartype_price'.$cartype->cartype_id]) || isset($data['addForm-cartype_price_additional_address'.$cartype->cartype_id]))
			{
				//Normaler Preis
				unset($price_tmp);
				//Wenn es ein hidden Field mit dem namen editForm-cartype_price<<CARTYPE_ID>>-id gibt, dann wurde zu diesem Cartype schon
				//ein Preis gespeichert und es wird die ID gesetzt, sodass der DB Eintrag ueberspeichert wird
				if(isset($data['editForm-cartype_price'.$cartype->cartype_id.'-id']) && is_numeric($data['editForm-cartype_price'.$cartype->cartype_id.'-id']))
				{
					$price_tmp->price_id = $data['editForm-cartype_price'.$cartype->cartype_id.'-id'];
				}
				if(!empty($data['addForm-cartype_price'.$cartype->cartype_id]))
				{
					$price_tmp->price = number_format($data['addForm-cartype_price'.$cartype->cartype_id],2);
				}
				else
				{
					$price_tmp->price = "-1";
				}
				
				//Preis fuer Zusatzadressen
				//Wenn es ein hidden Field mit dem namen editForm-cartype_price<<CARTYPE_ID>>-id gibt, dann wurde zu diesem Cartype schon
				//ein Preis gespeichert und es wird die ID gesetzt, sodass der DB Eintrag ueberspeichert wird
				if(isset($data['editForm-cartype_price_additional_address'.$cartype->cartype_id.'-id']) && is_numeric($data['editForm-cartype_price_additional_address'.$cartype->cartype_id.'-id']))
				{
					$price_tmp->price_id = $data['editForm-cartype_price_additional_address'.$cartype->cartype_id.'-id'];
				}
				if(!empty($data['addForm-cartype_price_additional_address'.$cartype->cartype_id]))
				{
					$price_tmp->additional_address_price = number_format($data['addForm-cartype_price_additional_address'.$cartype->cartype_id],2);
				}
				else
				{
					$price_tmp->additional_address_price = "-1";
				}
				
				$price_tmp->district_id = $row->district_id;
				$price_tmp->cartype_id = $cartype->cartype_id;
	
				$date = date("Y-m-d H:i:s");
		
				$price = JTable::getInstance('prices', 'Table');
				if (!$price->bind($price_tmp))
				{
					return false;
				}		
				
				$price->modified = $date;
		
				if (!$price->created)
				{
					$price->created = $date;
				}
		
				if (!$price->check())
				{
					return false;
				}
		
				//Hier true bei Paramter updateNulls, da auch additional Prices zurueckgesetzt werden muessen
				if (!$price->store(true))
				{
					return false;
				}
		
				$row->cartype_prices[''.$price->cartype_id]['price_id'] = $price->price_id;
				$row->cartype_prices[''.$price->cartype_id]['price'] = $price->price;
				$row->cartype_prices_additional_address[''.$price->cartype_id]['price_id'] = $price->price_id;
				$row->cartype_prices_additional_address[''.$price->cartype_id]['additional_address_price'] = $price->additional_address_price;
			}
		}
		return true;
	}

	function store($data=null) {
		$row = parent::store($data);
		if(is_numeric($row->city_id))  
		{
			$db = JFactory::getDBO();
			$query = $db->getQuery(TRUE);
			
			$query->select('c.name AS city_name');
	
			$query->from('#__cabsystem_cities as c');
			$query->where('c.city_id = ' . (int) $row->city_id);
			
			$db->setQuery($query);
			$item = $db->loadObject();
			if(!empty($item->city_name)) 
			{
				$row->city_name = $item->city_name;
			}
		}
		$data = $data ? $data : JRequest::get('post');
		if(self::storePrices($row, $data)) {
			$result = array();
			$result['row'] = $row;
			$result['tr'] = CabsystemHelpersView::getHtml('district', '_entry', 'district', $this->getItem());
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
		
		$query->select('d.*, c.name AS city_name');
		$query->from('#__cabsystem_districts as d');
		$query->leftjoin('#__cabsystem_cities as c on c.city_id = d.city_id');
		$query->where('d.deleted IS NULL');
		$query->order('d.district');
		
		return $query;
	}

	protected function _buildWhere($query)
	{
		if(is_numeric($this->_district_id)) 
		{
		  $query->where('d.district_id = ' . (int) $this->_district_id);
		}
		
		if(is_numeric($this->_city_id)) 
		{
		  $query->where('d.city_id = ' . (int) $this->_city_id);
		}
		
		return $query;
	}
	
	/**
	* Delete a district
	* @param int ID of the district to delete
	* @return boolean True if successfully deleted
	*/
	public function delete($id = null)
	{
		$app = JFactory::getApplication();
		$id = $id ? $id : $app->input->get('district_id');
		
		$district = JTable::getInstance('districts','Table');
		$district->load($id);
		
		$district->deleted = date('Y-m-d H:i:s');
		
		if($district->store())
		{
			return true;
		} else {
			return false;
		}
	}
}