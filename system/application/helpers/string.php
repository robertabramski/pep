<?php

	class String
	{
		public function remove_ext($value)
		{
			return current(explode('.', $value));
		}
		
		public function get_file_ext($value)
		{
			return end(explode('.', $value));
		}
	}
	
?>