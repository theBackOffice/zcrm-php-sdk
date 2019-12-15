<?php

use Illuminate\Support\Facades\Storage;

require_once realpath(dirname(__FILE__)."/../common/OAuthLogger.php");
require_once realpath(dirname(__FILE__)."/../common/ZohoOAuthTokens.php");
class ZohoOAuthPersistenceByFile implements ZohoOAuthPersistenceInterface
{

    public function saveOAuthData($zohoOAuthTokens)
	{
		try{
			self::deleteOAuthTokens($zohoOAuthTokens->getUserEmailId());

            $serialized=Storage::get("zcrm_oauthtokens.txt");
			if($serialized=="")
			{
				$arr=array();
			}
			else{
				$arr=unserialize($serialized);
			}
			array_push($arr,$zohoOAuthTokens);
			$serialized=serialize($arr);
            Storage::put("zcrm_oauthtokens.txt", $serialized);
		}
		catch (Exception $ex)
		{
			OAuthLogger::severe("Exception occured while Saving OAuthTokens to file(file::ZohoOAuthPersistenceByFile)({$ex->getMessage()})\n{$ex}");
			throw $ex;
		}
	}

	public function getOAuthTokens($userEmailId)
	{
		try{

            $serialized=Storage::get("zcrm_oauthtokens.txt");
			if(!isset($serialized) || $serialized=="")
			{
				throw new ZohoOAuthException("No Tokens exist for the given user-identifier,Please generate and try again.");
			}
			$arr=unserialize($serialized);
			$tokens=new ZohoOAuthTokens();
			$isValidUser=false;
			foreach ($arr as $eachObj)
			{
				if($userEmailId===$eachObj->getUserEmailId())
				{
					$tokens=$eachObj;
					$isValidUser=true;
					break;
				}
			}
			if(!$isValidUser)
			{
				throw new ZohoOAuthException("No Tokens exist for the given user-identifier,Please generate and try again.");
			}

			return $tokens;
		}
		catch (ZohoOAuthException $e)
		{
			throw $e;
		}
		catch (Exception $ex)
		{
			OAuthLogger::severe("Exception occured while fetching OAuthTokens from file(file::ZohoOAuthPersistenceByFile)({$ex->getMessage()})\n{$ex}");
			throw $ex;
		}
	}

	public function deleteOAuthTokens($userEmailId)
	{
		try{

            $serialized=Storage::get("zcrm_oauthtokens.txt");
			if(!isset($serialized) || $serialized=="")
			{
				return;
			}
			$arr=unserialize($serialized);
			$found=false;
			$i=-1;
			foreach ($arr as $i=>$eachObj)
			{
				if($userEmailId===$eachObj->getUserEmailId())
				{
					$found=true;
					break;
				}
			}
			if($found)
			{
				unset($arr[$i]);
				$arr=array_values(array_filter($arr));
			}
			$serialized=serialize($arr);
            Storage::put("zcrm_oauthtokens.txt", $serialized);
		}
		catch (Exception $ex)
		{
			OAuthLogger::severe("Exception occured while Saving OAuthTokens to file(file::ZohoOAuthPersistenceByFile)({$ex->getMessage()})\n{$ex}");
			throw $ex;
		}
	}
}
