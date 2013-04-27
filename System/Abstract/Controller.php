<?php

	abstract class Abstract_Controller
	{

		protected $app;

		public function __construct($app)
		{
			$this->app = $app;
		}

	}