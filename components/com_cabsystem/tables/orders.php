<?php

defined( '_JEXEC' ) or die( 'Restricted access' );



class TableOrders extends JTable

{

	/**

	 * Constructor

	 *

	 * @param object Database connector object

	 */

	function __construct(&$db)

	{

		parent::__construct('#__cabsystem_orders', 'order_id', $db);

	}

}