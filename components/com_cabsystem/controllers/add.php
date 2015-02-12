<?php

defined( '_JEXEC' ) or die( 'Restricted access' ); 



jimport('joomla.application.component.controller');



class CabsystemControllersAdd extends JController

{

	public function execute()

	{

		$app      = JFactory::getApplication();

		$return   = array("success" => false);

		

		$modelName  = $app->input->get('model');

		$view       = $app->input->get('view');

		$layout     = $app->input->get('layout', '_entry');

		$item       = $app->input->get('item');

		

		$modelName  = 'CabsystemModels'.ucwords($modelName);

		

		$model = new $modelName();

		if ($result = $model->store())

		{

			$return['success'] = true;

			$return['msg'] = JText::_('COM_CABSYSTEM_'.strtoupper($item).'_ADD_SUCCESS');
			
			$return['tr_amount'] = is_array($result['tr']) ? count($result['tr']) : 1;
			$return['tr'] = $result['tr'];
			$return['datatable_data'] = $result['row'];

		}

		else

		{

			$return['msg'] = JText::_('COM_CABSYSTEM_'.strtoupper($item).'_ADD_FAILURE');

		}

		

		echo json_encode($return);

	}

}