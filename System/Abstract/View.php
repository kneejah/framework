<?php

	abstract class Abstract_View
	{

		protected $app;

		public function __construct($app)
		{
			$this->app = $app;
		}

		abstract public function render();

	}