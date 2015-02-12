<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

class TableDrivers extends JTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db)
	{
		parent::__construct('#__cabsystem_drivers', 'driver_id', $db);
	}
}