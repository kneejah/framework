<?php

	define('APP_ROOT', dirname(__FILE__) . "/../../");

	if (!defined('DEBUG_MODE'))
	{
		define('DEBUG_MODE', 1);
	}

	require APP_ROOT . 'vendor/autoload.php';

	// Class autoloader, doesn't deal with namespaces yet
	function class_autoloader($name)
	{
		// Shortcut for convenience
		if ($name == "Config")
		{
			$name = "Engine_Config";
		}

		$className = str_replace('_', '/', $name);
		$fileName = $className . '.php';

		// Look for file in the normal path first, then under system
		$path = APP_ROOT . $fileName;
		if (file_exists($path))
		{
			$result = @include_once($path);
		}
		else
		{
			$path = APP_ROOT . 'System/' . $fileName;
			$result = @include_once($path);
		}

		if ($result == false)
		{
			echo "Fatal error: could not include file ($path)\n";

			if (DEBUG_MODE)
			{
				echo "\nStack trace:\n";
				debug_print_backtrace();
			}

			die();
		}

		return ($result !== false);
	}
	spl_autoload_register('class_autoloader');

	$router = new Engine_Router();
	$router->route();