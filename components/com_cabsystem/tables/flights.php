<?php

defined( '_JEXEC' ) or die( 'Restricted access' );



class TableFlights extends JTable

{

	/**

	 * Constructor

	 *

	 * @param object Database connector object

	 */

	function __construct(&$db)

	{

		parent::__construct('#__cabsystem_flights', 'flight_id', $db);

	}

}