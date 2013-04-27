<?php

	class Engine_Router
	{

		private $app = null;

		public function __construct()
		{
			$settings = array(
				'debug' => DEBUG_MODE
			);

			$app = new \Slim\Slim($settings);

			$cookie = Config_Settings::getCookie();
			if ($cookie !== false)
			{
				$app->add($cookie);
			}

			$this->app = $app;
		}

		public function route()
		{
			$app = $this->app;

			$routes = Config_Settings::getRoutes();

			foreach ($routes as $name => $data)
			{
				$type = $data['type'];
				$uri = $data['uri'];

				$app->$type($uri, function() use ($name, $app) {
					Engine_App::call($name, $app);
				});
			}

			Config_Settings::applyCustomRoutes($app);

			$this->app->run();
		}

	}