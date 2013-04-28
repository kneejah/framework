<?php

	class Engine_Settings
	{

		public static function getRoutes()
		{
			$routes = array(
				'Home' => array('uri' => '/', 'type' => 'get')
			);

			if (Config::exists('system'))
			{
				$config = Config::get('system');
				$routes = array_merge($routes, $config->routes);
			}
			
			return $routes;
		}

		public static function applyCustomRoutes(&$app)
		{
			if (Config::exists('system'))
			{
				// @TODO: actually od something here
			}
		}

		public static function getCookie()
		{
			if (Config::exists('system'))
			{
				$config = Config::get('system');
				return $config->cookie;
			}
			else
			{
				return false;
			}
		}

	}