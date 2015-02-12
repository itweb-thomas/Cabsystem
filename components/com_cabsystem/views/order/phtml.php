
<?php

// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );



jimport('joomla.application.component.view');



//Display partial views

class CabsystemViewsOrderPhtml extends JView

{

	function render()

	{
		$app = JFactory::getApplication();
		$layout = $app->input->get('layout');
		
		switch($layout) {
			case "list":
			default:
				if(!empty($this->order->creator)) {
					$this->creator_object = JFactory::getUser($this->order->creator);
				}
				break;
		}
		
		return parent::display();

	}

}