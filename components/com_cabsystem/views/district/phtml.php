<?php

// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );



jimport('joomla.application.component.view');



//Display partial views

class CabsystemViewsDistrictPhtml extends JView

{

	function render()

	{

		return parent::display();

	}

}