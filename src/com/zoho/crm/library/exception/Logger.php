<?php

use Illuminate\Support\Facades\Log;

class Logger
{
	
	public static function __callStatic($name, $arguments)
	{
		$msg = $arguments[0];
		
		$level = [
			         'debug'  => 'debug',
			         'err'    => 'error',
			         'info'   => 'info',
			         'severe' => 'critical',
			         'warn'   => 'warning',
		         ][$name];
		
		if(env('LOG_ZOHO', true)){
			Log::$level($msg);
		}
	}
	
}