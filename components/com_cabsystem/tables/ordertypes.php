<?php

defined( '_JEXEC' ) or die( 'Restricted access' );



class TableOrdertypes extends JTable

{

	/**

	 * Constructor

	 *

	 * @param object Database connector object

	 */

	function __construct(&$db)

	{

		parent::__construct('#__cabsystem_ordertypes', 'ordertype_id', $db);

	}

}