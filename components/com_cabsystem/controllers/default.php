<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');
 
class CabsystemControllersDefault extends JController
{
	public function execute()
	{
		// Get the application
		$app = JFactory::getApplication();
		
		// Get the document object.
		$document = JFactory::getDocument();
		
		$viewName = $app->input->getWord('view', 'order');
		$viewFormat = $document->getType();
		$layoutName   = $app->input->getWord('layout', 'list');
		
		$app->input->set('view', $viewName);
		
		$view = CabsystemHelpersView::load($viewName, $layoutName, $viewFormat);
		
		// Render our view.
		echo $view->render();
		
		return true;
	}
}