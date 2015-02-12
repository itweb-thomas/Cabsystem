<?php
defined( '_JEXEC' ) or die( 'Restricted access' ); 
jimport('joomla.application.component.controller');

class CabsystemControllersEmail extends JController
{
	public function execute()
	{
	}
	
	public function acceptOrder()
	{
		$app = JFactory::getApplication();		
		$order_id = $app->input->get('order_id');
		$driver_id = $app->input->get('driver_id');
		
		$model = new CabsystemModelsOrder();
		$model->set('id',$order_id);
		$order = $model->getItem();
		
		//Wenn die Bestellung schon von wem anderen akzeptiert wurde
		if($order->driver_id != $driver_id) {
			echo '<i class="icon-inactive fa fa-minus-circle fa-2x"></i> Die Bestellung wurde in der Zwischenzeit einem anderen Fahrer zugewiesen. Ihre Email ist veraltet und nicht mehr gültig.';
		}
		//Wenn nicht
		else {
			//Status auf ACCEPTED setzen
			$model->setStatus($order_id,2);
			echo '<i class="icon-active fa fa-check-circle fa-2x"></i> Die Bestellung wurde angenommen. Sie sind jetzt für diese Fahrt zuständig.';
		}
	}
	
	/*public function refuseOrder()
	{
		$app = JFactory::getApplication();		
		$order_id = $app->input->get('order_id');
		$driver_id = $app->input->get('driver_id');
		
		$model = new CabsystemModelsOrder();	
		//Status auf REFUSED setzen
		$model->setStatus($order_id,3);
		
		echo "Die Bestellung wurde abgelehnt. Sie sind für diese Fahrt also nicht zuständig.";
	}*/
}