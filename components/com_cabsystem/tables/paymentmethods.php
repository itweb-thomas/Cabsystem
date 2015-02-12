<?php

defined( '_JEXEC' ) or die( 'Restricted access' );



class TablePaymentmethods extends JTable

{

	/**

	 * Constructor

	 *

	 * @param object Database connector object

	 */

	function __construct(&$db)

	{

		parent::__construct('#__cabsystem_paymentmethods', 'paymentmethod_id', $db);

	}

}