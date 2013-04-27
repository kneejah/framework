<?php

	abstract class Abstract_Config
	{
		public function __construct()
		{
			$configs = $this->configs();
			foreach ($configs as $key => $value)
			{
				$this->$key = $value;
			}
		}

		abstract public function configs();

	}