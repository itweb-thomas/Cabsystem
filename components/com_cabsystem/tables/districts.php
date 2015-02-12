<?php

defined( '_JEXEC' ) or die( 'Restricted access' );



class TableDistricts extends JTable

{

	/**

	 * Constructor

	 *

	 * @param object Database connector object

	 */

	function __construct(&$db)

	{

		parent::__construct('#__cabsystem_districts', 'district_id', $db);

	}

}