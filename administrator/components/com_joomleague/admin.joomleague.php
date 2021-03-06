<?php defined( '_JEXEC' ) or die( 'Restricted access' ); // no direct access
/**
* @copyright	Copyright (C) 2005-2012 JoomLeague.net. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

/*
 * Make sure the user is authorized to view this page
 */
 /*

$user =& JFactory::getUser();
if (!$user->authorize( 'com_joomleague', 'manage' ))
{
	$mainframe->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
}
*/

// require core
require_once ( JPATH_ROOT.DS.'components'.DS.'com_joomleague'.DS.'joomleague.core.php' );

// Require the base controller
require_once ( JPATH_COMPONENT .DS . 'controller.php' );
require_once ( JPATH_COMPONENT .DS . 'defines.php' );
require_once ( JPATH_COMPONENT .DS . 'tables' . DS . 'jltable.php' );
require_once ( JPATH_COMPONENT .DS . 'helpers' . DS . 'jlparameter.php' );
require_once ( JPATH_COMPONENT .DS . 'helpers' . DS . 'jlcommon.php' );
require_once ( JPATH_COMPONENT_SITE .DS . 'helpers' . DS . 'countries.php' );

// diddipoeler
include_once JLG_PATH_SITE . DS . 'helpers' . DS . 'feedreaderhelperr.php';
require_once(JLG_PATH_SITE . DS . 'helpers' . DS . 'simple-gmap-api' . DS . "simpleGMapAPI.php");
require_once(JLG_PATH_SITE . DS . 'helpers' . DS . 'simple-gmap-api' . DS . "simpleGMapGeocoder.php");

$controller = "";
$controllername = JRequest::getWord( 'controller', 'application' );
// Create the controller
$classname	= 'JoomleagueController' . ucfirst( $controllername );

// extension management
$extensions = JoomleagueHelper::getExtensions(JRequest::getInt('p'));
$modelspath = array();
$viewspath  = array();
foreach ($extensions as $e => $extension) 
{
	$controllername = JRequest::getWord( 'controller' );
	
	$JLGPATH_EXTENSION 			= JPATH_COMPONENT_SITE . DS . 'extensions' . DS . $extension;
	$JLGPATH_EXTENSION_ADMIN 	= $JLGPATH_EXTENSION . DS . 'admin';
	
	define('JLG_PATH_EXTENSION_'.strtoupper($extension), $JLGPATH_EXTENSION);
	define('JLG_PATH_EXTENSION_ADMIN_'.strtoupper($extension), $JLGPATH_EXTENSION_ADMIN);
	
	// add additional tables
	if (file_exists( $JLGPATH_EXTENSION_ADMIN . DS . 'tables' )) {
		JTable::addIncludePath( $JLGPATH_EXTENSION_ADMIN . DS . 'tables' );
	}
	
	// language file
	$lang = &JFactory::getLanguage();
	$lang->load('com_joomleague_'.$extension, $JLGPATH_EXTENSION_ADMIN);
	
	$path = $JLGPATH_EXTENSION_ADMIN . DS . 'controllers' . DS . $controllername . '.php';
	//use specific controller
	if ( !class_exists($classname)&& file_exists($path) ) { 
		require_once $path;
		// instantiate it
		$controller = new $classname();
	} 
	
	// prepare path to add to the controller
	if (file_exists($JLGPATH_EXTENSION_ADMIN . DS . 'models') || 1) {
		$modelspath[] = $JLGPATH_EXTENSION_ADMIN . DS . 'models';
	}
	if (file_exists($JLGPATH_EXTENSION_ADMIN . DS . 'views') || 1) {
		$viewspath[] = $JLGPATH_EXTENSION_ADMIN . DS . 'views';
	}
	if ( file_exists( $path )  && !is_object($controller))
	{
		require_once $path;
		if (class_exists($controllername.ucfirst($extension))) {
			$classname = $controllername.ucfirst($extension);
		}
	}
	if ( file_exists( $JLGPATH_EXTENSION_ADMIN . DS . $extension. '.php') )  {
		require_once $JLGPATH_EXTENSION_ADMIN . DS . $extension. '.php';
	} 
}

//not handled by an extension
if(!is_object($controller)) {
	//use specific controller
	$path = JPATH_COMPONENT . DS . 'controllers' . DS . $controllername . '.php';
	//echo " -> path: " . $path;
	if ( file_exists( $path ) ) {
		//echo " -> specific controller: " . $path;
		require_once $path;
		$classname	= 'JoomleagueController' . ucfirst( $controllername );
		$controller = new $classname();
	} else {
		//echo " -> fallback";
		//fallback use standard controller
		$classname	= 'JoomleagueController';
		$controller = new $classname();
	}
}

// add extensions path for models and views
foreach ($modelspath as $path) {
		$controller->addModelPath($path);		
}
foreach ($viewspath as $path) {
		$controller->addViewPath($path);		
}

// Perform the Request task
$controller->execute( JRequest::getCmd( 'task' ) );
$controller->redirect();

?>