<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

class TableLockouts extends JTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db)
	{
		parent::__construct('#__cabsystem_lockouts', 'lockout_id', $db);
	}
}