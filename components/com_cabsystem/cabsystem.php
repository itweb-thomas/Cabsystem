<?php

defined('_JEXEC') or die('Restricted access');

echo "TEST";

//sessions

jimport( 'joomla.session.session' );



//load tables

JTable::addIncludePath(JPATH_COMPONENT.'/tables');



//load classes

JLoader::registerPrefix('Cabsystem', JPATH_COMPONENT);



//Load styles and javascripts

CabsystemHelpersStyle::load();



//application

$app = JFactory::getApplication();

 

// Require specific controller if requested

$controller = $app->input->get('controller', 'default');
$task = $app->input->get('task','execute');

// Create the controller

$classname  = 'CabsystemControllers'.ucfirst($controller);
$controller = new $classname();


// Perform the Request task
$controller->$task();
$controller->redirect();