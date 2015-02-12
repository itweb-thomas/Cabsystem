<?php

defined( '_JEXEC' ) or die( 'Restricted access' );



class TableCities extends JTable

{

	/**

	 * Constructor

	 *

	 * @param object Database connector object

	 */

	function __construct(&$db)

	{

		parent::__construct('#__cabsystem_cities', 'city_id', $db);

	}

}