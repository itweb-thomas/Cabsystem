<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

class CabsystemHelpersView
{
	function load($viewName, $layoutName='default', $viewFormat='html', $vars=null)
	{
		// Get the application
		$app = JFactory::getApplication();
		
		$app->input->set('view', $viewName);
		
		$viewClass  = 'CabsystemViews' . ucfirst($viewName) . ucfirst($viewFormat);
		
		$view = new $viewClass();
		
		// Register the layout paths for the view
		$view->addTemplatePath(JPATH_COMPONENT . '/views/' . $viewName . '/tmpl');
		
		$view->setLayout($layoutName);
		
		if (isset($vars))
		{
			foreach($vars as $varName => $var)
			{
				$view->$varName = $var;
			}
		}
		
		return $view;
	}
	
	function getHtml($view, $layout, $item, $data,$vars=null)
	{
		$objectView = CabsystemHelpersView::load($view, $layout, 'phtml');
		$objectView->$item = $data;
		
		if (isset($vars))
		{
			foreach($vars as $varName => $var)
			{
				$objectView->$varName = $var;
			}
		}
		
		ob_start();
		echo $objectView->render();
		$html = ob_get_contents();
		ob_clean();
		
		return $html;
	}
	
	/**
	 * Sendet eine Email mit den uebergebenen Parametern
	 * @return array ['ok'] => boolean, gesendet oder nicht | ['error'] => string, Fehlermeldung
	 */
	function sendMail($from_email_address, $from_email_name, $subject, $body, $to) {
		$from = array($from_email_address, $from_email_name);
		$mailer = JFactory::getMailer();
		$mailer->setSender($from);
		$mailer->addRecipient($to);
		$mailer->setSubject($subject);
		$mailer->setBody($body);
		$mailer->isHTML(true);
		return $mailer->Send();
	}
}