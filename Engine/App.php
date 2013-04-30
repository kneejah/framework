<?php

	class Engine_App
	{

		public static function call($cont, $app, $options)
		{
			$method = $app->request()->getMethod();

			$controller_name = "Controller_$cont";
			$controller = new $controller_name($app);
			
			$ret = $controller->$method();

			if ($options && isset($options['noview']))
			{
				// We're done
				die();
			}
			if ($options && isset($options['api']))
			{
				if (!$ret)
				{
					$ret = array();
				}
				echo json_encode($ret);
				die();
			}
			else
			{
				self::respond($cont, $app);
			}
		}

		public static function respond($cont, $app)
		{
			$method = $app->request()->getMethod();
			$new_cont = str_replace('_', '/', $cont);

			// Render the view and get all the params
			$view_name = "View_{$cont}_{$method}";
			$view = new $view_name($app);
			$vars = $view->render();

			// Load the actual template
			if (file_exists(APP_ROOT . 'Template'))
			{
				$loader = new Mustache_Loader_FilesystemLoader(APP_ROOT . 'Template');
			}
			else
			{
				$loader = new Mustache_Loader_FilesystemLoader(APP_ROOT . 'System/Template');
			}

			try
			{
				$loader->load($new_cont . "/" . $method);
			}
			catch (Exception $e)
			{
				// Fall back to try to load the system templates
				$loader = new Mustache_Loader_FilesystemLoader(APP_ROOT . 'System/Template');
			}

			$loaders = array(
				'loader' => $loader
			);

			if (file_exists(APP_ROOT . 'Partials'))
			{
				$partials_loader = new Mustache_Loader_FilesystemLoader(APP_ROOT . 'Partials');
				$loaders['partials_loader'] = $partials_loader;
			}

			$mustache = new Mustache_Engine($loaders);

			echo $mustache->render($new_cont . "/" . $method, $vars);
		}

	}