<?php

use Illuminate\Support\Facades\Log;

class Logger
{
	
	public static function __callStatic($name, $arguments)
	{
		$msg = $arguments[0];
		
		$level = [
			         'warn'   => 'warning',
			         'info'   => 'info',
			         'severe' => 'critical',
			         'err'    => 'error',
			         'debug'  => 'debug'
		         ][$name];
		
		Log::$level($msg);
	}
	
}