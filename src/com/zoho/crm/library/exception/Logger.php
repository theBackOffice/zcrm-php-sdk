<?php

use Illuminate\Support\Facades\Storage;

require_once realpath(dirname(__FILE__)."/../common/ZCRMConfigUtil.php");
class Logger
{
	public static function writeToFile($msg)
	{
		if(env('APP_ENV') != 'local'){
			return;
		}
		
		Storage::append('ZCRMClientLibrary.log', $msg);
	}
	
	public static function warn($msg)
	{
		self::writeToFile("WARNING: $msg");
	}
	public static function info($msg)
	{
		self::writeToFile("INFO: $msg");
	}
	public static function severe($msg)
	{
		self::writeToFile("SEVERE: $msg");
	}
	public static function err($msg)
	{
		self::writeToFile("ERROR: $msg");
	}
	public static function debug($msg)
	{
		self::writeToFile("DEBUG: $msg");
	}
}
?>