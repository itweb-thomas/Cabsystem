<?php defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport('joomla.application.component.controller');

class CabsystemControllersUnlock extends JController
{
	public function execute()
	{
		$app = JFactory::getApplication();
		
		$return = array("success"=>false);
		
		$type = $app->input->get('type');
		
		$modelName = 'CabsystemModels'.ucfirst($type);
		$model = new $modelName();
	
		if ( $row = $model->unlock() )
		{
			$return['success'] = true;
			$return['msg'] = JText::_('COM_CABSYSTEM_'.strtoupper($type).'_UNLOCK_SUCCESS');
		}else
		{
			$return['msg'] = JText::_('COM_CABSYSTEM_'.strtoupper($type).'_UNLOCK_FAILURE');
		}
		
		echo json_encode($return);
	}
}