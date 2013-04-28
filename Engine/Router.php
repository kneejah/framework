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

			$cookie = Engine_Settings::getCookie();
			if ($cookie !== false)
			{
				$app->add($cookie);
			}

			$this->app = $app;
		}

		public function route()
		{
			$app = $this->app;

			$routes = Engine_Settings::getRoutes();

			foreach ($routes as $name => $data)
			{
				$types = explode(',', $data['type']);
				$uri = $data['uri'];

				foreach ($types as $type)
				{
					$app->$type($uri, function() use ($name, $app) {
						Engine_App::call($name, $app);
					});
				}
			}

			Engine_Settings::applyCustomRoutes($app);

			$this->app->run();
		}

	}