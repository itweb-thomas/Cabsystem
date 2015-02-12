<?php

defined( '_JEXEC' ) or die( 'Restricted access' );



class TablePrices extends JTable

{

	/**

	 * Constructor

	 *

	 * @param object Database connector object

	 */

	function __construct(&$db)

	{

		parent::__construct('#__cabsystem_prices', 'price_id', $db);

	}

}