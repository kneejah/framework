<?php

	class Engine_App
	{

		public static function call($cont, $app, $options)
		{
			$method = $app->request()->getMethod();
			$response = $app->response();

			$controller_name = "Controller_$cont";
			$controller = new $controller_name($app);

			$success = null;
			try
			{
				$success = $controller->$method();
			}
			catch (Exception_Api $e)
			{
				$response->header('Content-Type', 'application/json; charset=utf8');
				$data = array('success' => false, 'error' => $e->getMessage());
				$response->write(json_encode($data));
				return $response;
			}

			if ($options && isset($options['noview']))
			{
				// We're done
				$response->write('');
				return $response;
			}
			if ($options && isset($options['api']))
			{
				if (!$success)
				{
					$success = array();
				}

				$response->header('Content-Type', 'application/json; charset=utf8');
				$data = array('success' => true, 'result' => $success);
				$response->write(json_encode($data));
				return $response;
			}
			else
			{
				$data = self::respond($cont, $app);
				$response->write($data);
				return $response;
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

			return $mustache->render($new_cont . "/" . $method, $vars);
		}

	}
