<?php

defined( '_JEXEC' ) or die( 'Restricted access' );



class TableSalutations extends JTable

{

	/**

	 * Constructor

	 *

	 * @param object Database connector object

	 */

	function __construct(&$db)

	{

		parent::__construct('#__cabsystem_salutations', 'salutation_id', $db);

	}

}