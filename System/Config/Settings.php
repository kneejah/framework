<?php

	class Config_Settings
	{

		public static function getRoutes()
		{
			$routes = array(
				'Home' => array('uri' => '/', 'type' => 'get')
			);

			$config = Config::get('system');
			$routes = array_merge($routes, $config->routes);
			
			return $routes;
		}

		public static function applyCustomRoutes(&$app)
		{
			$app->post('/canvas/facebook/session', function() use ($app) {
				echo "<pre>";
				print_r($_SESSION);
				echo "</pre>";
			});
		}

		public static function getCookie()
		{
			$config = Config::get('system');
			return $config->cookie;
		}

	}