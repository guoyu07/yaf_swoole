<?php
/**
 * @name Bootstrap
 * @author Rain
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Yaf_Bootstrap_Abstract
{
	public function _initConfig()
	{
		Yaf_Registry::set('config', Yaf_Application::app()->getConfig());
	}


	/*
	public function _initSession(Yaf_Dispatcher $dispatcher)
	{
		session_start();
	}

	public function _initPlugin(Yaf_Dispatcher $dispatcher)
	{
		$objSitePlugin = new SitePlugin();
		$dispatcher->registerPlugin($objSitePlugin);
	}

	public function _initRoute(Yaf_Dispatcher $dispatcher)
	{
		//在这里注册自己的路由协议,默认使用简单路由
	}
	
	public function _initView(Yaf_Dispatcher $dispatcher)
	{
		//在这里注册自己的view控制器，例如smarty,firekylin
	}
	*/
}
