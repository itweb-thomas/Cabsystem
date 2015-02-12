<?php

defined( '_JEXEC' ) or die( 'Restricted access' );



class TableCartypes extends JTable

{

	/**

	 * Constructor

	 *

	 * @param object Database connector object

	 */

	function __construct(&$db)

	{

		parent::__construct('#__cabsystem_cartypes', 'cartype_id', $db);

	}

}