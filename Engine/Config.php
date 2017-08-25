<?php

	class Config
	{

		public static function get($file)
		{
			$file = ucwords($file);
			$class_name = "Config_" . $file;
			$class = new $class_name();

			return $class;
		}

		public static function exists($name)
		{
			$name = ucwords($name);
			return file_exists(APP_ROOT . "Config/$name.php");
		}

	}
