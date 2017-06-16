<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * ------------------------------------------------------
 *  Load module common
 * ------------------------------------------------------
 */
	// Module Common
	require_once APPPATH.'libraries/module/Common'.EXT;
	
	// Module Library
	require_once APPPATH.'libraries/module/Library'.EXT;
	
/*
 * ------------------------------------------------------
 *  Load module base
 * ------------------------------------------------------
 */
	// Module Config
	require_once APPPATH.'libraries/module/Config'.EXT;
	
	// Module Lang
	require_once APPPATH.'libraries/module/Lang'.EXT;
	
	// Module Load
	require_once APPPATH.'libraries/module/Load'.EXT;
	
	// Module Url
	require_once APPPATH.'libraries/module/Url'.EXT;
	
	// Module Controller
	require_once APPPATH.'libraries/module/Controller'.EXT;

   	// Module Model
	require_once APPPATH.'libraries/module/Model'.EXT;
	// Module Base
	require_once APPPATH.'libraries/module/Module'.EXT;
