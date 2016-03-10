<?php
/**
 * @name InitController
 * @author rain
 * @desc 公共的初始化操作的控制器所有控制器都继承此Controller
 */
class InitController extends Yaf_Controller_Abstract
{
	public function init()
	{
	}

	protected function location($url)
	{
		$cache = Cache::getInstance('m');
		$key = 'mem_'.session_id();
		$sessionCache = $cache->get($key);
		if (empty($sessionCache)) {
			$sessionCache = [];
		}
		$sessionCache[$key]['location'] = $url;
		
		return $cache->set($key, $sessionCache, 86400);
	}

	protected function delSession($key)
	{
		$cache = Cache::getInstance('m');
		$memkey = 'mem_'.session_id();
		$sessionCache = $cache->get($memkey);

		if (empty($sessionCache)) {
			$sessionCache = [];
		}

		if (!isset($sessionCache[$memkey][$key]))
			return true;
		unset($sessionCache[$memkey][$key]);

		return $cache->set($memkey, $sessionCache, 86400);
	}

	protected function session($key, $val = null)
	{
		$cache = Cache::getInstance('m');
		$memkey = 'mem_'.session_id();
		$sessionCache = $cache->get($memkey);

		if (empty($sessionCache)) {
			$sessionCache = [];
		}

		if (is_null($val)) {
			if (!isset($sessionCache[$memkey][$key]))
				return false;
			return $sessionCache[$memkey][$key];
		} else {
			$sessionCache[$memkey][$key] = $val;
		}
		return $cache->set($memkey, $sessionCache, 86400);
	}
}
