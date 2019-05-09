<?php

	class Engine_Settings
	{

		public static function getRoutes()
		{
			$routes = array(
				array('name' => 'Home', 'uri' => '/', 'type' => 'get')
			);

			if (Config::exists('system'))
			{
				$config = Config::get('system');
				$routes = array_merge($config->routes, $routes);
			}
			
			return $routes;
		}

		public static function applyCustomRoutes(&$app)
		{
			if (Config::exists('system'))
			{
				// @TODO: actually do something here
			}
		}

		public static function getCookie()
		{
			if (Config::exists('cookie'))
			{
				$config = Config::get('cookie');
				return $config->cookie;
			}
			else
			{
				return false;
			}
		}

	}
