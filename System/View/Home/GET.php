<?php

	class View_Home_GET extends Abstract_View
	{

		public function render()
		{
			return array('time' => time());
		}

	}