<?php

defined( '_JEXEC' ) or die( 'Restricted access' );



class TableStreets extends JTable

{

	/**

	 * Constructor

	 *

	 * @param object Database connector object

	 */

	function __construct(&$db)

	{

		parent::__construct('#__cabsystem_streets', 'street_id', $db);

	}

}