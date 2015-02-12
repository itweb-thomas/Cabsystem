<?php

defined( '_JEXEC' ) or die( 'Restricted access' );



class TableDestination_cities extends JTable

{

	/**

	 * Constructor

	 *

	 * @param object Database connector object

	 */

	function __construct(&$db)

	{

		parent::__construct('#__cabsystem_destination_cities', 'city_id', $db);

	}

}