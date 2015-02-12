<?php

defined( '_JEXEC' ) or die( 'Restricted access' );



class TableCustomers extends JTable

{

	/**

	 * Constructor

	 *

	 * @param object Database connector object

	 */

	function __construct(&$db)

	{

		parent::__construct('#__cabsystem_customers', 'customer_id', $db);

	}

}