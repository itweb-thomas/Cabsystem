<?php

defined( '_JEXEC' ) or die( 'Restricted access' );



class TableAdditionalprices extends JTable

{

	/**

	 * Constructor

	 *

	 * @param object Database connector object

	 */

	function __construct(&$db)

	{

		parent::__construct('#__cabsystem_additionalprices', 'additionalprice_id', $db);

	}

}