<?php

	abstract class Abstract_Policy
	{

		protected $app = null;

		abstract protected function check();
		abstract protected function success();
		abstract protected function failure();

		public function __construct($app)
		{
			$this->app = $app;
		}

		public function ensure()
		{
			if ($this->check())
			{
				return $this->success();
			}
			else
			{
				return $this->failure();
			}
		}

	}