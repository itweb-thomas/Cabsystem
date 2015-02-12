<?php

defined( '_JEXEC' ) or die( 'Restricted access' );



class TableAdditionaladdresses extends JTable

{

	/**

	 * Constructor

	 *

	 * @param object Database connector object

	 */

	function __construct(&$db)

	{

		parent::__construct('#__cabsystem_additionaladdresses', 'additionaladdress_id', $db);

	}

}