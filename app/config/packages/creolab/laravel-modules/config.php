<?php

return array(

	/**
	 * The path that will contain our modules
	 * This can also be an array with multiple paths
	 */
	'path' => 'app/addons',

	/**
	 * If set to 'auto', the modules path will be scanned for modules
	 */
	'mode' => 'manual',

	/**
	 * In case the auto detect mode is disabled, these modules will be loaded
	 * If the mode is set to 'auto', this setting will be discarded
	 */
	'modules' => array(

	),

	/**
	 * Default files that are included automatically for each module
	 */
	'include' => array(
		'helpers.php',
		'filters.php',
        'bindings.php',
		'routes.php',
        'menu.php',
        'ajax.php'
	),

	/**
	 * Debug mode
	 */
	'debug' => true,

);
