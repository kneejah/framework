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

			foreach ($routes as $data)
			{
				$types = explode(',', $data['type']);
				$uri   = $data['uri'];

				foreach ($types as $type)
				{
					$passedOptions = null;

					if (isset($data['options']))
					{
						$options = $data['options'];

						if (isset($options[$type]))
						{
							$passedOptions = $options[$type];
						}
						else if (count($types) == 1)
						{
							$passedOptions = $options;
						}
					}

					$app->$type($uri, function() use ($data, $app, $passedOptions) {
						Engine_App::call($data['name'], $app, $passedOptions);
					});
				}
			}

			Engine_Settings::applyCustomRoutes($app);

			$this->app->run();
		}

	}